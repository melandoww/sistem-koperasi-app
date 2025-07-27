<?php
// koperasi-app/pages/transaction.php

// Pastikan koneksi database dan model sudah di-include dari index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Transaction.php';
require_once __DIR__ . '/../../app/models/Sales.php'; // Digunakan untuk daftar Sales ID di form
require_once __DIR__ . '/../../app/models/Item.php';   // Digunakan untuk daftar Item di form

$database = new Database();
$db = $database->connect();
$transaction_model = new Transaction($db);

// Ambil semua data transaksi
$stmt = $transaction_model->getAll();
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
    <h1 class="h3 mb-0 text-gray-800">Data Transaksi Detail</h1>
    <a href="?page=transaction&action=add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Tambah Transaksi Detail</a>
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
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Detail</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Sales ID (DO Number)</th>
                        <th>Tanggal Sales</th>
                        <th>Nama Item</th>
                        <th>UOM</th>
                        <th>Quantity</th>
                        <th>Harga per Unit</th>
                        <th>Amount</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($num > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row); // Mengambil variabel seperti $id_transaction, $id_sales, $nama_item, dll.
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($id_transaction); ?></td>
                                <td><?php echo htmlspecialchars($id_sales) . " (" . htmlspecialchars($do_number) . ")"; ?></td>
                                <td><?php echo htmlspecialchars($tgl_sales); ?></td>
                                <td><?php echo htmlspecialchars($nama_item); ?></td>
                                <td><?php echo htmlspecialchars($uom); ?></td>
                                <td><?php echo htmlspecialchars($quantity); ?></td>
                                <td><?php echo number_format($price, 0, ',', '.'); ?></td>
                                <td><?php echo number_format($amount, 0, ',', '.'); ?></td>
                                <td>
                                    <a href="?page=transaction&action=edit&id=<?php echo $id_transaction; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?page=transaction&action=delete&id=<?php echo $id_transaction; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus detail transaksi ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="10" class="text-center">Tidak ada data transaksi.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>