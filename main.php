<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$page = $_GET['page'] ?? 'dashboard';

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
include 'pages/' . $page . '.php';
include 'includes/footer.php';
?>
