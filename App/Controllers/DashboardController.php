<?php
namespace App\Controllers;

use App\Models\Workout;
use App\Models\Food;

class DashboardController {
    private $workoutModel;
    private $foodModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->workoutModel = new Workout();
        $this->foodModel = new Food();
    }

    public function index() {
        $workoutCategories = $this->workoutModel->getCategories();
        $recentWorkouts = $this->workoutModel->getRecentWorkouts($_SESSION['user_id']);
        $todayCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);
        $todayCaloriesBurned = $this->workoutModel->getTodayCaloriesBurned($_SESSION['user_id']);
        
        include __DIR__ . '/../views/dashboard.php';
        
    }
}
