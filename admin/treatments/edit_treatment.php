<?php
session_start();
$requiresAdmin = true;
include '../../auth.php'; // Proteksi akses
include '../../assets/db/database.php'; // Koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Treatment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include "../sidebar.php"; ?>
    <div class="ml-64 p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-700 mb-6">Edit Treatment</h1>
            <?php
            // Pastikan parameter `id` ada
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                die("ID treatment tidak ditemukan.");
            }

            $id = intval($_GET['id']);
            $result = $conn->query("SELECT * FROM treatments WHERE id = $id");

            // Cek apakah treatment ditemukan
            if ($result->num_rows === 0) {
                die("Treatment tidak ditemukan.");
            }

            $treatment = $result->fetch_assoc();
            ?>
            <form action="update_treatment.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $treatment['id'] ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Treatment</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($treatment['name']) ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($treatment['category']) ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi Treatment</label>
                    <textarea name="description" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required><?= htmlspecialchars($treatment['description']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Harga Treatment</label>
                    <input type="number" name="price" value="<?= $treatment['price'] ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Treatment</label>
                    <input type="file" name="image" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                    <p class="text-sm text-gray-600 mt-2">Gambar saat ini:</p>
                    <img src="../../uploads/<?= htmlspecialchars($treatment['image']) ?>" alt="Gambar Treatment" class="w-32 h-32 object-cover mt-2">
                </div>
                <div class="flex justify-between">
                    <a href="../dashboard.php" class="bg-gray-400 text-white py-2 px-4 rounded hover:bg-gray-500 transition">
                        Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                        Edit Treatment
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
