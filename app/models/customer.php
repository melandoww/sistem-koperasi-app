<?php
// app/models/Customer.php

class Customer {
    private $conn;
    private $table = 'customer'; // Nama tabel di database

    // Properti untuk operasi CRUD
    public $id_customer;
    public $nama_customer;
    public $alamat;
    public $telp;
    public $fax;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mendapatkan semua customer
    public function getAll() {
        $query = "SELECT id_customer, nama_customer, alamat, telp, fax, email
                  FROM " . $this->table . "
                  ORDER BY id_customer DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Mengembalikan PDOStatement
    }

    // Metode untuk mendapatkan satu customer berdasarkan ID
    public function getById($id) {
        $query = "SELECT id_customer, nama_customer, alamat, telp, fax, email
                  FROM " . $this->table . "
                  WHERE id_customer = :id_customer
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_customer', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Mengembalikan array asosiatif
    }

    // Metode untuk menambah customer baru (Create)
    public function create() {
        $query = "INSERT INTO " . $this->table . " (nama_customer, alamat, telp, fax, email)
                  VALUES (:nama_customer, :alamat, :telp, :fax, :email)";
        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->nama_customer = htmlspecialchars(strip_tags($this->nama_customer));
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->telp = htmlspecialchars(strip_tags($this->telp));
        $this->fax = htmlspecialchars(strip_tags($this->fax));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':nama_customer', $this->nama_customer);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':telp', $this->telp);
        $stmt->bindParam(':fax', $this->fax);
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk mengupdate customer (Update)
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET
                      nama_customer = :nama_customer,
                      alamat = :alamat,
                      telp = :telp,
                      fax = :fax,
                      email = :email
                  WHERE
                      id_customer = :id_customer";

        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->nama_customer = htmlspecialchars(strip_tags($this->nama_customer));
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->telp = htmlspecialchars(strip_tags($this->telp));
        $this->fax = htmlspecialchars(strip_tags($this->fax));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));

        $stmt->bindParam(':nama_customer', $this->nama_customer);
        $stmt->bindParam(':alamat', $this->alamat);
        $stmt->bindParam(':telp', $this->telp);
        $stmt->bindParam(':fax', $this->fax);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id_customer', $this->id_customer);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk menghapus customer (Delete)
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id_customer = :id_customer";
        $stmt = $this->conn->prepare($query);

        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));
        $stmt->bindParam(':id_customer', $this->id_customer);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>