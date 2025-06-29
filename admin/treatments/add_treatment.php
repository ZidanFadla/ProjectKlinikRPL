<?php
include('../../auth.php'); 
include('../../assets/db/database.php');

// DEBUG: Pastikan koneksi database tersedia
if (!isset($conn)) {
    die("Error: Koneksi database tidak tersedia. Periksa file database.php");
}   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $targetDir = "../../uploads/";
    $targetFile = $targetDir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO treatments (name, category, description, price, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $name, $category, $description, $price, $image);

        if ($stmt->execute()) {
            header("Location: ../dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Gagal mengunggah gambar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Treatment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-300 via-blue-100 to-blue-300">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Tambah Treatment</h2>
            <form action="add_treatment.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Treatment</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <input type="text" name="category" id="category" class="w-full px-4 py-2 border rounded-md" placeholder="Contoh: Product-Kosmetik" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">Deskripsi Treatment</label>
                    <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-md" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-semibold mb-2">Harga</label>
                    <input type="number" name="price" id="price" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-semibold mb-2">Gambar Treatment</label>
                    <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="flex justify-between">
                    <a href="../dashboard.php" class="bg-gray-400 text-white py-2 px-4 rounded hover:bg-gray-500 transition">
                        Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                        Tambah Treatment
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

