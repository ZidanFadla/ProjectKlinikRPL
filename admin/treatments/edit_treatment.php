<?php
session_start();
$requiresAdmin = true;
include '../../auth.php';
include '../../assets/db/database.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Treatment</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
  <?php include "../sidebar_add_edit.php"; ?>
  <main class="flex-1 ml-64 p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-blue-600">Edit Treatment</h2>
        <a href="../dashboard.php" class="text-blue-500 hover:text-blue-700 flex items-center">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
      </div>
      <?php
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            die("ID treatment tidak ditemukan.");
        }

        $id = intval($_GET['id']);
        $result = $conn->query("SELECT * FROM treatments WHERE id = $id");

        if ($result->num_rows === 0) {
            die("Treatment tidak ditemukan.");
        }

        $treatment = $result->fetch_assoc();
        $categoryRaw = str_replace("Treatment-", "", $treatment['category'] ?? "");
      ?>
      <form action="update_treatment.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?= $treatment['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama Treatment</label>
            <input type="text" name="name" value="<?= htmlspecialchars($treatment['name']) ?>" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
            <select name="category_raw" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
              <option disabled>Pilih Kategori</option>
              <option value="Hair Care" <?= $categoryRaw === 'Hair Care' ? 'selected' : '' ?>>Hair Care</option>
              <option value="Face Care" <?= $categoryRaw === 'Face Care' ? 'selected' : '' ?>>Face Care</option>
              <option value="Body Care" <?= $categoryRaw === 'Body Care' ? 'selected' : '' ?>>Body Care</option>
              <option value="Anti-Aging" <?= $categoryRaw === 'Anti-Aging' ? 'selected' : '' ?>>Anti-Aging</option>
              <option value="Slimming" <?= $categoryRaw === 'Slimming' ? 'selected' : '' ?>>Slimming</option>
              <option value="Brightening" <?= $categoryRaw === 'Brightening' ? 'selected' : '' ?>>Brightening</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-2">Deskripsi Treatment</label>
          <textarea name="description" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"><?= htmlspecialchars($treatment['description']) ?></textarea>
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-2">Harga Treatment</label>
          <input type="number" name="price" value="<?= $treatment['price'] ?>" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-2">Gambar Treatment</label>
          <input type="file" name="image" class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
          <p class="text-sm text-gray-600 mt-2">Gambar saat ini:</p>
          <img src="../../uploads/<?= htmlspecialchars($treatment['image']) ?>" alt="Gambar Treatment" class="w-32 h-32 object-cover mt-2">
        </div>

        <div class="flex justify-end pt-4">
          <a href="../dashboard.php" class="px-6 py-3 border rounded-md text-gray-700 hover:bg-gray-100 transition">
            <i class="fas fa-times mr-2"></i> Batal
          </a>
          <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
            <i class="fas fa-save mr-2"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
