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
                  (category_id, workout_name, description, repetitions, duration_minutes, calories_burned, video_url, created_by)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param(
            "issiissi",
            $data['category_id'],
            $data['workout_name'],
            $data['description'],
            $data['repetitions'],
            $data['duration_minutes'],
            $data['calories_burned'],
            $data['video_url'],
            $data['created_by']
        );

        return $stmt->execute();
    }

    /* ======================
       EXTRACT YOUTUBE VIDEO ID
    ====================== */
    public function extractYoutubeId($url) {
        // Handle different YouTube URL formats
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtu\.be\/([^?]+)/',
            '/youtube\.com\/embed\/([^?]+)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return $url; // Return as is if already an ID
    }
    
/* ======================
   GET WORKOUT BY ID
====================== */
public function getWorkoutById($id) {
    $query = "SELECT w.*, wc.category_name, u.username as created_by_name
              FROM workouts w
              LEFT JOIN workout_categories wc ON w.category_id = wc.id
              LEFT JOIN users u ON w.created_by = u.id
              WHERE w.id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/* ======================
   DELETE WORKOUT
====================== */
public function deleteWorkout($id) {
    $query = "DELETE FROM workouts WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
}
?>