<?php
include('assets/db/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $role = 'user'; // Role default untuk register

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Username sudah ada
        $error_message = "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        // Tambahkan user ke database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Azra Beauty Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 min-h-screen flex items-center justify-center">

    <!-- Register Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 md:p-8 w-full max-w-md">
        <h1 class="text-2xl md:text-3xl font-bold text-blue-500 text-center mb-6">Register</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-300">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-300">
            </div>
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold text-lg shadow-lg">
                Register
            </button>
        </form>
        <div class="text-center mt-4 text-sm text-gray-600">
            Sudah punya akun? 
            <a href="login.php" class="text-blue-500 hover:underline">Login di sini</a>
        </div>
    </div>
</body>
</html>
