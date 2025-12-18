<?php
namespace App\Controllers;

use App\Models\Workout;
use App\Models\Food;
use App\Controllers\WorkoutHistoryController;

class DashboardController {

    private $workoutModel;
    private $foodModel;
    private $workoutHistoryController;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->workoutModel = new Workout();
        $this->foodModel = new Food();
        $this->workoutHistoryController = new WorkoutHistoryController();
    }

    public function index() {
        $userId = $_SESSION['user_id'];

        $workoutCategories = $this->workoutModel->getCategories();

        $recentWorkouts = $this->workoutHistoryController
            ->getRecentForDashboard($userId);

        $todayCalories = $this->foodModel
            ->getTodayCalories($userId);

        $todayCaloriesBurned = $this->workoutHistoryController
            ->getTodayCaloriesBurned($userId);

        $workoutStats = $this->workoutHistoryController
            ->getStats($userId);

        $workoutStreak = $this->workoutHistoryController
            ->getStreak($userId);

        include __DIR__ . '/../views/dashboard.php';
    }
}
