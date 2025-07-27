<?php
// koperasi-app/pages/petugas_form.php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../app/models/user.php';

$database = new Database();
$db = $database->connect();
$user_model = new User($db);

$page_title_action = "Tambah";
$id_user = '';
$nama_user = '';
$username = '';
$level_id = ''; // Default level ID

$message = '';
$message_type = '';

// Untuk dropdown level
$levels = $user_model->getAllLevels();

// Cek apakah mode edit
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $page_title_action = "Edit";
    $id_user = $_GET['id'];
    $user_data_edit = $user_model->getById($id_user);

    if ($user_data_edit) {
        $nama_user = $user_data_edit['nama_user'];
        $username = $user_data_edit['username'];
        $level_id = $user_data_edit['level_id']; // ID Level yang sudah ada
    } else {
        $_SESSION['message'] = "Petugas tidak ditemukan!";
        $_SESSION['message_type'] = "danger";
        header('Location: ?page=petugas');
        exit();
    }
}

// Tangani POST request (untuk tambah atau edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_model->nama_user = $_POST['nama_user'];
    $user_model->username = $_POST['username'];
    $user_model->level = $_POST['level_id']; // ID Level yang dipilih

    // Jika ada password baru, atau jika ini mode tambah
    if (!empty($_POST['password'])) {
        $user_model->password = $_POST['password'];
    }

    $message = '';
    $message_type = '';

    if (isset($_POST['id_user']) && !empty($_POST['id_user'])) {
        // Mode Edit
        $user_model->id_user = $_POST['id_user'];
        if ($user_model->update()) {
            $_SESSION['message'] = "Petugas berhasil diupdate!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=petugas');
            exit();
        } else {
            $message = "Gagal mengupdate petugas.";
            $message_type = "danger";
        }
    } else {
        // Mode Tambah
        // Validasi password confirm jika ada
        if (empty($_POST['password']) || $_POST['password'] != $_POST['confirm_password']) {
             $message = "Password harus diisi dan cocok dengan konfirmasi password!";
             $message_type = "danger";
        } else {
            if ($user_model->create()) {
                $_SESSION['message'] = "Petugas berhasil ditambahkan!";
                $_SESSION['message_type'] = "success";
                header('Location: ?page=petugas');
                exit();
            } else {
                $message = "Gagal menambahkan petugas. Username mungkin sudah ada.";
                $message_type = "danger";
            }
        }
    }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $page_title_action; ?> Petugas</h1>
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
        <h6 class="m-0 font-weight-bold text-primary">Form Petugas</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <?php if ($id_user): ?>
                <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="namaUser">Nama Lengkap:</label>
                <input type="text" class="form-control" id="namaUser" name="nama_user" value="<?php echo htmlspecialchars($nama_user); ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password (kosongkan jika tidak ingin mengubah):</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Isi jika ingin mengubah password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password baru">
            </div>

            <div class="form-group">
                <label for="level">Level:</label>
                <select class="form-control" id="level" name="level_id" required>
                    <option value="">-- Pilih Level --</option>
                    <?php foreach ($levels as $level_opt): ?>
                        <option value="<?php echo htmlspecialchars($level_opt['id_level']); ?>"
                            <?php echo ($level_opt['id_level'] == $level_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($level_opt['level']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="?page=petugas" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>