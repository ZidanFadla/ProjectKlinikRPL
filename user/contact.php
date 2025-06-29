<?php
include('../auth.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
</head>
<body>
<?php include "../layout/navbar.php" ?>
    <h1>Welcome to Contact Page, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    // isi ini 
    <a href="../logout.php" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">
        Logout
    </a>
</body>
</html>
