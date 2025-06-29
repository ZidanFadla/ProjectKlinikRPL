<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$requiresAdmin = true;
include('../auth.php'); // Proteksi akses admin
include('../assets/db/database.php'); // Koneksi database
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- Konten Utama -->
    <div class="bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 ml-64 p-6">
        <!-- Manajemen Promo -->
        <section id="promo" class="mb-12">
            <div class="container mx-auto px-4 py-6">
                <div class="bg-blue-300 shadow-lg rounded-lg p-6 mb-8 max-w-3xl mx-auto flex justify-center items-center">
                    <h2 class="text-3xl font-bold text-gray-800 text-center">MANAJEMEN PROMO</h2>
                </div>
            </div>
            <!-- Konten Promo -->
            <?php include "promo/promos.php"; ?>
        </section>

        <!-- Manajemen Produk -->
        <section id="product" class="mb-12">
            <div class="container mx-auto px-4 py-6">
                <div class="bg-blue-300 shadow-lg rounded-lg p-6 mb-8 max-w-3xl mx-auto flex justify-center items-center">
                    <h2 class="text-3xl font-bold text-gray-800 text-center">MANAJEMEN PRODUK</h2>
                </div>
            </div>
            <!-- Konten Produk -->
            <?php include "products/products.php"; ?>
        </section>

        <!-- Manajemen Treatment -->
        <section id="treatment" class="mb-12">
            <div class="container mx-auto px-4 py-6">
                <div class="bg-blue-300 shadow-lg rounded-lg p-6 mb-8 max-w-3xl mx-auto flex justify-center items-center">
                    <h2 class="text-3xl font-bold text-gray-800 text-center">MANAJEMEN TREATMENT</h2>
                </div>
            </div>
            <!-- Konten Treatment -->
            <?php include "treatments/treatments.php"; ?>
        </section>

        <!-- Manajemen Booking -->
        <section id="booking" class="mb-12">
            <div class="container mx-auto px-4 py-6">
                <div class="bg-blue-300 shadow-lg rounded-lg p-6 mb-8 max-w-3xl mx-auto flex flex-col justify-center items-center space-y-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h2 class="text-3xl font-bold text-gray-800 text-center">Booking</h2>
                </div>
            </div>

            <!-- Konten Treatment -->
            <?php include "booking/admin_booking.php"; ?>
        </section>
    </div>
</body>

</html>