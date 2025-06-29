<?php
include('../auth.php');
include '../assets/db/database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treatment</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- BG -->
    <div class="bg-[url('../uploads/rumah_sakit.jpg')] bg-cover bg-contain">

        <!-- NAVBAR -->
        <?php include "../layout/navbar.php" ?>

        <!-- Treatment Section -->
        <div>
            <section class="py-12 px-6">
                <div class="max-w-7xl mx-auto">
                    <h2 class="relative text-3xl font-extrabold text-center text-gray-800 mb-8 p-4 border-4 border border-blue-500 rounded-xl shadow-md bg-gradient-to-r from-blue-200 via-white to-blue-100">
                        <span class="bg-gradient-to-r from-blue-400 to-blue-600 text-transparent bg-clip-text">
                            Daftar Treatment
                        </span>
                    </h2>

                    <!-- Grid for Treatments -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        $query = "SELECT 
                                        treatments.id, 
                                        treatments.name, 
                                        treatments.category, 
                                        treatments.description, 
                                        treatments.price, 
                                        treatments.image, 
                                        promos.discount, 
                                        promos.valid_until
                                    FROM 
                                        treatments
                                    LEFT JOIN 
                                        promos 
                                    ON 
                                        treatments.category = promos.category COLLATE utf8mb4_general_ci 
                                    WHERE 
                                        promos.valid_until >= CURDATE() 
                                        OR promos.category IS NULL
                                    ORDER BY 
                                        CASE 
                                            WHEN promos.discount IS NOT NULL THEN 1 
                                            ELSE 0 
                                        END DESC, 
                                        promos.discount DESC";

                        $result = $conn->query($query);

                        while ($treatment = $result->fetch_assoc()):
                            // Harga diskon jika promo berlaku
                            $isPromoValid = !empty($treatment['discount']) && strtotime($treatment['valid_until']) >= time();
                            $discountedPrice = $isPromoValid
                                ? $treatment['price'] * (1 - ($treatment['discount'] / 100))
                                : $treatment['price'];
                        ?>
                            <!-- Treatment Card -->
                            <div class="bg-white shadow-md rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                                <div class="relative">
                                    <img src="../uploads/<?= htmlspecialchars($treatment['image']) ?>"
                                        alt="<?= htmlspecialchars($treatment['name']) ?>"
                                        class="w-full h-48 object-cover">
                                    <?php if ($isPromoValid): ?>
                                        <span class="absolute top-4 left-4 bg-gradient-to-r from-blue-400 to-blue-600 text-white py-1 px-3 rounded-full text-sm">
                                            Diskon <?= $treatment['discount'] ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-6 flex flex-col bg-gradient-to-r from-blue-100 to-blue-200 min-h-[350px]">
                                    <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($treatment['name']) ?></h3>
                                    <p class="text-lg font-bold mb-1">Kategori:
                                        <?= htmlspecialchars(str_replace('Treatment-', '', $treatment['category'])) ?>
                                    </p>
                                    <p class="text-gray-600 text-sm flex-grow"><?= htmlspecialchars($treatment['description']) ?></p>
                                    <?php if ($isPromoValid): ?>
                                        <p class="text-gray-400 line-through text-sm mt-2">Rp <?= number_format($treatment['price'], 0, ',', '.') ?></p>
                                    <?php endif; ?>
                                    <p class="text-blue-500 text-lg font-bold mt-1">Rp <?= number_format($discountedPrice, 0, ',', '.') ?></p>
                                    <a href="booking.php?treatments_id=<?= $treatment['id'] ?>" class="block mt-4 w-full">
                                        <button class="w-full bg-gradient-to-r from-blue-400 to-blue-600 text-white py-2 px-4 rounded-lg transition-transform transform hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-calendar-check"></i> Booking Sekarang
                                        </button>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <?php include "../layout/footer.php" ?>
    </div>

</body>

</html>