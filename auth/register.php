<?php
// koperasi-app/auth/register.php

session_start();

// Jika user sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Untuk menampilkan pesan error/sukses
$message = '';
$message_type = '';

if (isset($_SESSION['register_message'])) {
    $message = $_SESSION['register_message'];
    $message_type = $_SESSION['register_message_type'];
    unset($_SESSION['register_message']);
    unset($_SESSION['register_message_type']);
}

// ===============================================
// PASTE BARIS INI DARI login.php KE SINI
// Tentukan base URL untuk aset
// Asumsi register.php ada di auth/, jadi kita perlu mundur satu level untuk ke root
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$base_url = str_replace('auth/', '', $base_url); // Hapus 'auth/' dari base_url
// ===============================================


// SERTAKAN FILE KONEKSI DAN MODEL DI SINI UNTUK MENGAMBIL DATA LEVEL
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/user.php';

$database = new Database(); // Perhatikan huruf kapital 'D' pada Database()
$db = $database->connect();
$user_model = new User($db); // Perhatikan huruf kapital 'U' pada User()

$levels = [];
if ($db) { // Pastikan koneksi database berhasil sebelum mengambil level
    $levels = $user_model->getAllLevels();
} else {
    // Handle error jika koneksi DB gagal
    $message = "Koneksi database untuk mengambil level gagal.";
    $message_type = "danger";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Koperasi App - Register</title>

    <link href="<?php echo $base_url; ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?php echo $base_url; ?>css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="row justify-content-center">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Buat Akun Baru!</h1>
                            </div>
                            <?php if ($message): ?>
                                <div class="alert alert-<?php echo $message_type; ?>" role="alert">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            <form class="user" action="proses_register.php" method="POST">
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="namaUser" name="nama_user"
                                            placeholder="Nama Lengkap" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="username" name="username"
                                        placeholder="Username" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="repeatPassword" name="confirm_password" placeholder="Ulangi Password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" id="level" name="level_id" required>
                                        <option value="">-- Pilih Level --</option>
                                        <?php foreach ($levels as $level): ?>
                                            <option value="<?php echo htmlspecialchars($level['id_level']); ?>">
                                                <?php echo htmlspecialchars($level['level']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Daftar Akun
                                </button>
                                <hr>
                                </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Lupa Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Sudah punya akun? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <script src="<?php echo $base_url; ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $base_url; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo $base_url; ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="<?php echo $base_url; ?>js/sb-admin-2.min.js"></script>

</body>

</html>