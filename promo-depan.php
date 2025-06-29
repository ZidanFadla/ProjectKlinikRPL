<?php
include 'assets/db/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <!-- BG -->
    <div class="bg-[url('uploads/rumah_sakit.jpg')] bg-cover bg-contain">

        <!-- Navbar -->
        <?php include "layout/navbar2.php" ?>

        <!-- Promo Section -->
        <div>
            <section class="py-10 px-6">
                <div class="max-w-7xl mx-auto">
                    <h2 class="relative text-3xl font-extrabold text-center text-gray-800 mb-8 p-4 border-4 border border-blue-500 rounded-xl shadow-md bg-gradient-to-r from-blue-200 via-white to-blue-100">
                        <span class="bg-gradient-to-r from-blue-400 to-blue-600 text-transparent bg-clip-text">
                            Penawaran Spesial
                        </span>
                    </h2>

                    <!-- Grid for Promotions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                        // Query untuk mengambil data dari tabel promosi
                        $query = "SELECT * FROM promos WHERE valid_until >= CURDATE()";
                        $result = $conn->query($query);

                        // Loop untuk menampilkan setiap promosi
                        while ($promo = $result->fetch_assoc()):
                            // Proses kategori
                            $category = htmlspecialchars($promo['category']);
                            $shortCategory = '';
                            $buttonLink = '#';

                            if (str_starts_with($category, 'Treatment-')) {
                                $shortCategory = str_replace('Treatment-', '', $category);
                                $buttonLink = 'treatment.php';
                            } elseif (str_starts_with($category, 'Product-')) {
                                $shortCategory = str_replace('Product-', '', $category);
                                $buttonLink = 'product.php';
                            }
                        ?>
                            <!-- Promo Card -->
                            <div class="bg-white shadow-md rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                                <div class="relative">
                                    <img src="uploads/<?= htmlspecialchars($promo['image']) ?>" alt="<?= htmlspecialchars($promo['title']) ?>" class="w-full h-48 object-cover">
                                    <span class="absolute top-4 left-4 bg-gradient-to-r from-blue-400 to-blue-600 text-white py-1 px-3 rounded-full text-sm">
                                        Berlaku hingga <?= date('d M Y', strtotime($promo['valid_until'])) ?>
                                    </span>
                                </div>
                                <div class="p-6 flex flex-col justify-between bg-gradient-to-r from-blue-100 to-blue-200 min-h-[300px]">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($promo['title']) ?></h3>
                                        <p class="text-lg font-semibold">Kategori: <?= htmlspecialchars($shortCategory) ?></p>  
                                        <p class="text-gray-600 text-sm mt-2"><?= htmlspecialchars($promo['description']) ?></p>
                                        <p class="text-blue-500 font-semibold mt-2">Diskon: <?= htmlspecialchars($promo['discount']) ?>%</p>
                                    </div>
                                    <a href="login.php" class="w-full bg-gradient-to-r from-blue-400 to-blue-600 text-white py-2 px-4 rounded-lg transition-transform transform hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2">
                                        Lihat Promo
                                    </a>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        </div>
        <!-- Footer -->
        <?php include "layout/footer.php" ?>

</body>

</html>