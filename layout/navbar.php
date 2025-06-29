<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>



<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playwrite+AU+SA:wght@100..400&display=swap" rel="stylesheet">

<style>
  .font-playwrite {
    font-family: "Playwrite AU SA", serif;
  }
</style>


<nav class="bg-gradient-to-r from-blue-400 to-blue-600 shadow-lg text-white">
  <div class="mx-auto px-8 py-5 flex justify-between items-center">

    <!-- Logo -->
    <a href="user-home.php" class="text-xl md:text-2xl font-bold font-playwrite text-white hover:text-blue-200">
      Azra Beauty Clinic
    </a>

    <!-- Menu -->
    <div class="flex items-center space-x-4 md:space-x-5">
      <ul class="hidden md:flex space-x-4 md:space-x-6">
        <li>
          <a href="user-home.php"
            class="rounded-md px-3 py-2 text-lg font-medium hover:bg-blue-500 hover:bg-opacity-80 <?= $current_page == 'user-home.php' ? 'bg-blue-700' : '' ?>">
            Home
          </a>
        </li>
        <li>
          <a href="promo.php"
            class="rounded-md px-3 py-2 text-lg font-medium hover:bg-blue-500 hover:bg-opacity-80 <?= $current_page == 'promo.php' ? 'bg-blue-700' : '' ?>">
            Promo
          </a>
        </li>
        <li>
          <a href="treatment.php"
            class="rounded-md px-3 py-2 text-lg font-medium hover:bg-blue-500 hover:bg-opacity-80 <?= $current_page == 'treatment.php' ? 'bg-blue-700' : '' ?>">
            Treatment
          </a>
        </li>
        <li>
          <a href="product.php"
            class="rounded-md px-3 py-2 text-lg font-medium hover:bg-blue-500 hover:bg-opacity-80 <?= $current_page == 'product.php' ? 'bg-blue-700' : '' ?>">
            Product
          </a>
        </li>
        <li>
          <a href="cart.php"
            class="rounded-md px-3 py-2 text-lg font-medium hover:bg-blue-500 hover:bg-opacity-80 <?= $current_page == 'cart.php' ? 'bg-blue-700' : '' ?>">
            Keranjang
          </a>
        </li>
        <li>
          <a href="../logout.php" class="rounded-md px-3 py-2 text-lg font-medium hover:bg-blue-500 hover:bg-opacity-80">
            Logout
          </a>
        </li>
      </ul>
    </div>

  </div>
</nav>