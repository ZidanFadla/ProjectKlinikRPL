<?php
session_start();
include '../assets/db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Item berhasil dihapus dari keranjang!";
    } else {
        $_SESSION['error'] = "Gagal menghapus item dari keranjang!";
    }
}

header("Location: cart.php");
exit;