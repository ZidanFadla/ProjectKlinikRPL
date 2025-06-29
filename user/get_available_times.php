<?php
include '../assets/db/database.php';

// Periksa apakah parameter date ada
if (!isset($_GET['date'])) {
    echo json_encode(['all_times' => [], 'booked_times' => []]);
    exit;
}

$date = $_GET['date'];

// Validasi tanggal
if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || strtotime($date) < strtotime(date('Y-m-d'))) {
    echo json_encode(['all_times' => [], 'booked_times' => []]);
    exit;
}

$openHour = 10;
$closeHour = 20;
$allTimes = [];

// Buat array jam dari 10:00 - 20:00 (tiap 30 menit), dalam format HH:MM untuk tampilan
for ($h = $openHour; $h < $closeHour; $h++) {
    $allTimes[] = sprintf("%02d:00", $h);
    $allTimes[] = sprintf("%02d:30", $h);
}
$allTimes[] = "20:00"; // Jam terakhir

// Ambil semua waktu yang sudah dibooked untuk tanggal tertentu (tanpa memfilter treatments_id)
$stmt = $conn->prepare("SELECT time FROM booking_treatments WHERE date = ? AND status != 'cancelled'");
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$bookedTimes = [];
while ($row = $result->fetch_assoc()) {
    $bookedTimes[] = $row['time']; // Format HH:MM:SS
}

// Kembalikan hasil dalam format JSON
header('Content-Type: application/json');
echo json_encode([
    'all_times' => $allTimes, // Semua slot waktu dalam format HH:MM
    'booked_times' => $bookedTimes // Waktu yang sudah dibooked dalam format HH:MM:SS
]);
