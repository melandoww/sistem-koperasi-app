<?php
// koperasi-app/pages/petugas.php

// Pastikan koneksi database dan model User sudah di-include dari index.php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../app/models/user.php';


$database = new Database();
$db = $database->connect();
$user_model = new User($db);

// Ambil semua data petugas
$stmt = $user_model->getAll();
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
    <h1 class="h3 mb-0 text-gray-800">Data Petugas</h1>
    <a href="?page=petugas&action=add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Tambah Petugas</a>
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
        <h6 class="m-0 font-weight-bold text-primary">Daftar Petugas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Petugas</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($num > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row); // Mengambil variabel seperti $id_user, $nama_user, $username, $level_name
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($nama_user); ?></td>
                                <td><?php echo htmlspecialchars($username); ?></td>
                                <td><?php echo htmlspecialchars($level_name); ?></td>
                                <td>
                                    <a href="?page=petugas&action=edit&id=<?php echo $id_user; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?page=petugas&action=delete&id=<?php echo $id_user; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Tidak ada data petugas.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>