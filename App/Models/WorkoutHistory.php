<?php
// App/Models/WorkoutHistory.php
namespace App\Models;

use App\Config\Database;

class WorkoutHistory {
    private $conn;
    private $table = 'workout_history';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /* ======================
       ADD WORKOUT HISTORY
    ====================== */
    public function addWorkoutHistory($data) {
        $query = "INSERT INTO {$this->table}
                  (user_id, workout_id, sets_completed, total_calories_burned, 
                   duration_minutes, completed_at, notes)
                  VALUES (?, ?, ?, ?, ?, NOW(), ?)";

        $stmt = $this->conn->prepare($query);
        
        $notes = isset($data['notes']) ? $data['notes'] : '';
        
        $stmt->bind_param(
            "iiiiss",
            $data['user_id'],
            $data['workout_id'],
            $data['sets_completed'],
            $data['total_calories_burned'],
            $data['duration_minutes'],
            $notes
        );

        return $stmt->execute();
    }

    /* ======================
       GET WORKOUT HISTORY BY USER
    ====================== */
    public function getWorkoutHistory($userId, $workoutId = null, $limit = 10) {
        if ($workoutId) {
            // Get history for specific workout
            $query = "SELECT wh.*, w.workout_name, w.calories_burned as calories_per_set,
                             c.category_name
                      FROM {$this->table} wh
                      JOIN workouts w ON wh.workout_id = w.id
                      JOIN workout_categories c ON w.category_id = c.id
                      WHERE wh.user_id = ? AND wh.workout_id = ?
                      ORDER BY wh.completed_at DESC
                      LIMIT ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("iii", $userId, $workoutId, $limit);
        } else {
            // Get all history for user
            $query = "SELECT wh.*, w.workout_name, w.calories_burned as calories_per_set,
                             c.category_name
                      FROM {$this->table} wh
                      JOIN workouts w ON wh.workout_id = w.id
                      JOIN workout_categories c ON w.category_id = c.id
                      WHERE wh.user_id = ?
                      ORDER BY wh.completed_at DESC
                      LIMIT ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $userId, $limit);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ======================
       GET RECENT WORKOUTS (DASHBOARD)
    ====================== */
    public function getRecentWorkouts($userId, $limit = 5) {
        $query = "SELECT wh.*, w.workout_name, w.calories_burned as calories_per_set,
                         c.category_name,
                         DATE(wh.completed_at) as date_completed
                  FROM {$this->table} wh
                  JOIN workouts w ON wh.workout_id = w.id
                  JOIN workout_categories c ON w.category_id = c.id
                  WHERE wh.user_id = ?
                  ORDER BY wh.completed_at DESC
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
                  FROM {$this->table}
                  WHERE user_id = ? AND DATE(completed_at) = CURDATE()";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    /* ======================
       GET WORKOUT STATS
    ====================== */
    public function getWorkoutStats($userId) {
        $query = "SELECT 
                    COUNT(DISTINCT workout_id) as unique_workouts,
                    COUNT(*) as total_sessions,
                    SUM(total_calories_burned) as total_calories,
                    SUM(sets_completed) as total_sets,
                    SUM(duration_minutes) as total_minutes
                  FROM {$this->table}
                  WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /* ======================
       GET WORKOUT STREAK
    ====================== */
    public function getWorkoutStreak($userId) {
        $query = "SELECT DISTINCT DATE(completed_at) as workout_date
                  FROM {$this->table}
                  WHERE user_id = ?
                  ORDER BY workout_date DESC
                  LIMIT 30";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $dates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        $streak = 0;
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $today = date('Y-m-d');

        foreach ($dates as $row) {
            $workoutDate = $row['workout_date'];
            
            if ($workoutDate == $today || $workoutDate == $yesterday) {
                $streak++;
                $yesterday = date('Y-m-d', strtotime($workoutDate . ' -1 day'));
            } else {
                break;
            }
        }

        return $streak;
    }

    /* ======================
       DELETE WORKOUT HISTORY
    ====================== */
    public function deleteWorkoutHistory($id, $userId) {
        $query = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
    
    public function deleteByWorkoutId($workoutId) {
    $query = "DELETE FROM {$this->table} WHERE workout_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $workoutId);
    return $stmt->execute();
}
}

?>