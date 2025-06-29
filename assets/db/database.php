<?php
$host = 'localhost'; 
$username = 'root'; 
$password = '';
$database = 'klinik_azra'; 

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} 
    // else {
//     echo "Koneksi berhasil ke database: " . $database;
// }
// Pastikan koneksi database sudah benar
?>
