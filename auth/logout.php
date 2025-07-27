<?php
// koperasi-app/auth/logout.php

session_start(); // Mulai session

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan ke halaman login
header('Location: login.php');
exit();
?>