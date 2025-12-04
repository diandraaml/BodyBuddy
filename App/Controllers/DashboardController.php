<?php
// App/Controllers/DashboardController.php
namespace App\Controllers;

use App\Models\Workout;
use App\Models\Food;
use App\Models\WorkoutHistory;

class DashboardController {
    private $workoutModel;
    private $foodModel;
    private $workoutHistoryModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->workoutModel = new Workout();
        $this->foodModel = new Food();
        $this->workoutHistoryModel = new WorkoutHistory();
    }

    public function index() {
        // Get workout categories
        $workoutCategories = $this->workoutModel->getCategories();
        
        // Get recent workouts from WorkoutHistory (BUKAN dari Workout model)
        $recentWorkouts = $this->workoutHistoryModel->getRecentWorkouts($_SESSION['user_id'], 5);
        
        // Get today's calories from food
        $todayCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);
        
        // Get today's calories burned from WorkoutHistory
        $todayCaloriesBurned = $this->workoutHistoryModel->getTodayCaloriesBurned($_SESSION['user_id']);
        
        // Optional: Get workout stats
        $workoutStats = $this->workoutHistoryModel->getWorkoutStats($_SESSION['user_id']);
        
        // Optional: Get workout streak
        $workoutStreak = $this->workoutHistoryModel->getWorkoutStreak($_SESSION['user_id']);
        
        include __DIR__ . '/../views/dashboard.php';
    }
}
?>