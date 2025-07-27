<?php
// koperasi-app/pages/transaction_form.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Transaction.php'; 
require_once __DIR__ . '/../../app/models/Sales.php'; // Untuk dropdown Sales
require_once __DIR__ . '/../../app/models/Item.php';  // Untuk dropdown Item dan harga jual
 
$database = new Database();
$db = $database->connect();
$transaction_model = new Transaction($db);
$sales_model = new Sales($db);
$item_model = new Item($db);

$page_title_action = "Tambah";
$id_transaction = '';
$id_sales = '';
$id_item = '';
$quantity = 1;
$price = 0;
$amount = 0;

// Ambil semua data Sales untuk dropdown
$stmt_sales = $sales_model->getAll();
$sales_list = $stmt_sales->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua data Item untuk dropdown
$stmt_items = $item_model->getAll();
$items_list = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi pesan
$message = '';
$message_type = '';

// Cek apakah mode edit
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $page_title_action = "Edit";
    $id_transaction = $_GET['id'];
    $transaction_data_edit = $transaction_model->getById($id_transaction);

    if ($transaction_data_edit) {
        $id_sales = $transaction_data_edit['id_sales'];
        $id_item = $transaction_data_edit['id_item'];
        $quantity = $transaction_data_edit['quantity'];
        $price = $transaction_data_edit['price'];
        $amount = $transaction_data_edit['amount'];
    } else {
        $_SESSION['message'] = "Detail transaksi tidak ditemukan!";
        $_SESSION['message_type'] = "danger";
        header('Location: ?page=transaction');
        exit();
    }
}

// Tangani POST request (untuk tambah atau edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_model->id_sales = $_POST['id_sales'];
    $transaction_model->id_item = $_POST['id_item'];
    $transaction_model->quantity = $_POST['quantity'];
    // Pastikan price dan amount tidak mengandung format ribuan saat disimpan
    $transaction_model->price = str_replace('.', '', $_POST['price']);
    $transaction_model->amount = str_replace('.', '', $_POST['amount']);

    $message = '';
    $message_type = '';

    if (isset($_POST['id_transaction']) && !empty($_POST['id_transaction'])) {
        // Mode Edit
        $transaction_model->id_transaction = $_POST['id_transaction'];
        if ($transaction_model->update()) {
            $_SESSION['message'] = "Detail transaksi berhasil diupdate!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=transaction');
            exit();
        } else {
            $message = "Gagal mengupdate detail transaksi.";
            $message_type = "danger";
        }
    } else {
        // Mode Tambah
        if ($transaction_model->create()) {
            $_SESSION['message'] = "Detail transaksi berhasil ditambahkan!";
            $_SESSION['message_type'] = "success";
            header('Location: ?page=transaction');
            exit();
        } else {
            $message = "Gagal menambahkan detail transaksi.";
            $message_type = "danger";
        }
    }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $page_title_action; ?> Transaksi Detail</h1>
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
        <h6 class="m-0 font-weight-bold text-primary">Form Transaksi Detail</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <?php if ($id_transaction): ?>
                <input type="hidden" name="id_transaction" value="<?php echo htmlspecialchars($id_transaction); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="idSales">Sales ID (DO Number):</label>
                <select class="form-control" id="idSales" name="id_sales" required>
                    <option value="">-- Pilih Sales --</option>
                    <?php foreach ($sales_list as $sales_opt): ?>
                        <option value="<?php echo htmlspecialchars($sales_opt['id_sales']); ?>"
                            <?php echo ($sales_opt['id_sales'] == $id_sales) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sales_opt['id_sales'] . " - " . $sales_opt['do_number'] . " (" . $sales_opt['tgl_sales'] . ")"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="idItem">Nama Item:</label>
                <select class="form-control" id="idItem" name="id_item" required>
                    <option value="">-- Pilih Item --</option>
                    <?php foreach ($items_list as $item_opt): ?>
                        <option value="<?php echo htmlspecialchars($item_opt['id_item']); ?>"
                            data-price="<?php echo htmlspecialchars($item_opt['harga_jual']); ?>"
                            <?php echo ($item_opt['id_item'] == $id_item) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($item_opt['nama_item'] . " (" . $item_opt['uom'] . ")"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" min="1" required>
            </div>

            <div class="form-group">
                <label for="price">Harga per Unit:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars(number_format($price, 0, ',', '.')); ?>" readonly required>
                </div>

            <div class="form-group">
                <label for="amount">Amount (Total):</label>
                <input type="text" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars(number_format($amount, 0, ',', '.')); ?>" readonly required>
                </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="?page=transaction" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const idItemSelect = document.getElementById('idItem');
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const amountInput = document.getElementById('amount');

        function calculateAmount() {
            const selectedOption = idItemSelect.options[idItemSelect.selectedIndex];
            const itemPrice = parseFloat(selectedOption.dataset.price) || 0;
            const qty = parseInt(quantityInput.value) || 0;

            // Update price input (formatted)
            priceInput.value = itemPrice.toLocaleString('id-ID'); // Format angka Indonesia

            // Calculate amount
            const calculatedAmount = itemPrice * qty;
            // Update amount input (formatted)
            amountInput.value = calculatedAmount.toLocaleString('id-ID'); // Format angka Indonesia
        }

        // Initial calculation when page loads (useful for edit mode)
        calculateAmount();

        // Attach event listeners
        idItemSelect.addEventListener('change', calculateAmount);
        quantityInput.addEventListener('input', calculateAmount); // Use 'input' for real-time update
    });
</script>