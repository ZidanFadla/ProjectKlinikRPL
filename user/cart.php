<?php
session_start();
include '../assets/db/database.php';
require_once '../vendor/autoload.php'; // Pastikan path benar

// Aktifkan logging error ke file
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');
ini_set('display_errors', 0);

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-cVfq0eR0c_nzvCamzZSlvTx0'; // Ganti dengan server key Anda
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil data keranjang
$user_id = (int) $_SESSION['user_id'];
try {
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result();
} catch (Exception $e) {
    error_log('Database error (fetch cart): ' . $e->getMessage());
    die("Database error: " . $e->getMessage());
}

// Hitung total dan siapkan data untuk Snap token
$total = 0;
$snap_token = null;
$items = [];
if ($cart_items->num_rows > 0) {
    // Hitung total
    while ($item = $cart_items->fetch_assoc()) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        $items[] = $item;
    }

    // Ambil data user
    try {
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            error_log('User not found for user_id: ' . $user_id);
            die("Error: User not found");
        }
    } catch (Exception $e) {
        error_log('Database error (fetch user): ' . $e->getMessage());
        die("Database error: " . $e->getMessage());
    }

    // Validasi total
    if ($total <= 0) {
        error_log('Invalid total: ' . $total);
        die("Error: Total amount must be a positive integer");
    }

    // Parameter untuk Midtrans
    $order_id = 'ORDER-' . $user_id . '-' . time();
    $transaction_details = [
        'order_id' => $order_id,
        'gross_amount' => $total
    ];

    $transaction_data = [
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details
    ];

    // Simpan transaksi ke database
    try {
        $stmt = $conn->prepare("
            INSERT INTO transactions (user_id, order_id, total, status) 
            VALUES (?, ?, ?, 'pending')
        ");
        $stmt->bind_param("isi", $user_id, $order_id, $total);
        $stmt->execute();
    } catch (Exception $e) {
        error_log('Database error (save transaction): ' . $e->getMessage());
        die("Failed to save transaction: " . $e->getMessage());
    }

    // Hasilkan Snap token
    try {
        $snap_token = \Midtrans\Snap::getSnapToken($transaction_data);
    } catch (Exception $e) {
        error_log('Midtrans error: ' . $e->getMessage());
        die("Midtrans error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-qFjVxhY0U4Wu5Klp"></script>
</head>

<body class="bg-gray-100">
    <?php include "../layout/navbar.php"; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

        <?php if (!empty($items)): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <?php foreach ($items as $item): ?>
                    <div class="flex items-center border-b py-4">
                        <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" class="w-20 h-20 object-cover rounded">
                        <div class="flex-1 ml-4">
                            <h3 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-gray-600">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                            <p class="text-sm">Jumlah: <?= htmlspecialchars($item['quantity']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
                            <form action="remove_cart_item.php" method="POST" class="mt-2">
                                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="mt-6 text-right">
                    <p class="text-xl font-bold">Total: Rp <?= number_format($total, 0, ',', '.') ?></p>
                    <button id="pay-button" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600" <?= $snap_token ? '' : 'disabled' ?>>
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-600">Keranjang belanja Anda kosong</p>
                <a href="product.php" class="text-blue-500 hover:text-blue-700">Belanja Sekarang</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const snapToken = <?= json_encode($snap_token) ?>;
        document.getElementById('pay-button').onclick = function() {
            if (!snapToken) {
                alert('Gagal menghasilkan token pembayaran. Silakan coba lagi.');
                return;
            }

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.href = 'payment_success.php';
                },
                onPending: function(result) {
                    alert('Pembayaran pending');
                },
                onError: function(result) {
                    alert('Pembayaran gagal');
                },
                onClose: function() {
                    alert('Anda menutup popup pembayaran');
                }
            });
        };
    </script>
</body>

</html>