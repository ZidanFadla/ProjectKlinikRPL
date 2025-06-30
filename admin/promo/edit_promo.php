<?php
session_start();
$requiresAdmin = true;
include '../../auth.php';
include '../../assets/db/database.php';

$feedback = null;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID promo tidak ditemukan.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM promos WHERE id = $id");

if ($result->num_rows === 0) {
    die("Promo tidak ditemukan.");
}

$promo = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <?php include "../sidebar_add_edit.php"; ?>
    <div class="ml-64 p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-700 mb-6">Edit Promo</h1>
            <form action="update_promo.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $promo['id'] ?>">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Judul Promo</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($promo['title']) ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <select name="category_raw" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                        <option disabled>Pilih Kategori</option>
                        <?php
                        $kategoriList = [
                            "Product-Skincare",
                            "Product-Kosmetik",
                            "Treatment-Hair Care",
                            "Treatment-Face Care",
                            "Treatment-Body Care",
                            "Treatment-Anti-Aging"
                        ];
                        foreach ($kategoriList as $kategori) {
                            $selected = ($promo['category'] == $kategori) ? 'selected' : '';
                            echo "<option value='$kategori' $selected>$kategori</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi Promo</label>
                    <textarea name="description" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required><?= htmlspecialchars($promo['description']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Diskon</label>
                    <input type="number" name="discount" value="<?= $promo['discount'] ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Berlaku Hingga</label>
                    <input type="date" name="valid_until" value="<?= $promo['valid_until'] ?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Promo</label>
                    <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                    <p class="text-sm text-gray-600 mt-2">Gambar saat ini:</p>
                    <div class="flex gap-4 items-center">
                        <img src="../../uploads/<?= htmlspecialchars($promo['image']) ?>" alt="Gambar Promo" class="w-32 h-32 object-cover rounded border">
                        <img id="preview" class="hidden w-32 h-32 object-cover rounded border" />
                    </div>
                </div>
                <div class="flex justify-between">
                    <a href="../dashboard.php" class="bg-gray-400 text-white py-2 px-4 rounded hover:bg-gray-500 transition">
                        Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                        Edit Promo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
      document.getElementById("image").addEventListener("change", function(e) {
        const reader = new FileReader();
        reader.onload = () => {
          const preview = document.getElementById("preview");
          preview.src = reader.result;
          preview.classList.remove("hidden");
        };
        reader.readAsDataURL(e.target.files[0]);
      });
    </script>
</body>
</html>
