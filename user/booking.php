<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
$database_path = '../assets/db/database.php';
if (!file_exists($database_path)) {
    die("File database.php tidak ditemukan di: $database_path. Periksa path atau keberadaan file.");
}
include($database_path);

// Periksa apakah koneksi berhasil
if (!isset($conn)) {
    die("Koneksi database gagal. Silakan periksa konfigurasi database di assets/db/database.php.");
}

// Ambil treatments_id dari URL
$treatments_id = isset($_GET['treatments_id']) ? (int)$_GET['treatments_id'] : 0;

// Validasi treatments_id
if ($treatments_id <= 0) {
    die("❌ Treatment tidak valid. Silakan pilih treatment dari halaman treatments.");
}

// Ambil data treatment untuk ditampilkan
$treatmentQuery = $conn->prepare("SELECT name FROM treatments WHERE id = ?");
$treatmentQuery->bind_param("i", $treatments_id);
$treatmentQuery->execute();
$treatmentResult = $treatmentQuery->get_result();
$treatmentData = $treatmentResult->fetch_assoc();

if (!$treatmentData) {
    die("❌ Treatment tidak ditemukan.");
}
$treatmentName = $treatmentData['name'];

// Inisialisasi pesan
$error_message = '';
$success_message = '';

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Validasi input
    if (empty($name) || empty($email) || empty($date) || empty($time)) {
        $error_message = "Harap lengkapi semua data booking.";
    } else {
        // Validasi tanggal tidak boleh di masa lalu
        $today = date("Y-m-d");
        if ($date < $today) {
            $error_message = "❌ Tidak bisa booking untuk tanggal yang sudah lewat.";
        } else {
            // Format waktu ke HH:MM:SS untuk disimpan ke database
            $time = $time . ":00";

            // Simpan data booking dengan status langsung confirmed
            $stmt = $conn->prepare("INSERT INTO booking_treatments (name, email, treatments_id, date, time, status) VALUES (?, ?, ?, ?, ?, 'confirmed')");
            if ($stmt) {
                $stmt->bind_param("ssiss", $name, $email, $treatments_id, $date, $time);
                $success = $stmt->execute();

                if ($success) {
                    $booking_id = $stmt->insert_id;

                    // Kirim email pemberitahuan
                    $msg_user = "Halo $name,\n\nPemesanan treatment '$treatmentName' pada tanggal $date jam $time berhasil dibuat. Status: Confirmed.";
                    $msg_admin = "Ada booking baru:\nNama: $name\nTreatment: $treatmentName\nTanggal: $date\nJam: $time\nStatus: Confirmed";

                    // Catatan: pastikan fungsi mail() aktif di server
                    if (!empty($email)) {
                        mail($email, "Pemesanan Treatment Anda", $msg_user);
                    }
                    mail("admin@klinik.com", "Booking Baru", $msg_admin);

                    // Redirect ke halaman bukti booking
                    header("Location: bukti_booking.php?id=$booking_id");
                    exit;
                } else {
                    $error_message = "❌ Terjadi kesalahan saat menyimpan data booking: " . $stmt->error;
                }
            } else {
                $error_message = "❌ Gagal mempersiapkan statement: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking Treatments</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[url('../uploads/Rumah-Sakit2.jpg')] bg-gray-200 bg-cover bg-center min-h-screen flex items-center justify-center">
    <div class="bg-black bg-opacity-50 min-h-screen w-full flex items-center justify-center py-8">
        <div class="bg-white p-8 rounded-xl shadow-md max-w-md w-full transform transition duration-300 hover:shadow-xl">
            <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-8 bg-gradient-to-r from-blue-400 to-blue-600 text-transparent bg-clip-text">
                Booking Treatment Anda
            </h2>

            <!-- Tampilkan Nama Treatment -->
            <div class="mb-6 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded-md text-sm">
                Anda akan memesan treatment: <strong><?php echo htmlspecialchars($treatmentName); ?></strong>
            </div>

            <!-- Pesan Error/Sukses -->
            <?php if (isset($error_message) && $error_message !== ''): ?>
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md text-sm">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php elseif (isset($success_message) && $success_message !== ''): ?>
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 flex items-center rounded-md">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <!-- Form Booking -->
            <form method="POST" class="space-y-6" id="bookingForm">
                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-800 placeholder-gray-400 transition duration-200"
                        placeholder="Masukkan nama Anda">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-800 placeholder-gray-400 transition duration-200"
                        placeholder="Masukkan email Anda">
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="date" id="date" min="<?= date('Y-m-d') ?>" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-800 transition duration-200">
                </div>

                <!-- Waktu -->
                <div>
                    <label for="time" class="block text-sm font-medium text-gray-700">Waktu</label>
                    <select name="time" id="time" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-800 transition duration-200">
                        <option selected disabled>-- Pilih Tanggal Dulu --</option>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div>
                    <button type="submit" id="submitButton"
                        class="w-full bg-gradient-to-r from-blue-400 to-blue-600 text-white py-3 px-4 rounded-xl shadow-md hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-transform transform hover:scale-105 duration-300"
                        disabled>
                        Pesan Treatment Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dateInput = document.getElementById("date");
            const timeSelect = document.getElementById("time");
            const submitButton = document.getElementById("submitButton");

            function updateAvailableTimes() {
                const selectedDate = dateInput.value;

                if (!selectedDate) {
                    timeSelect.innerHTML = '<option selected disabled>-- Pilih Tanggal Dulu --</option>';
                    submitButton.disabled = true;
                    return;
                }

                // Melakukan fetch untuk mendapatkan waktu yang tersedia dan yang sudah dibooked
                fetch(`get_available_times.php?date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        timeSelect.innerHTML = "";

                        // Tambahkan opsi default
                        const defaultOpt = document.createElement("option");
                        defaultOpt.textContent = "-- Pilih Jam --";
                        defaultOpt.disabled = true;
                        defaultOpt.selected = true;
                        timeSelect.appendChild(defaultOpt);

                        // Tampilkan semua slot waktu (tersedia dan booked)
                        const allTimes = data.all_times;
                        const bookedTimes = data.booked_times;

                        let hasAvailableTimes = false;
                        allTimes.forEach(time => {
                            const opt = document.createElement("option");
                            opt.value = time;
                            if (bookedTimes.includes(time + ":00")) {
                                opt.textContent = `${time} (Booked)`;
                                opt.disabled = true;
                                opt.classList.add("text-red-500");
                            } else {
                                opt.textContent = time;
                                hasAvailableTimes = true;
                            }
                            timeSelect.appendChild(opt);
                        });

                        // Aktifkan atau nonaktifkan tombol submit berdasarkan ketersediaan waktu
                        submitButton.disabled = !hasAvailableTimes;
                    })
                    .catch(error => {
                        console.log("Terjadi kesalahan saat mengambil waktu: ", error);
                        timeSelect.innerHTML = '<option selected disabled>❌ Terjadi kesalahan, coba lagi.</option>';
                        submitButton.disabled = true;
                    });
            }

            dateInput.addEventListener("change", updateAvailableTimes);

            // Nonaktifkan tombol submit secara default
            submitButton.disabled = true;
        });
    </script>

</body>

</html>