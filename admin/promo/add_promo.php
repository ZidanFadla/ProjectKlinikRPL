<?php
session_start();
$requiresAdmin = true;
include '../../auth.php'; // Proteksi akses admin
include '../../assets/db/database.php'; // Koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-300 via-blue-100 to-blue-300">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Tambah Promo</h2>
            <form action="add_promo_process.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">Judul Promo</label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <input type="text" name="category" id="category" class="w-full px-4 py-2 border rounded-md" placeholder="Contoh: Kosmetik" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">Deskripsi Promo</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border rounded-md" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="discount" class="block text-gray-700 font-semibold mb-2">Diskon (%)</label>
                    <input type="number" name="discount" id="discount" class="w-full px-4 py-2 border rounded-md" min="0">
                </div>

                <div class="mb-4">
                    <label for="valid_until" class="block text-gray-700 font-semibold mb-2">Berlaku Hingga</label>
                    <input type="date" name="valid_until" id="valid_until" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-semibold mb-2">Gambar Promo</label>
                    <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="flex justify-between">
                    <a href="../dashboard.php" class="bg-gray-400 text-white py-2 px-4 rounded hover:bg-gray-500 transition">
                        Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                        Tambah Promo
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>