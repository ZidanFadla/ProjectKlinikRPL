<!-- Konten Manajemen Treatment -->
<div class="flex justify-between items-center mb-6">
    <a href="treatments/add_treatment.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
        Tambah Treatment
    </a>
</div>

<!-- Tabel Treatment -->
<div class="overflow-x-auto shadow-lg rounded-lg border border-blue-300 bg-white">
    <table class="table-auto w-full text-left border-collapse">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="py-3 px-4">No</th>
                <th class="py-3 px-4">Gambar</th>
                <th class="py-3 px-4">Nama Treatment</th>
                <th class="py-3 px-4">Kategory</th>
                <th class="py-3 px-4">Deskripsi</th>
                <th class="py-3 px-4">Harga</th>
                <th class="py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php
            $result = $conn->query("SELECT * FROM treatments");
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr class='border-t'>
                    <td class='py-3 px-4'>{$no}</td>
                    <td class='py-3 px-4'>
                        <img src='../uploads/{$row['image']}' alt='{$row['name']}' class='w-16 h-16 object-cover rounded'>
                    </td>
                    <td class='py-3 px-4'>{$row['name']}</td>
                    <td class='py-3 px-4'>{$row['category']}</td>
                    <td class='py-3 px-4'>" . substr($row['description'], 0, 50) . "</td>
                    <td class='py-3 px-4'>Rp. " . number_format($row['price'], 0, ',', '.') . "</td>
                    <td class='py-3 px-4'>
                        <a href='treatments/edit_treatment.php?id={$row['id']}' class='inline-block px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600'>Edit</a>
                        <a href='#' 
                            class='delete-treatment-btn inline-block px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 ml-2' 
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
        const deleteButtons = document.querySelectorAll('.delete-treatment-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();

                const treatmentId = button.getAttribute('id');
                const confirmDelete = confirm('Apakah Anda yakin ingin menghapus treatment ini?');

                if (confirmDelete) {
                    // Redirect ke halaman delete_treatment.php
                    window.location.href = `treatments/delete_treatment.php?id=${treatmentId}`;
                }
            });
        });
    });
</script>
