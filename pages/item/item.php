<?php
// koperasi-app/pages/item.php

// Pastikan koneksi database dan model Item sudah di-include dari index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Item.php';

$database = new Database();
$db = $database->connect();
$item_model = new Item($db);

// Ambil semua data item
$stmt = $item_model->getAll();
$num = $stmt->rowCount(); // Jumlah baris

// Untuk menampilkan pesan sukses/gagal dari operasi CRUD
$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
    unset($_SESSION['message_type']);
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Item</h1>
    <a href="?page=item&action=add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Tambah Item</a>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Item</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Item</th>
                        <th>UOM</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($num > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row); // Mengambil variabel seperti $id_item, $nama_item, dll.
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($nama_item); ?></td>
                                <td><?php echo htmlspecialchars($uom); ?></td>
                                <td><?php echo number_format($harga_beli, 0, ',', '.'); ?></td>
                                <td><?php echo number_format($harga_jual, 0, ',', '.'); ?></td>
                                <td>
                                    <a href="?page=item&action=edit&id=<?php echo $id_item; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?page=item&action=delete&id=<?php echo $id_item; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Tidak ada data item.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>