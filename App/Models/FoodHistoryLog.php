<?php
namespace App\Models;
use App\Config\Database;

class FoodHistoryLog {
    private $conn;
    private $table = 'food_history_logs';

     private $id;
    private $user_id;
    private $food_id;
    private $quantity;
    private $total_calories;
    private $date_added;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function logAction($data) {
        $query = "INSERT INTO $this->table
                  (user_id, food_id, food_name, quantity, calories_per_unit, total_calories, action_type, created_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "iisiiss",
            $data['user_id'],
            $data['food_id'],
            $data['food_name'],
            $data['quantity'],
            $data['calories_per_unit'],
            $data['total_calories'],
            $data['action_type']
        );

        return $stmt->execute();
    }

    public function getUserHistory($userId, $limit = 20, $offset = 0, $dateFilter = null) {
        if ($dateFilter) {
            $query = "SELECT * FROM $this->table
                      WHERE user_id = ? AND DATE(created_at) = ?
                      ORDER BY created_at DESC
                      LIMIT ? OFFSET ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("isii", $userId, $dateFilter, $limit, $offset);
        } else {
            $query = "SELECT * FROM $this->table
                      WHERE user_id = ?
                      ORDER BY created_at DESC
                      LIMIT ? OFFSET ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("iii", $userId, $limit, $offset);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalHistoryCount($userId, $dateFilter = null) {
        if ($dateFilter) {
            $query = "SELECT COUNT(*) as total FROM $this->table
                      WHERE user_id = ? AND DATE(created_at) = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("is", $userId, $dateFilter);
        } else {
            $query = "SELECT COUNT(*) as total FROM $this->table
                      WHERE user_id = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $userId);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    public function getUserStats($userId) {
        $query = "SELECT 
                    COUNT(*) as total_actions,
                    SUM(CASE WHEN action_type = 'add' THEN 1 ELSE 0 END) as total_added,
                    SUM(CASE WHEN action_type = 'delete' THEN 1 ELSE 0 END) as total_deleted,
                    SUM(CASE WHEN action_type = 'add' THEN total_calories ELSE 0 END) as total_calories_added,
                    AVG(CASE WHEN action_type = 'add' THEN total_calories END) as avg_calories_per_meal
                  FROM $this->table
                  WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }

    public function getCaloriesByDateRange($userId, $startDate, $endDate) {
        $query = "SELECT DATE(created_at) as date, 
                         SUM(CASE WHEN action_type = 'add' THEN total_calories ELSE 0 END) as daily_calories
                  FROM $this->table
                  WHERE user_id = ? AND DATE(created_at) BETWEEN ? AND ?
                  GROUP BY DATE(created_at)
                  ORDER BY date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getMostConsumedFoods($userId, $limit = 10) {
        $query = "SELECT food_name, 
                         COUNT(*) as consumption_count,
                         SUM(total_calories) as total_calories
                  FROM $this->table
                  WHERE user_id = ? AND action_type = 'add'
                  GROUP BY food_name
                  ORDER BY consumption_count DESC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

        public function getTodayFoods($userId, $date)
    {
        $query = "SELECT 
                    id,
                    food_id,
                    food_name,
                    quantity,
                    calories_per_unit AS calories,
                    total_calories
                FROM $this->table
                WHERE user_id = ?
                    AND action_type = 'add'
                    AND DATE(created_at) = ?
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $userId, $date);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}
?>