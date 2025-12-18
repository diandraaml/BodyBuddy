<?php
namespace App\Models;

use App\Config\Database;

class PaymentMethod {
    private $conn;
    private $table = 'payment_methods';

    private $id;
    private $user_id;
    private $payment_type;
    private $card_number;
    private $card_holder_name;
    private $expiry_date;
    private $is_default;
    private $created_at;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); 
    }

    // Ambil semua payment methods milik trainer
    public function getByTrainer($trainerId) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE trainer_id = ? 
                  AND is_active = 1
                  ORDER BY method_type ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $trainerId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil payment method by ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Tambah payment method baru
    public function create($data) {
        $query = "INSERT INTO {$this->table}
                  (trainer_id, method_type, account_name, account_number, bank_name, is_active)
                  VALUES (?, ?, ?, ?, ?, 1)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "issss",
            $data['trainer_id'],
            $data['method_type'],
            $data['account_name'],
            $data['account_number'],
            $data['bank_name']
        );

        return $stmt->execute();
    }

    // Update payment method
    public function update($id, $data) {
        $query = "UPDATE {$this->table}
                  SET method_type = ?,
                      account_name = ?,
                      account_number = ?,
                      bank_name = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "ssssi",
            $data['method_type'],
            $data['account_name'],
            $data['account_number'],
            $data['bank_name'],
            $id
        );

        return $stmt->execute();
    }

    // Soft delete
    public function delete($id) {
        $query = "UPDATE {$this->table} SET is_active = 0 WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // Hard delete
    public function hardDelete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // Cek apakah trainer punya payment method aktif
    public function hasActivePaymentMethod($trainerId) {
        $query = "SELECT COUNT(*) AS count
                  FROM {$this->table}
                  WHERE trainer_id = ?
                  AND is_active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $trainerId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row['count'] > 0;
    }
}
?>
