<?php
// koperasi-app/pages/dashboard.php

// Pastikan koneksi database dan model sudah di-include
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/customer.php';
require_once __DIR__ . '/../app/models/Item.php';
require_once __DIR__ . '/../app/models/Sales.php';
require_once __DIR__ . '/../app/models/Transaction.php'; // Diperlukan untuk total amount

$database = new Database();
$db = $database->connect();

$customer_model = new Customer($db);
$item_model = new Item($db);
$sales_model = new Sales($db);
$transaction_model = new Transaction($db);

// --- Ambil Data Statistik ---

// Total Customer
$stmt_customer_count = $db->query("SELECT COUNT(*) as total FROM customer");
$total_customers = $stmt_customer_count->fetch(PDO::FETCH_ASSOC)['total'];

// Total Item
$stmt_item_count = $db->query("SELECT COUNT(*) as total FROM item");
$total_items = $stmt_item_count->fetch(PDO::FETCH_ASSOC)['total'];

// Total Sales (jumlah record sales)
$stmt_sales_count = $db->query("SELECT COUNT(*) as total FROM sales");
$total_sales = $stmt_sales_count->fetch(PDO::FETCH_ASSOC)['total'];

// Total Revenue (sum dari amount di tabel transaction)
$stmt_total_revenue = $db->query("SELECT SUM(amount) as total FROM transaction");
$total_revenue = $stmt_total_revenue->fetch(PDO::FETCH_ASSOC)['total'];
// Format mata uang Rupiah
$total_revenue_formatted = 'Rp ' . number_format($total_revenue, 0, ',', '.');


// --- Ambil Data Sales Terbaru (misal 5 data terakhir) ---
$query_recent_sales = "SELECT s.id_sales, s.tgl_sales, s.do_number, s.status,
                             c.nama_customer
                       FROM sales s
                       LEFT JOIN customer c ON s.id_customer = c.id_customer
                       ORDER BY s.tgl_sales DESC, s.id_sales DESC
                       LIMIT 5"; // Ambil 5 data sales terbaru
$stmt_recent_sales = $db->prepare($query_recent_sales);
$stmt_recent_sales->execute();
$recent_sales_num = $stmt_recent_sales->rowCount();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pelanggan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($total_customers); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($total_items); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Sales</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo htmlspecialchars($total_sales); ?></div>
                            </div>
                            </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Pendapatan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_revenue_formatted; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Penjualan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Sales ID</th>
                                <th>Tanggal Sales</th>
                                <th>DO Number</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($recent_sales_num > 0) {
                                while ($row = $stmt_recent_sales->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($id_sales); ?></td>
                                        <td><?php echo htmlspecialchars($tgl_sales); ?></td>
                                        <td><?php echo htmlspecialchars($do_number); ?></td>
                                        <td><?php echo htmlspecialchars($nama_customer); ?></td>
                                        <td><?php echo htmlspecialchars($status); ?></td>
                                        <td>
                                            <a href="?page=sales&action=edit&id=<?php echo $id_sales; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center">Tidak ada penjualan terbaru.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>