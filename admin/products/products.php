<!-- Konten Manajemen Produk -->
<div class="flex justify-between items-center mb-6">
    <a href="products/add_product.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
        Tambah Produk
    </a>
</div>

<!-- Tabel Produk -->
<div class="overflow-x-auto shadow-lg rounded-lg border border-blue-300 bg-white">
    <table class="table-auto w-full text-left border-collapse">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="py-3 px-4">No</th>
                <th class="py-3 px-4">Gambar</th>
                <th class="py-3 px-4">Nama Produk</th>
                <th class="py-3 px-4">Kategori</th>
                <th class="py-3 px-4">Harga</th>
                <th class="py-3 px-4">Stok</th>
                <th class="py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php
            $result = $conn->query("SELECT * FROM products");
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr class='border-t'>
                    <td class='py-3 px-4'>{$no}</td>
                    <td class='py-3 px-4'>
                        <img src='../uploads/{$row['image']}' alt='{$row['name']}' class='w-16 h-16 object-cover rounded'>
                    </td>
                    <td class='py-3 px-4'>{$row['name']}</td>
                    <td class='py-3 px-4'>{$row['category']}</td>
                    <td class='py-3 px-4'>Rp. " . number_format($row['price'], 0, ',', '.') . "</td>
                    <td class='py-3 px-4'>{$row['stock']}</td>
                    <td class='py-3 px-4'>
                        <a href='products/edit_product.php?id={$row['id']}' class='inline-block px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600'>Edit</a>
                        <a href='#' 
                            class='delete-btn inline-block px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 ml-2' 
                            id={$row['id']}>Hapus</a>
                    </td>
                </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();

                const productId = button.getAttribute('id');
                const confirmDelete = confirm('Apakah Anda yakin ingin menghapus produk ini?');

                if (confirmDelete) {
                    // Redirect ke halaman delete_product.php
                    window.location.href = `products/delete_product.php?id=${productId}`;
                }
            });
        });
    });
</script>
