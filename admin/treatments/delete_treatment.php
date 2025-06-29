<?php
session_start();
$requiresAdmin = true;
include('../../auth.php'); // Proteksi akses admin
include('../../assets/db/database.php'); // Koneksi database

// Pastikan request menggunakan metode GET dan memiliki parameter id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Validasi `id` sebagai angka

    // Hapus data berdasarkan id
    $stmt = $conn->prepare("DELETE FROM treatments WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
    } else {
        header("Location: ../dashboard.php");
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect jika akses tidak valid
    header("Location: ../dashboard.php");
    exit;
}
?>

