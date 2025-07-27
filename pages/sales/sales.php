<?php
// koperasi-app/pages/sales.php

// Pastikan koneksi database dan model Sales sudah di-include dari index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Sales.php';
// Mungkin dibutuhkan untuk dropdown nanti, tapi tidak di sini
require_once __DIR__ . '/../../app/models/customer.php'; 
$database = new Database();
$db = $database->connect();
$sales_model = new Sales($db);

// Ambil semua data penjualan
$stmt = $sales_model->getAll();
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
    <h1 class="h3 mb-0 text-gray-800">Data Sales</h1>
    <a href="?page=sales&action=add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Tambah Sales</a>
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
        <h6 class="m-0 font-weight-bold text-primary">Daftar Sales</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Sales</th>
                        <th>Tanggal Sales</th>
                        <th>Customer</th>
                        <th>DO Number</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($num > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row); // Mengambil variabel seperti $id_sales, $tgl_sales, $nama_customer, dll.
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($id_sales); ?></td>
                                <td><?php echo htmlspecialchars($tgl_sales); ?></td>
                                <td><?php echo htmlspecialchars($nama_customer); ?></td> <td><?php echo htmlspecialchars($do_number); ?></td>
                                <td><?php echo htmlspecialchars($status); ?></td>
                                <td>
                                    <a href="?page=sales&action=edit&id=<?php echo $id_sales; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?page=sales&action=delete&id=<?php echo $id_sales; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus sales ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">Tidak ada data sales.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>