<?php
session_start();
$requiresAdmin = true;
include '../../auth.php'; // Proteksi akses
include '../../assets/db/database.php'; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $valid_until = $_POST['valid_until'];
    $discount = $_POST['discount'];
    $newImage = $_FILES['image']['name'];

    if (!empty($newImage)) {
        $targetDir = "../../uploads/";
        $targetFile = $targetDir . basename($newImage);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $result = $conn->query("SELECT image FROM promos WHERE id = $id");
            if ($result && $result->num_rows > 0) {
                $oldImage = $result->fetch_assoc()['image'];
                unlink($targetDir . $oldImage);
            }

            $stmt = $conn->prepare("UPDATE promos SET title = ?, description = ?, discount = ?, valid_until = ?, category = ?, image = ? WHERE id = ?");
            $stmt->bind_param("ssisssi", $title, $description, $discount, $valid_until, $category, $newImage, $id);
        } else {
            die("Gagal mengupload gambar baru.");
        }
    } else {
        $stmt = $conn->prepare("UPDATE promos SET title = ?, description = ?, discount = ?, valid_until = ?, category = ? WHERE id = ?");
            $stmt->bind_param("ssissi", $title, $description, $discount, $valid_until, $category, $id);
    }

    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
    } else {
        die("Error: " . $stmt->error);
    }
} else {
    die("Akses tidak valid.");

}
?>