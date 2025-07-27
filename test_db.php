<?php
// test_db.php

// Pastikan path ke file Database.php benar
require_once __DIR__ . '/config/database.php';

// Buat instance dari kelas Database
$database = new Database();

// Coba konek ke database
$db = $database->connect();

// Periksa apakah koneksi berhasil
if ($db) {
    echo "<h1>Koneksi database berhasil!</h1>";

    // Contoh: Coba ambil data dari tabel 'customer'
    try {
        $stmt = $db->query("SELECT id_customer, nama_customer FROM customer LIMIT 5");
        $customers = $stmt->fetchAll();

        echo "<h2>Data Customer (5 Data Pertama):</h2>";
        if (count($customers) > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID Customer</th><th>Nama Customer</th></tr>";
            foreach ($customers as $customer) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($customer['id_customer']) . "</td>";
                echo "<td>" . htmlspecialchars($customer['nama_customer']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada data customer.</p>";
        }
    } catch (PDOException $e) {
        echo "Error saat mengambil data: " . $e->getMessage();
    }

} else {
    echo "<h1>Koneksi database GAGAL!</h1>";
}

?>