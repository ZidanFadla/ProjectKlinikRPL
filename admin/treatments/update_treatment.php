<?php
session_start();
$requiresAdmin = true;
include '../../auth.php'; // Proteksi akses
include '../../assets/db/database.php'; // Koneksi database

// Pastikan semua data tersedia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $newImage = $_FILES['image']['name'];

    // Cek apakah gambar baru diupload
    if (!empty($newImage)) {
        // Upload gambar baru
        $targetDir = "../../uploads/";
        $targetFile = $targetDir . basename($newImage);

        // Pindahkan file yang diupload
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Hapus gambar lama dari database
            $result = $conn->query("SELECT image FROM treatments WHERE id = $id");
            if ($result && $result->num_rows > 0) {
                $oldImage = $result->fetch_assoc()['image'];
                unlink($targetDir . $oldImage);
            }

            // Update data termasuk gambar
            $stmt = $conn->prepare("UPDATE treatments SET name = ?, category = ?, description = ?, price = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sssdsi", $name, $category, $description, $price, $newImage, $id);
        } else {
            die("Gagal mengupload gambar baru.");
        }
    } else {
        // Update data tanpa mengganti gambar
        $stmt = $conn->prepare("UPDATE treatments SET name = ?, category = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("sssdi", $name, $category, $description, $price, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
    } else {
        die("Error: " . $stmt->error);
    }
} else {
    die("Akses tidak valid.");
}
?>
