<?php

class Database {
    // Properti untuk koneksi database
    private $host = 'localhost'; // Server database Anda (biasanya localhost)
    private $db_name = 'koperasi'; // Ganti dengan nama database Anda yang dibuat di phpMyAdmin
    private $username = 'root'; // Ganti dengan username MySQL Anda (default XAMPP/MAMP adalah 'root')
    private $password = ''; // Ganti dengan password MySQL Anda (default XAMPP/MAMP adalah kosong '')
    private $conn; // Variabel untuk menyimpan objek koneksi PDO

    /**
     * Metode untuk mendapatkan koneksi database
     *
     * @return PDO|null Objek koneksi PDO jika berhasil, null jika gagal.
     */
    public function connect() {
        $this->conn = null; // Reset koneksi

        try {
            // String koneksi DSN (Data Source Name)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;

            // Membuat objek PDO baru
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Mengatur mode error untuk PDO agar melempar Exception pada error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Mengatur mode fetch default ke asosiatif array (key adalah nama kolom)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Mengatur charset koneksi
            $this->conn->exec("set names utf8");

            // Mengembalikan objek koneksi
            return $this->conn;

        } catch(PDOException $exception) {
            // Menangkap dan menampilkan pesan error jika koneksi gagal
            // Dalam lingkungan produksi, sebaiknya log error ini ke file dan tidak ditampilkan langsung ke user
            echo "Koneksi database gagal: " . $exception->getMessage();
            // Anda bisa tambahkan logging di sini:
            // error_log("Database connection error: " . $exception->getMessage(), 0);
            return null; // Kembalikan null jika koneksi gagal
        }
    }
}

?>