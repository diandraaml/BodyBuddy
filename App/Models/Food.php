<?php
namespace App\Models;
use App\Config\Database;

class Food {
    private $conn;
    private $table = 'foods';
    private $userFoodTable = 'user_foods';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); // MySQLi connection
    }

    public function getAllFoods() {
        $query = "SELECT * FROM $this->table ORDER BY food_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFoodById($id) {
        $query = "SELECT * FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addUserFood($data) {
        $food = $this->getFoodById($data['food_id']);
        $totalCalories = $food['calories'] * $data['quantity'];

        $query = "INSERT INTO $this->userFoodTable
                  (user_id, food_id, quantity, total_calories, date_added)
                  VALUES (?, ?, ?, ?, CURDATE())";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "iiii",
            $data['user_id'],
            $data['food_id'],
            $data['quantity'],
            $totalCalories
        );

        return $stmt->execute();
    }

    public function getUserFoodsToday($userId) {
        $query = "SELECT uf.*, f.food_name, f.calories
                  FROM $this->userFoodTable uf
                  JOIN $this->table f ON uf.food_id = f.id
                  WHERE uf.user_id = ? AND uf.date_added = CURDATE()
                  ORDER BY uf.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTodayCalories($userId) {
        $query = "SELECT SUM(total_calories) AS total
                  FROM $this->userFoodTable
                  WHERE user_id = ? AND date_added = CURDATE()";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    public function deleteUserFood($id, $userId) {
        $query = "DELETE FROM $this->userFoodTable
                  WHERE id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id, $userId);

        return $stmt->execute();
    }

    public function createFood($data) {
        $query = "INSERT INTO $this->table
                  (food_name, calories, protein, carbs, fats, description, created_by)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "siiiisi",
            $data['food_name'],
            $data['calories'],
            $data['protein'],
            $data['carbs'],
            $data['fats'],
            $data['description'],
            $data['created_by']
        );

        return $stmt->execute();
    }
}
?>
