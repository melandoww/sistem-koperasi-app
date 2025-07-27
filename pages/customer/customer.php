<?php
// koperasi-app/pages/customer.php

// Pastikan koneksi database dan model Customer sudah di-include dari index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/customer.php';

$database = new database();
$db = $database->connect();
$customer_model = new Customer($db);

// Ambil semua data customer
$stmt = $customer_model->getAll();
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
    <h1 class="h3 mb-0 text-gray-800">Data Customer</h1>
    <a href="?page=customer&action=add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Tambah Customer</a>
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
        <h6 class="m-0 font-weight-bold text-primary">Daftar Customer</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Customer</th>
                        <th>Alamat</th>
                        <th>Telp</th>
                        <th>Fax</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($num > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row); // Mengambil variabel seperti $id_customer, $nama_customer, dll.
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($nama_customer); ?></td>
                                <td><?php echo htmlspecialchars($alamat); ?></td>
                                <td><?php echo htmlspecialchars($telp); ?></td>
                                <td><?php echo htmlspecialchars($fax); ?></td>
                                <td><?php echo htmlspecialchars($email); ?></td>
                                <td>
                                    <a href="?page=customer&action=edit&id=<?php echo $id_customer; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?page=customer&action=delete&id=<?php echo $id_customer; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">Tidak ada data customer.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>