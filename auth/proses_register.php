<?php
// koperasi-app/auth/proses_register.php

session_start();

// Pastikan ini adalah request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit();
}

// Sertakan file koneksi database dan model User
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/user.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

// Ambil data dari form
$nama_user = trim($_POST['nama_user'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$level_id = $_POST['level_id'] ?? '';

// ===================================
// VALIDASI INPUT
// ===================================

if (empty($nama_user) || empty($username) || empty($password) || empty($confirm_password) || empty($confirm_password)) {
    $_SESSION['register_message'] = "Semua field harus diisi.";
    $_SESSION['register_message_type'] = "danger";
    header('Location: register.php');
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['register_message'] = "Konfirmasi password tidak cocok.";
    $_SESSION['register_message_type'] = "danger";
    header('Location: register.php');
    exit();
}

// Minimal panjang password (sesuaikan kebutuhan)
if (strlen($password) < 6) {
    $_SESSION['register_message'] = "Password minimal 6 karakter.";
    $_SESSION['register_message_type'] = "danger";
    header('Location: register.php');
    exit();
}

// Opsional: Validasi apakah level_id adalah angka dan ada di database (lebih aman)
// Untuk saat ini, kita biarkan foreign key di DB yang menangkap jika level_id tidak valid
if (!is_numeric($level_id) || $level_id <= 0) {
     $_SESSION['register_message'] = "Pilihan level tidak valid.";
     $_SESSION['register_message_type'] = "danger";
     header('Location: register.php');
     exit();
}

// Asumsi default level untuk user yang daftar sendiri adalah 'Petugas' (misal level ID 2)
// Anda harus menyesuaikan ini dengan ID level 'Petugas' di tabel `level` Anda
// $default_level_id = 2; // Ganti dengan ID level yang sesuai untuk 'Petugas'

// ===================================
// PROSES REGISTRASI
// ===================================

// Panggil metode register dari model User
if ($user->register($nama_user, $username, $password, $level_id)) {
    $_SESSION['register_message'] = "Registrasi berhasil! Silakan login.";
    $_SESSION['register_message_type'] = "success";
    header('Location: login.php'); // Arahkan ke halaman login
    exit();
} else {
    // Registrasi gagal, mungkin username sudah ada atau ada error DB lainnya
    $_SESSION['register_message'] = "Registrasi gagal. Username mungkin sudah terdaftar.";
    $_SESSION['register_message_type'] = "danger";
    header('Location: register.php');
    exit();
}
?>