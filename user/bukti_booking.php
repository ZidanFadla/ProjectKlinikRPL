<?php
include('../assets/db/database.php');

if (!isset($_GET['id'])) {
    die("ID booking tidak ditemukan.");
}

$booking_id = (int)$_GET['id'];
$stmt = $conn->prepare("
    SELECT bt.*, t.name as treatment_name 
    FROM booking_treatments bt 
    JOIN treatments t ON bt.treatments_id = t.id 
    WHERE bt.id = ?
");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data booking tidak ditemukan.");
}

$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Booking | Azra Clinic</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white shadow-xl rounded-2xl p-10 max-w-xl w-full border border-pink-100 relative">
        <!-- Branding -->
        <div class="absolute top-5 right-5 text-s text-blue-400 italic">Azra Clinic</div>

        <!-- Heading -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">💆 Bukti Booking Treatment</h1>
            <p class="text-sm text-gray-500 mt-1">Terima kasih telah mempercayakan perawatan Anda kepada Azra Clinic!</p>
            <hr class="my-4 border-pink-200">
        </div>

        <!-- Detail Booking -->
        <div class="space-y-4 text-gray-700 text-[15px]">
            <div class="flex justify-between">
                <span class="font-medium">Nama</span>
                <span><?= htmlspecialchars($booking['name']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Email</span>
                <span><?= htmlspecialchars($booking['email']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Treatment</span>
                <span><?= htmlspecialchars($booking['treatment_name']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Tanggal</span>
                <span><?= date('d M Y', strtotime($booking['date'])) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Jam</span>
                <span><?= date('H:i', strtotime($booking['time'])) ?> WIB</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Status</span>
                <span class="capitalize font-semibold text-<?= $booking['status'] == 'pending' ? 'yellow-500' : ($booking['status'] == 'confirmed' ? 'green-600' : 'gray-500') ?>">
                    <?= ucfirst($booking['status']) ?>
                </span>
            </div>
        </div>

        <!-- Footer & Print -->
        <style>
            @media print {
                .print-hide {
                    display: none;
                }
            }
        </style>

        <div class="mt-10 text-center space-y-4">
            <button onclick="window.print()" class="bg-blue-500 hover:bg-pink-600 text-white px-6 py-3 rounded-xl shadow-md transition-all text-sm font-medium print-hide">
                🖨️ Cetak Bukti Booking
            </button>
            <a href="treatment.php" class="block">
                <button class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl shadow-md transition-all text-sm font-medium print-hide">
                    ⬅ Kembali ke Treatments
                </button>
            </a>
            <p class="text-xs text-gray-400 mt-4 italic">Silakan tunjukkan bukti ini saat datang ke klinik.</p>
        </div>
    </div>

</body>

</html>