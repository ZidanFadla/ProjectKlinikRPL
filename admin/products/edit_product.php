<?php
session_start();
$requiresAdmin = true;
include '../../auth.php'; // Proteksi akses
include '../../assets/db/database.php'; // Koneksi database
?>


<!-- edit_product.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include "../sidebar.php"; ?>
    <div class="ml-64 p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-700 mb-6">Edit Produk</h1>
            <?php
            include '../../auth.php'; // Proteksi akses
            include '../../assets/db/database.php'; // Koneksi database

            // Pastikan parameter `id` ada
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                die("ID produk tidak ditemukan.");
            }

            $id = intval($_GET['id']);
            $result = $conn->query("SELECT * FROM products WHERE id = $id");

            // Cek apakah produk ditemukan
            if ($result->num_rows === 0) {
                die("Produk tidak ditemukan.");
            }

            $product = $result->fetch_assoc();
            ?>
            <form action="update_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Produk</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Harga Produk</label>
                    <input type="number" name="price" value="<?= $product['price'] ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Stok Produk</label>
                    <input type="number" name="stock" value="<?= $product['stock'] ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Produk</label>
                    <input type="file" name="image" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                    <p class="text-sm text-gray-600 mt-2">Gambar saat ini:</p>
                    <img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="Gambar Produk" class="w-32 h-32 object-cover mt-2">
                </div>
                <div class="flex justify-between">
                    <a href="../dashboard.php" class="bg-gray-400 text-white py-2 px-4 rounded hover:bg-gray-500 transition">
                        Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                        Edit Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
