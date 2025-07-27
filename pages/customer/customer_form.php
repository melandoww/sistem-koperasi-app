<?php
// koperasi-app/pages/customer_form.php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../app/models/customer.php';

$database = new Database();
$db = $database->connect();
$customer_model = new Customer($db);

$page_title_action = "Tambah";
$id_customer = '';
$nama_customer = '';
$alamat = '';
$telp = '';
$fax = '';
$email = '';

$message = '';
$message_type = '';

// Cek apakah mode edit
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $page_title_action = "Edit";
    $id_customer = $_GET['id'];
    $customer_data_edit = $customer_model->getById($id_customer);

    if ($customer_data_edit) {
        $nama_customer = $customer_data_edit['nama_customer'];
        $alamat = $customer_data_edit['alamat'];
        $telp = $customer_data_edit['telp'];
        $fax = $customer_data_edit['fax'];
        $email = $customer_data_edit['email'];
    } else {
        $_SESSION['message'] = "Customer tidak ditemukan!";
        $_SESSION['message_type'] = "danger";
        header('Location: ?page=customer');
        exit();
    }
}

// Tangani POST request (untuk tambah atau edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_model->nama_customer = $_POST['nama_customer'];
    $customer_model->alamat = $_POST['alamat'];
    $customer_model->telp = $_POST['telp'];
    $customer_model->fax = $_POST['fax'];
    $customer_model->email = $_POST['email'];

    $message = '';
    $message_type = '';

    if (isset($_POST['id_customer']) && !empty($_POST['id_customer'])) {
        // Mode Edit
        $customer_model->id_customer = $_POST['id_customer'];
        if ($customer_model->update()) {
            $_SESSION['message'] = "Customer berhasil diupdate!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=customer');
            exit();
        } else {
            $message = "Gagal mengupdate customer.";
            $message_type = "danger";
        }
    } else {
        // Mode Tambah
        if ($customer_model->create()) {
            $_SESSION['message'] = "Customer berhasil ditambahkan!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=customer');
            exit();
        } else {
            $message = "Gagal menambahkan customer.";
            $message_type = "danger";
        }
    }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $page_title_action; ?> Customer</h1>
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
        <h6 class="m-0 font-weight-bold text-primary">Form Customer</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <?php if ($id_customer): ?>
                <input type="hidden" name="id_customer" value="<?php echo htmlspecialchars($id_customer); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="namaCustomer">Nama Customer:</label>
                <input type="text" class="form-control" id="namaCustomer" name="nama_customer" value="<?php echo htmlspecialchars($nama_customer); ?>" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($alamat); ?></textarea>
            </div>

            <div class="form-group">
                <label for="telp">Telepon:</label>
                <input type="text" class="form-control" id="telp" name="telp" value="<?php echo htmlspecialchars($telp); ?>">
            </div>

            <div class="form-group">
                <label for="fax">Fax:</label>
                <input type="text" class="form-control" id="fax" name="fax" value="<?php echo htmlspecialchars($fax); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="?page=customer" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>