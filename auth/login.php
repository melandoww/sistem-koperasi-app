<?php
// koperasi-app/auth/login.php

// Mulai session PHP
session_start();

// Jika user sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Arahkan ke dashboard utama
    exit();
}

// Untuk menampilkan pesan error (jika ada) dari proses_login.php
$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Hapus pesan setelah ditampilkan
}

// Tentukan base URL untuk aset
// Asumsi login.php ada di auth/, jadi kita perlu mundur satu level untuk ke root
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$base_url = str_replace('auth/', '', $base_url); // Hapus 'auth/' dari base_url

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Koperasi App - Login</title>

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
                                        <h1 class="h4 text-gray-900 mb-4">SISTEM KOPERASI PEGAWAI</h1>
                                    </div>
                                    <?php if ($error_message): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error_message; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form class="user" action="proses_login.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="username" name="username" aria-describedby="emailHelp"
                                                placeholder="Masukkan Username..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Ingat Saya</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                        </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Lupa Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Buat Akun Baru!</a>
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