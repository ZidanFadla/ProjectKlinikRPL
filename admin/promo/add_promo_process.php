<?php
session_start();
$requiresAdmin = true;
include '../../auth.php'; // Proteksi akses admin
include '../../assets/db/database.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $discount = $_POST['discount'];
    $valid_until = $_POST['valid_until'];
    $category = $_POST['category'];
    $imageName = $_FILES['image']['name'];
    $targetDir = "../../uploads/";
    $targetFile = $targetDir . basename($imageName);

    // Upload file gambar
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        // Simpan data ke database
        $stmt = $conn->prepare("INSERT INTO promos (title, description, image, valid_until, discount, category) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis",$title, $description, $imageName, $valid_until, $discount, $category);

        if ($stmt->execute()) {
            header("Location: ../dashboard.php");
        } else {
            die("Error: " . $stmt->error);
        }
    } else {
        die("Gagal mengupload promo atau gambar.");
    }
} else {
    die("Akses tidak valid.");
}
?>