<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../assets/db/database.php');
include('../../auth.php');

$feedback = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $price    = $_POST['price'];
    $stock    = $_POST['stock'];
    $categoryRaw = $_POST['category_raw'];
    $category = "Product-" . $categoryRaw;

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp   = $_FILES['image']['tmp_name'];
        $targetDir  = "../../uploads/";
        $targetFile = $targetDir . basename($image);

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($tmp, $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdiss", $name, $price, $stock, $image, $category);

            if ($stmt->execute()) {
                $feedback = ['type' => 'success', 'msg' => 'Produk berhasil ditambahkan!'];
            } else {
                $feedback = ['type' => 'error', 'msg' => 'Gagal simpan ke database: ' . $stmt->error];
            }
        } else {
            $feedback = ['type' => 'error', 'msg' => 'Gagal mengunggah gambar.'];
        }
    } else {
        $feedback = ['type' => 'warning', 'msg' => 'Gambar produk wajib diunggah.'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
  <?php include "../sidebar_add_edit.php"; ?>

  <!-- Konten utama -->
  <main class="flex-1 ml-64 p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-blue-600"><i class="fas fa-box-open mr-2"></i>Tambah Produk</h2>
        <a href="../dashboard.php" class="text-blue-500 hover:text-blue-700 flex items-center">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
      </div>

      <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Nama Produk <span class="text-red-500">*</span></label>
            <input type="text" name="name" placeholder="Nama Produk" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
            <select name="category_raw" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
              <option value="" disabled selected>Pilih Kategori</option>
              <option value="Skincare">Skincare</option>
              <option value="Kosmetik">Kosmetik</option>
              <option value="Haircare">Haircare</option>
              <option value="Facecare">Facecare</option>
              <option value="Bodycare">Bodycare</option>
              <option value="Makeup">Makeup</option>
              <option value="Nailcare">Nailcare</option>
              <option value="Fragrance">Fragrance</option>
              <option value="Tools">Tools</option>
              <option value="Tester">Tester</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Harga <span class="text-red-500">*</span></label>
            <input type="number" name="price" placeholder="Harga" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
          </div>
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Stok <span class="text-red-500">*</span></label>
            <input type="number" name="stock" placeholder="Jumlah Stok" required class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
          </div>
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-2">Gambar Produk <span class="text-red-500">*</span></label>
          <label class="flex flex-col items-center justify-center h-32 border-2 border-dashed rounded-lg hover:bg-gray-50 hover:border-blue-400 cursor-pointer transition">
            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-1"></i>
            <p class="text-sm text-gray-600">Upload gambar produk</p>
            <input type="file" name="image" id="image" accept="image/*" required class="hidden">
          </label>
          <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (maks. 2MB)</p>
          <img id="preview" class="hidden mt-2 w-32 h-32 object-cover rounded shadow" />
        </div>

        <div class="flex justify-end space-x-3 pt-4">
          <a href="../dashboard.php" class="px-6 py-3 border rounded-md text-gray-700 hover:bg-gray-100 transition">
            <i class="fas fa-times mr-2"></i> Batal
          </a>
          <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
            <i class="fas fa-save mr-2"></i> Tambah Produk
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    // Preview gambar otomatis
    document.getElementById("image").addEventListener("change", function(e) {
      const reader = new FileReader();
      reader.onload = () => {
        const preview = document.getElementById("preview");
        preview.src = reader.result;
        preview.classList.remove("hidden");
      };
      reader.readAsDataURL(e.target.files[0]);
    });

    <?php if ($feedback): ?>
      Swal.fire({
        icon: '<?= $feedback['type'] ?>',
        title: '<?= $feedback['msg'] ?>',
        showConfirmButton: <?= $feedback['type'] === 'success' ? 'false' : 'true' ?>,
        timer: <?= $feedback['type'] === 'success' ? 1500 : 3000 ?>
      }).then(() => {
        <?php if ($feedback['type'] === 'success'): ?>
          window.location.href = "../dashboard.php";
        <?php endif; ?>
      });
    <?php endif; ?>
  </script>
</body>
</html>
