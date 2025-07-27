<?php
// app/models/User.php

class User {
    private $conn;
    private $table = 'petugas'; // Sesuai nama tabel petugas di database Anda

    // Properti objek User
    public $id_user;
    public $nama_user;
    public $username;
    public $password; // Password akan di-hash
    public $level; // ID level

    // Constructor dengan koneksi DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mencoba login
    public function login($username, $password) {
        // Query untuk mencari user berdasarkan username
        // Menggabungkan dengan tabel level untuk mendapatkan nama level
        $query = 'SELECT p.id_user, p.nama_user, p.username, p.password, p.level, l.level as level_name
                  FROM ' . $this->table . ' p
                  JOIN level l ON p.level = l.id_level
                  WHERE p.username = :username LIMIT 0,1';

        $stmt = $this->conn->prepare($query);

        // Sanitasi username
        $username = htmlspecialchars(strip_tags($username));
        $stmt->bindParam(':username', $username);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Verifikasi password (gunakan password_verify karena password harus di-hash di DB)
            if (password_verify($password, $row['password'])) {
                // Password cocok, kembalikan data user
                return [
                    'id_user' => $row['id_user'],
                    'nama_user' => $row['nama_user'],
                    'username' => $row['username'],
                    'level' => $row['level'], // ID level
                    'level_name' => $row['level_name'] // Nama level
                ];
            }
        }
        return false; // Login gagal
    }

    // Metode untuk register user baru (jika diperlukan)
    // Penting: Password HARUS di-hash sebelum disimpan ke database
    public function register($nama_user, $username, $password, $level_id) {
        // Cek apakah username sudah ada
        $check_query = 'SELECT id_user FROM ' . $this->table . ' WHERE username = :username LIMIT 0,1';
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':username', $username);
        $check_stmt->execute();
        if ($check_stmt->rowCount() > 0) {
            return false; // Username sudah terdaftar
        }

        $query = 'INSERT INTO ' . $this->table . ' (nama_user, username, password, level) VALUES (:nama_user, :username, :password, :level)';
        $stmt = $this->conn->prepare($query);

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Sanitasi data
        $nama_user = htmlspecialchars(strip_tags($nama_user));
        $username = htmlspecialchars(strip_tags($username));
        // Password tidak perlu strip_tags setelah di-hash
        $level_id = htmlspecialchars(strip_tags($level_id));

        // Bind data
        $stmt->bindParam(':nama_user', $nama_user);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':level', $level_id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

     // Metode BARU: untuk mendapatkan semua level dari tabel 'level'
    public function getAllLevels() {
        $query = "SELECT id_level, level FROM level ORDER BY id_level ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Metode untuk mendapatkan detail user berdasarkan ID
    public function getUserById($id_user) {
        $query = 'SELECT p.id_user, p.nama_user, p.username, p.level, l.level as level_name
                  FROM ' . $this->table . ' p
                  JOIN level l ON p.level = l.id_level
                  WHERE p.id_user = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_user);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // BAGIAN PETUGAS
        // Metode untuk mendapatkan semua petugas
    public function getAll() {
        $query = "SELECT p.id_user, p.nama_user, p.username, l.level as level_name
                  FROM " . $this->table . " p
                  JOIN level l ON p.level = l.id_level
                  ORDER BY p.id_user DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Mengembalikan PDOStatement
    }
        // Metode untuk mendapatkan satu petugas berdasarkan ID
    public function getById($id) {
        $query = "SELECT p.id_user, p.nama_user, p.username, p.password, p.level as level_id, l.level as level_name
                  FROM " . $this->table . " p
                  JOIN level l ON p.level = l.id_level
                  WHERE p.id_user = :id_user
                  LIMIT 0,1"; // Limit 1 karena hanya ingin 1 record
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Mengembalikan array asosiatif
    }
    
    // Metode untuk menambah petugas baru (Create)
    public function create() {
        $query = "INSERT INTO " . $this->table . " (nama_user, username, password, level)
                  VALUES (:nama_user, :username, :password, :level)";
        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->nama_user = htmlspecialchars(strip_tags($this->nama_user));
        $this->username = htmlspecialchars(strip_tags($this->username));
        // Password harus di-hash sebelum disimpan
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->level = htmlspecialchars(strip_tags($this->level));

        $stmt->bindParam(':nama_user', $this->nama_user);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':level', $this->level); // Ini adalah id_level

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk mengupdate petugas (Update)
    public function update() {
        // Perhatikan: password tidak diupdate jika kosong
        $query = "UPDATE " . $this->table . "
                  SET
                      nama_user = :nama_user,
                      username = :username,
                      " . (!empty($this->password) ? "password = :password," : "") . "
                      level = :level
                  WHERE
                      id_user = :id_user";

        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->nama_user = htmlspecialchars(strip_tags($this->nama_user));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->level = htmlspecialchars(strip_tags($this->level));
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));

        $stmt->bindParam(':nama_user', $this->nama_user);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':level', $this->level);
        $stmt->bindParam(':id_user', $this->id_user);

        // Jika password diisi, hash dan bind
        if (!empty($this->password)) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $this->password);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk menghapus petugas (Delete)
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);

        $this->id_user = htmlspecialchars(strip_tags($this->id_user));
        $stmt->bindParam(':id_user', $this->id_user);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
?>