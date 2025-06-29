<?php
session_start();
include '../assets/db/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include "../layout/navbar.php"; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-green-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold mb-4">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mb-6">Terima kasih telah berbelanja di toko kami.</p>
            <a href="product.php" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition">
                Lanjut Belanja
            </a>
        </div>
    </div>
</body>
</html>