<?php
namespace App\Models;

use App\Config\Database;

class Workout {
    private $conn;
    private $table = 'workouts';
    private $categoryTable = 'workout_categories';
    private $userWorkoutTable = 'user_workouts';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /* ======================
       GET ALL CATEGORIES
    ====================== */
    public function getCategories() {
        $query = "SELECT * FROM {$this->categoryTable}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* ======================
       GET WORKOUTS BY CATEGORY
    ====================== */
    public function getWorkoutsByCategory($category) {
        if ($category === 'all') {
            $query = "SELECT w.*, c.category_name 
                      FROM {$this->table} w
                      JOIN {$this->categoryTable} c ON w.category_id = c.id
                      ORDER BY c.category_name, w.workout_name";
            $stmt = $this->conn->prepare($query);
        } else {
            $query = "SELECT w.*, c.category_name 
                      FROM {$this->table} w
                      JOIN {$this->categoryTable} c ON w.category_id = c.id
                      WHERE c.id = ?
                      ORDER BY w.workout_name";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $category);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ======================
       GET WORKOUT BY ID
    ====================== */
    public function getWorkoutById($id) {
        $query = "SELECT w.*, c.category_name 
                  FROM {$this->table} w
                  JOIN {$this->categoryTable} c ON w.category_id = c.id
                  WHERE w.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /* ======================
       COMPLETE WORKOUT
    ====================== */
    public function completeWorkout($data) {
        $workout = $this->getWorkoutById($data['workout_id']);
        $totalCalories = $workout['calories_burned'] * $data['sets_completed'];

        $query = "INSERT INTO {$this->userWorkoutTable}
                  (user_id, workout_id, date_completed, sets_completed, total_calories_burned)
                  VALUES (?, ?, CURDATE(), ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "iiii",
            $data['user_id'],
            $data['workout_id'],
            $data['sets_completed'],
            $totalCalories
        );

        return $stmt->execute();
    }

    /* ======================
       GET RECENT WORKOUTS
    ====================== */
    public function getRecentWorkouts($userId, $limit = 5) {
        $query = "SELECT uw.*, w.workout_name, w.calories_burned
                  FROM {$this->userWorkoutTable} uw
                  JOIN {$this->table} w ON uw.workout_id = w.id
                  WHERE uw.user_id = ?
                  ORDER BY uw.date_completed DESC
                  LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ======================
       GET TODAY CALORIES BURNED
    ====================== */
    public function getTodayCaloriesBurned($userId) {
        $query = "SELECT SUM(total_calories_burned) as total
                  FROM {$this->userWorkoutTable}
                  WHERE user_id = ? AND date_completed = CURDATE()";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    /* ======================
       CREATE NEW WORKOUT
    ====================== */
    public function createWorkout($data) {
        $query = "INSERT INTO {$this->table}
                  (category_id, workout_name, description, repetitions, duration_minutes, calories_burned, created_by)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param(
            "issiiii",
            $data['category_id'],
            $data['workout_name'],
            $data['description'],
            $data['repetitions'],
            $data['duration_minutes'],
            $data['calories_burned'],
            $data['created_by']
        );

        return $stmt->execute();
    }
}
?>
