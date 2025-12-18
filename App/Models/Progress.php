<?php
namespace App\Models;
use App\Config\Database;

class Progress {
    private $conn;
    private $table = 'progress';

    private $id;
    private $user_id;
    private $weight;
    private $bmi;
    private $recorded_date;
    private $notes;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); // MySQLi connection
    }

    public function addProgress($userId, $weight, $bmi, $notes = '') {
        $query = "INSERT INTO $this->table 
                  (user_id, weight, bmi, recorded_date, notes) 
                  VALUES (?, ?, ?, CURDATE(), ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "idds",   // i = int, d = double, s = string
            $userId,
            $weight,
            $bmi,
            $notes
        );

        return $stmt->execute();
    }

    public function getProgressByUser($userId) {
        $query = "SELECT * FROM $this->table 
                  WHERE user_id = ? 
                  ORDER BY recorded_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
