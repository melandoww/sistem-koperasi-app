<?php
// koperasi-app/auth/proses_login.php

session_start(); // Selalu mulai session di awal script

// Pastikan ini adalah request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php'); // Arahkan kembali jika bukan POST
    exit();
}

// Sertakan file koneksi database dan model User
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/user.php'; // Kita akan buat model User ini

$database = new database();
$db = $database->connect();

$user = new user($db);

// Ambil data dari form
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validasi input sederhana
if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = "Username dan password harus diisi.";
    header('Location: login.php');
    exit();
}

// Coba lakukan proses login melalui model User
$loggedInUser = $user->login($username, $password);

if ($loggedInUser) {
    // Login berhasil
    $_SESSION['user_id'] = $loggedInUser['id_user'];
    $_SESSION['username'] = $loggedInUser['username'];
    $_SESSION['nama_user'] = $loggedInUser['nama_user'];
    $_SESSION['level_id'] = $loggedInUser['level']; 
    $_SESSION['level_name'] = $loggedInUser['level_name']; // Asumsi Anda akan mengelola level

    // Arahkan ke dashboard utama
    header('Location: ../index.php');
    exit();
} else {
    // Login gagal
    $_SESSION['login_error'] = "Username atau password salah.";
    header('Location: login.php');
    exit();
}
?>