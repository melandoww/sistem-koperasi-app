<?php
// app/models/Item.php

class Item {
    private $conn;
    private $table = 'item'; // Nama tabel di database

    // Properti untuk operasi CRUD
    public $id_item;
    public $nama_item;
    public $uom; // Satuan unit
    public $harga_beli;
    public $harga_jual;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mendapatkan semua item
    public function getAll() {
        $query = "SELECT id_item, nama_item, uom, harga_beli, harga_jual
                  FROM " . $this->table . "
                  ORDER BY id_item DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Mengembalikan PDOStatement
    }

    // Metode untuk mendapatkan satu item berdasarkan ID
    public function getById($id) {
        $query = "SELECT id_item, nama_item, uom, harga_beli, harga_jual
                  FROM " . $this->table . "
                  WHERE id_item = :id_item
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_item', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Mengembalikan array asosiatif
    }

    // Metode untuk menambah item baru (Create)
    public function create() {
        $query = "INSERT INTO " . $this->table . " (nama_item, uom, harga_beli, harga_jual)
                  VALUES (:nama_item, :uom, :harga_beli, :harga_jual)";
        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->nama_item = htmlspecialchars(strip_tags($this->nama_item));
        $this->uom = htmlspecialchars(strip_tags($this->uom));
        $this->harga_beli = htmlspecialchars(strip_tags($this->harga_beli));
        $this->harga_jual = htmlspecialchars(strip_tags($this->harga_jual));

        $stmt->bindParam(':nama_item', $this->nama_item);
        $stmt->bindParam(':uom', $this->uom);
        $stmt->bindParam(':harga_beli', $this->harga_beli);
        $stmt->bindParam(':harga_jual', $this->harga_jual);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk mengupdate item (Update)
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET
                      nama_item = :nama_item,
                      uom = :uom,
                      harga_beli = :harga_beli,
                      harga_jual = :harga_jual,
                  WHERE
                      id_item = :id_item";

        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->nama_item = htmlspecialchars(strip_tags($this->nama_item));
        $this->uom = htmlspecialchars(strip_tags($this->uom));
        $this->harga_beli = htmlspecialchars(strip_tags($this->harga_beli));
        $this->harga_jual = htmlspecialchars(strip_tags($this->harga_jual));
        $this->id_item = htmlspecialchars(strip_tags($this->id_item));

        $stmt->bindParam(':nama_item', $this->nama_item);
        $stmt->bindParam(':uom', $this->uom);
        $stmt->bindParam(':harga_beli', $this->harga_beli);
        $stmt->bindParam(':harga_jual', $this->harga_jual);
        $stmt->bindParam(':id_item', $this->id_item);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk menghapus item (Delete)
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id_item = :id_item";
        $stmt = $this->conn->prepare($query);

        $this->id_item = htmlspecialchars(strip_tags($this->id_item));
        $stmt->bindParam(':id_item', $this->id_item);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>