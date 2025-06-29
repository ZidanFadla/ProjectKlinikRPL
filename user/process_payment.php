<?php
session_start();
header('Content-Type: application/json'); // Tambahkan ini di awal
include '../assets/db/database.php';
require_once '../vendor/autoload.php';

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-p_rr6ZhgUcuXXt7ZJaAJsSM2';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

try {
    $user_id = $_SESSION['user_id'];

    // Ambil data user
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        throw new Exception('User tidak ditemukan');
    }

    // Ambil items dari keranjang
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $items = $stmt->get_result();

    $total = 0;
    $item_details = [];

    while($item = $items->fetch_assoc()) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        
        $item_details[] = [
            'id' => (string)$item['product_id'],
            'price' => (int)$item['price'],
            'quantity' => (int)$item['quantity'],
            'name' => $item['name']
        ];
    }

    if (empty($item_details)) {
        throw new Exception('Keranjang kosong');
    }

    $transaction_details = [
        'order_id' => 'ORDER-' . time(),
        'gross_amount' => (int)$total
    ];

    $customer_details = [
        'first_name' => $user['name'],
        'email' => $user['email']
    ];

    $transaction = [
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details
    ];

    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    echo json_encode(['token' => $snapToken]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}