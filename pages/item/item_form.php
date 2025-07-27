<?php
// session_start(); // Tambahkan ini di awal untuk menggunakan session
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Item.php';

$database = new Database(); // Perhatikan huruf besar 'D' pada Database()
$db = $database->connect();
$item_model = new Item($db);

$page_title_action = "Tambah";
$id_item = '';
$nama_item = '';
$uom = '';
$harga_beli = '';
$harga_jual = '';

// Inisialisasi pesan
$message = '';
$message_type = '';

// Cek apakah mode edit
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $page_title_action = "Edit";
    $id_item = $_GET['id'];
    $item_data_edit = $item_model->getById($id_item);

    if ($item_data_edit) {
        $nama_item = $item_data_edit['nama_item'];
        $uom = $item_data_edit['uom'];
        $harga_beli = $item_data_edit['harga_beli'];
        $harga_jual = $item_data_edit['harga_jual'];
    } else {
        $_SESSION['message'] = "Item tidak ditemukan!";
        $_SESSION['message_type'] = "danger";
        header('Location: ?page=item');
        exit();
    }
}

// Tangani POST request (untuk tambah atau edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_model->nama_item = $_POST['nama_item'];
    $item_model->uom = $_POST['uom'];
    // Pastikan harga_beli dan harga_jual adalah numerik
    $item_model->harga_beli = str_replace(['.', ','], '', $_POST['harga_beli']); // Hapus format angka
    $item_model->harga_jual = str_replace(['.', ','], '', $_POST['harga_jual']); // Hapus format angka

    if (isset($_POST['id_item']) && !empty($_POST['id_item'])) {
        // Mode Edit
        $item_model->id_item = $_POST['id_item'];
        if ($item_model->update()) {
            $_SESSION['message'] = "Item berhasil diupdate!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=item');
            exit();
        } else {
            $message = "Gagal mengupdate item.";
            $message_type = "danger";
        }
    } else {
        // Mode Tambah
        if ($item_model->create()) {
            $_SESSION['message'] = "Item berhasil ditambahkan!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=item');
            exit();
        } else {
            $message = "Gagal menambahkan item.";
            $message_type = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title_action; ?> Item</title>
    <!-- Tambahkan CSS yang diperlukan -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?php echo $page_title_action; ?> Item</h1>
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
                <h6 class="m-0 font-weight-bold text-primary">Form Item Koperasi</h6>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <?php if ($id_item): ?>
                        <input type="hidden" name="id_item" value="<?php echo htmlspecialchars($id_item); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="namaItem">Nama Item:</label>
                        <input type="text" class="form-control" id="namaItem" name="nama_item" 
                               value="<?php echo htmlspecialchars($nama_item); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="uom">UOM (Unit of Measure):</label>
                        <input type="text" class="form-control" id="uom" name="uom" 
                               value="<?php echo htmlspecialchars($uom); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="hargaBeli">Harga Beli:</label>
                        <input type="text" class="form-control" id="hargaBeli" name="harga_beli" 
                               value="<?php echo $harga_beli ? htmlspecialchars(number_format($harga_beli, 0, ',', '.')) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="hargaJual">Harga Jual:</label>
                        <input type="text" class="form-control" id="hargaJual" name="harga_jual" 
                               value="<?php echo $harga_jual ? htmlspecialchars(number_format($harga_jual, 0, ',', '.')) : ''; ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=item" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambahkan JavaScript yang diperlukan -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
</body>
</html>