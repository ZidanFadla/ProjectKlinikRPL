<!-- Page ini untuk membuat user dengan role admin. Contoh di bawah usernamenya adit dan passwordnya adit123 yang kemudian dienkripsi -->
<!-- Jalankan page ini pada browser atau localhost/klinik_pweb/create_admin.php -->

<?php
include('assets/db/database.php');

$username = 'adit';
$password = password_hash('adit123', PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $conn->prepare("INSERT INTO users (username, password, role, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    echo "Admin berhasil dibuat.";
    header("location: login.php");
} else {
    echo "Gagal membuat admin: " . $conn->error;
}

$stmt->close();
$conn->close();
?>