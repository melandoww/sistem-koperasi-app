<?php
// app/models/Sales.php

class Sales {
    private $conn;
    private $table = 'sales'; // Nama tabel di database

    // Properti untuk operasi CRUD
    public $id_sales;
    public $tgl_sales;
    public $id_customer;
    public $do_number; // Delivery Order Number
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mendapatkan semua penjualan, join dengan customer
    public function getAll() {
        $query = "SELECT s.id_sales, s.tgl_sales, s.do_number, s.status,
                         c.nama_customer
                  FROM " . $this->table . " s
                  LEFT JOIN customer c ON s.id_customer = c.id_customer
                  ORDER BY s.id_sales DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Mengembalikan PDOStatement
    }

    // Metode untuk mendapatkan satu penjualan berdasarkan ID
    public function getById($id) {
        $query = "SELECT id_sales, tgl_sales, id_customer, do_number, status
                  FROM " . $this->table . "
                  WHERE id_sales = :id_sales
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_sales', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Mengembalikan array asosiatif
    }

    // Metode untuk menambah penjualan baru (Create)
    public function create() {
        $query = "INSERT INTO " . $this->table . " (tgl_sales, id_customer, do_number, status)
                  VALUES (:tgl_sales, :id_customer, :do_number, :status)";
        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->tgl_sales = htmlspecialchars(strip_tags($this->tgl_sales));
        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));
        $this->do_number = htmlspecialchars(strip_tags($this->do_number));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':tgl_sales', $this->tgl_sales);
        $stmt->bindParam(':id_customer', $this->id_customer);
        $stmt->bindParam(':do_number', $this->do_number);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk mengupdate penjualan (Update)
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET
                      tgl_sales = :tgl_sales,
                      id_customer = :id_customer,
                      do_number = :do_number,
                      status = :status
                  WHERE
                      id_sales = :id_sales";

        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->tgl_sales = htmlspecialchars(strip_tags($this->tgl_sales));
        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));
        $this->do_number = htmlspecialchars(strip_tags($this->do_number));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id_sales = htmlspecialchars(strip_tags($this->id_sales));

        $stmt->bindParam(':tgl_sales', $this->tgl_sales);
        $stmt->bindParam(':id_customer', $this->id_customer);
        $stmt->bindParam(':do_number', $this->do_number);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id_sales', $this->id_sales);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk menghapus penjualan (Delete)
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id_sales = :id_sales";
        $stmt = $this->conn->prepare($query);

        $this->id_sales = htmlspecialchars(strip_tags($this->id_sales));
        $stmt->bindParam(':id_sales', $this->id_sales);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>