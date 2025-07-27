<?php
// app/models/Transaction.php

class Transaction {
    private $conn;
    private $table = 'transaction'; // Nama tabel di database

    // Properti untuk operasi CRUD
    public $id_transaction;
    public $id_sales;
    public $id_item;
    public $quantity;
    public $price;  // Ini adalah harga jual per unit saat transaksi terjadi
    public $amount; // quantity * price

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mendapatkan semua transaksi, join dengan sales dan item
    public function getAll() {
        $query = "SELECT t.id_transaction, t.id_sales, t.quantity, t.price, t.amount,
                         s.do_number, s.tgl_sales,
                         i.nama_item, i.uom
                  FROM " . $this->table . " t
                  LEFT JOIN sales s ON t.id_sales = s.id_sales
                  LEFT JOIN item i ON t.id_item = i.id_item
                  ORDER BY t.id_transaction DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Mengembalikan PDOStatement
    }

    // Metode untuk mendapatkan transaksi berdasarkan id_sales (untuk melihat detail per sales)
    public function getBySalesId($sales_id) {
        $query = "SELECT t.id_transaction, t.id_sales, t.quantity, t.price, t.amount,
                         i.nama_item, i.uom, i.harga_jual
                  FROM " . $this->table . " t
                  LEFT JOIN item i ON t.id_item = i.id_item
                  WHERE t.id_sales = :id_sales
                  ORDER BY t.id_transaction DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_sales', $sales_id);
        $stmt->execute();
        return $stmt;
    }

    // Metode untuk mendapatkan satu transaksi berdasarkan ID
    public function getById($id) {
        $query = "SELECT id_transaction, id_sales, id_item, quantity, price, amount
                  FROM " . $this->table . "
                  WHERE id_transaction = :id_transaction
                  LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_transaction', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Mengembalikan array asosiatif
    }

    // Metode untuk menambah transaksi baru (Create)
    public function create() {
        $query = "INSERT INTO " . $this->table . " (id_sales, id_item, quantity, price, amount)
                  VALUES (:id_sales, :id_item, :quantity, :price, :amount)";
        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->id_sales = htmlspecialchars(strip_tags($this->id_sales));
        $this->id_item = htmlspecialchars(strip_tags($this->id_item));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->amount = htmlspecialchars(strip_tags($this->amount));

        $stmt->bindParam(':id_sales', $this->id_sales);
        $stmt->bindParam(':id_item', $this->id_item);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':amount', $this->amount);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk mengupdate transaksi (Update)
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET
                      id_sales = :id_sales,
                      id_item = :id_item,
                      quantity = :quantity,
                      price = :price,
                      amount = :amount
                  WHERE
                      id_transaction = :id_transaction";

        $stmt = $this->conn->prepare($query);

        // Sanitize dan bind data
        $this->id_sales = htmlspecialchars(strip_tags($this->id_sales));
        $this->id_item = htmlspecialchars(strip_tags($this->id_item));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->id_transaction = htmlspecialchars(strip_tags($this->id_transaction));

        $stmt->bindParam(':id_sales', $this->id_sales);
        $stmt->bindParam(':id_item', $this->id_item);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':id_transaction', $this->id_transaction);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metode untuk menghapus transaksi (Delete)
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id_transaction = :id_transaction";
        $stmt = $this->conn->prepare($query);

        $this->id_transaction = htmlspecialchars(strip_tags($this->id_transaction));
        $stmt->bindParam(':id_transaction', $this->id_transaction);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>