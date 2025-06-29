<!-- Konten Manajemen Promo -->

<div class="flex justify-between items-center mb-6">
    <a href="promo/add_promo.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
        Tambah Promo
    </a>
</div>

<!-- Tabel Promo -->
<div class="overflow-x-auto shadow-lg rounded-lg border border-blue-500 bg-white">
    <table class="table-auto w-full text-left border-collapse">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="py-3 px-4">No</th>
                <th class="py-3 px-4">Gambar</th>
                <th class="py-3 px-4">Judul Promo</th>
                <th class="py-3 px-4">Kategori</th>
                <th class="py-3 px-4">Deskripsi</th>
                <th class="py-3 px-4">Diskon</th>
                <th class="py-3 px-4">Berlaku Hingga</th>
                <th class="py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php
            $result = $conn->query("SELECT * FROM promos");
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                // Validasi untuk 'valid_until'
                $valid_until = !empty($row['valid_until']) ? date("d M Y", strtotime($row['valid_until'])) : "Tidak Ada Tanggal";
                
                // Validasi untuk 'discount'
                $discount = !empty($row['discount']) ? $row['discount'] . "%" : "-";

                echo "<tr class='border-t'>
                    <td class='py-3 px-4'>{$no}</td>
                    <td class='py-3 px-4'>
                        <img src='../uploads/{$row['image']}' alt='{$row['title']}' class='w-16 h-16 object-cover rounded'>
                    </td>
                    <td class='py-3 px-4'>{$row['title']}</td>
                    <td class='py-3 px-4'>{$row['category']}</td>
                    <td class='py-3 px-4'>{$row['description']}</td>
                    <td class='py-3 px-4'>{$discount}</td>
                    <td class='py-3 px-4'>{$valid_until}</td>
                    <td class='py-3 px-4'>
                        <a href='promo/edit_promo.php?id={$row['id']}' class='inline-block px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600'>Edit</a>
                        <a href='#' 
                            class='promo-delete-btn inline-block px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 ml-2' 
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
        const deleteButtons = document.querySelectorAll('.promo-delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();

                const promoId = button.getAttribute('id');
                const confirmDelete = confirm('Apakah Anda yakin ingin menghapus promo ini?');

                if (confirmDelete) {
                    // Redirect ke halaman delete_promo.php
                    window.location.href = `promo/delete_promo.php?id=${promoId}`;
                }
            });
        });
    });
</script>