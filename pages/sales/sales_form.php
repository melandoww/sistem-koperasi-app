<?php
// koperasi-app/pages/sales_form.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Sales.php';
require_once __DIR__ . '/../../app/models/Customer.php'; // Perlu model Customer untuk dropdown

$database = new database();
$db = $database->connect();
$sales_model = new Sales($db);
$customer_model = new customer($db); // Inisialisasi model Customer

$page_title_action = "Tambah";
$id_sales = '';
$tgl_sales = date('Y-m-d'); // Default tanggal hari ini
$id_customer = '';
$do_number = '';
$status = 'Open'; // Default status

// Ambil semua customer untuk dropdown
$stmt_customers = $customer_model->getAll();
$customers = $stmt_customers->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi pesan
$message = '';
$message_type = '';

// Cek apakah mode edit
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $page_title_action = "Edit";
    $id_sales = $_GET['id'];
    $sales_data_edit = $sales_model->getById($id_sales);

    if ($sales_data_edit) {
        $tgl_sales = $sales_data_edit['tgl_sales'];
        $id_customer = $sales_data_edit['id_customer'];
        $do_number = $sales_data_edit['do_number'];
        $status = $sales_data_edit['status'];
    } else {
        $_SESSION['message'] = "Sales tidak ditemukan!";
        $_SESSION['message_type'] = "danger";
        header('Location: ?page=sales');
        exit();
    }
}

// Tangani POST request (untuk tambah atau edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sales_model->tgl_sales = $_POST['tgl_sales'];
    $sales_model->id_customer = $_POST['id_customer'];
    $sales_model->do_number = $_POST['do_number'];
    $sales_model->status = $_POST['status'];

    $message = '';
    $message_type = '';

    if (isset($_POST['id_sales']) && !empty($_POST['id_sales'])) {
        // Mode Edit
        $sales_model->id_sales = $_POST['id_sales'];
        if ($sales_model->update()) {
            $_SESSION['message'] = "Sales berhasil diupdate!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=sales');
            exit();
        } else {
            $message = "Gagal mengupdate sales.";
            $message_type = "danger";
        }
    } else {
        // Mode Tambah
        if ($sales_model->create()) {
            $_SESSION['message'] = "Sales berhasil ditambahkan!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=sales');
            exit();
        } else {
            $message = "Gagal menambahkan sales.";
            $message_type = "danger";
        }
    }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $page_title_action; ?> Sales</h1>
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
        <h6 class="m-0 font-weight-bold text-primary">Form Sales</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <?php if ($id_sales): ?>
                <input type="hidden" name="id_sales" value="<?php echo htmlspecialchars($id_sales); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="tglSales">Tanggal Sales:</label>
                <input type="date" class="form-control" id="tglSales" name="tgl_sales" value="<?php echo htmlspecialchars($tgl_sales); ?>" required>
            </div>

            <div class="form-group">
                <label for="idCustomer">Customer:</label>
                <select class="form-control" id="idCustomer" name="id_customer" required>
                    <option value="">-- Pilih Customer --</option>
                    <?php foreach ($customers as $customer_opt): ?>
                        <option value="<?php echo htmlspecialchars($customer_opt['id_customer']); ?>"
                            <?php echo ($customer_opt['id_customer'] == $id_customer) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($customer_opt['nama_customer']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="doNumber">DO Number (Nomor Pengiriman):</label>
                <input type="text" class="form-control" id="doNumber" name="do_number" value="<?php echo htmlspecialchars($do_number); ?>">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Open" <?php echo ($status == 'Open') ? 'selected' : ''; ?>>Open</option>
                    <option value="Closed" <?php echo ($status == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                    <option value="Canceled" <?php echo ($status == 'Canceled') ? 'selected' : ''; ?>>Canceled</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="?page=sales" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>