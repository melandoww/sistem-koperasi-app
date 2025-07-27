<?php
// koperasi-app/index.php

session_start(); // Penting: Mulai session di awal setiap file yang butuh session

// (Opsional) Definisikan base URL
// Ini akan menjadi URL dasar aplikasi Anda, misal: http://localhost/koperasi-app/
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: auth/login.php');
    exit();
}

// Ambil data user dari session
$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama_user']; // <--- Tambahkan baris ini
// $level = $_SESSION['level'];       // <--- Tambahkan baris ini

// (Opsional) Definisikan base URL
// Ini akan menjadi URL dasar aplikasi Anda, misal: http://localhost/koperasi-app/
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

// Set variabel untuk active menu sidebar
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$current_page = $page;
$page_title = 'Koperasi App - Dashboard'; // Default title, akan diubah di switch

ob_start(); // Mulai output buffering

switch ($page) {
    case 'dashboard':
        include 'pages/dashboard.php';
        $page_title = 'Koperasi App - Dashboard';
        break;
    case 'customer': // <<< PASTIKAN BLOK INI ADA DAN SESUAI
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action == 'add' || $action == 'edit') {
            include 'pages/customer/customer_form.php';
            $page_title = ($action == 'add') ? 'Tambah Customer' : 'Edit Customer';
        } elseif ($action == 'delete') {
            // Logika hapus customer
            require_once __DIR__ . '/config/database.php';
            require_once __DIR__ . '/app/models/customer.php';
            $database = new Database();
            $db = $database->connect();
            $customer_model = new Customer($db);

            // Pastikan ID ada dan valid
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $customer_model->id_customer = $_GET['id'];
                if ($customer_model->delete()) {
                    $_SESSION['message'] = "Customer berhasil dihapus.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menghapus customer.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['message'] = "ID Customer tidak valid.";
                $_SESSION['message_type'] = "danger";
            }
            header('Location: ?page=customer'); // Redirect kembali ke daftar customer
            exit();
        } else {
            include 'pages/customer/customer.php';
            $page_title = 'Koperasi App - Customer';
        }
        break;
    case 'item': // <<< PASTIKAN BLOK INI ADA DAN SESUAI
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action == 'add' || $action == 'edit') {
            include 'pages/item/item_form.php';
            $page_title = ($action == 'add') ? 'Tambah Item' : 'Edit Item';
        } elseif ($action == 'delete') {
            // Logika hapus item
            require_once __DIR__ . '/config/database.php';
            require_once __DIR__ . '/app/models/Item.php';
            $database = new database();
            $db = $database->connect();
            $item_model = new Item($db);

            // Pastikan ID ada dan valid
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $item_model->id_item = $_GET['id'];
                if ($item_model->delete()) {
                    $_SESSION['message'] = "Item berhasil dihapus.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menghapus item.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['message'] = "ID Item tidak valid.";
                $_SESSION['message_type'] = "danger";
            }
            header('Location: ?page=item'); // Redirect kembali ke daftar item
            exit();
        } else {
            include 'pages/item/item.php';
            $page_title = 'Koperasi App - Item';
        }
        break;
    case 'sales': // <<< TAMBAHKAN BLOK INI
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action == 'add' || $action == 'edit') {
            include 'pages/sales/sales_form.php';
            $page_title = ($action == 'add') ? 'Tambah Sales' : 'Edit Sales';
        } elseif ($action == 'delete') {
            // Logika hapus sales
            require_once __DIR__ . '/config/database.php';
            require_once __DIR__ . '/app/models/Sales.php';
            $database = new Database();
            $db = $database->connect();
            $sales_model = new Sales($db);

            // Pastikan ID ada dan valid
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $sales_model->id_sales = $_GET['id'];
                if ($sales_model->delete()) {
                    $_SESSION['message'] = "Sales berhasil dihapus.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menghapus sales. Pastikan tidak ada transaksi terkait.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['message'] = "ID Sales tidak valid.";
                $_SESSION['message_type'] = "danger";
            }
            header('Location: ?page=sales'); // Redirect kembali ke daftar sales
            exit();
        } else {
            include 'pages/sales/sales.php';
            $page_title = 'Koperasi App - Sales';
        }
        break;
    case 'transaction': // <<< TAMBAHKAN BLOK INI
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action == 'add' || $action == 'edit') {
            include 'pages/transaction/transaction_form.php';
            $page_title = ($action == 'add') ? 'Tambah Transaksi Detail' : 'Edit Transaksi Detail';
        } elseif ($action == 'delete') {
            // Logika hapus transaksi
            require_once __DIR__ . '/config/database.php';
            require_once __DIR__ . '/app/models/Transaction.php';
            $database = new Database();
            $db = $database->connect();
            $transaction_model = new Transaction($db);

            // Pastikan ID ada dan valid
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $transaction_model->id_transaction = $_GET['id'];
                if ($transaction_model->delete()) {
                    $_SESSION['message'] = "Detail transaksi berhasil dihapus.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menghapus detail transaksi.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['message'] = "ID Transaksi tidak valid.";
                $_SESSION['message_type'] = "danger";
            }
            header('Location: ?page=transaction'); // Redirect kembali ke daftar transaksi
            exit();
        } else {
            include 'pages/transaction/transaction.php';
            $page_title = 'Koperasi App - Transaksi Detail';
        }
        break;
    case 'petugas': // <<< TAMBAHKAN BLOK INI
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action == 'add' || $action == 'edit') {
            include 'pages/petugas/petugas_form.php';
            $page_title = ($action == 'add') ? 'Tambah Petugas' : 'Edit Petugas';
        } elseif ($action == 'delete') {
            // Logika hapus petugas
            require_once __DIR__ . '/config/database.php';
            require_once __DIR__ . '/app/models/user.php';
            $database = new Database();
            $db = $database->connect();
            $user_model = new User($db);

            // Pastikan ID ada dan valid
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $user_model->id_user = $_GET['id'];
                if ($user_model->delete()) {
                    $_SESSION['message'] = "Petugas berhasil dihapus.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menghapus petugas. Pastikan tidak ada data terkait.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['message'] = "ID Petugas tidak valid.";
                $_SESSION['message_type'] = "danger";
            }
            header('Location: ?page=petugas'); // Redirect kembali ke daftar petugas
            exit();
        } else {
            include 'pages/petugas/petugas.php';
            $page_title = 'Koperasi App - Petugas';
        }
        break;
    default:
        include 'pages/dashboard.php';
        $page_title = 'Koperasi App - Dashboard';
        break;
}

$content = ob_get_clean();

// Load layout utama
include 'includes/header.php';
?>

<div id="wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <?php include 'includes/topbar.php'; ?>

            <div class="container-fluid">
                <?php echo $content; ?>
            </div>
            </div>
        <?php include 'includes/footer.php'; ?>

    </div>
    </div>
<?php include 'includes/scripts.php'; ?>