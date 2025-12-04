<?php
// App/Controllers/WorkoutListController.php
namespace App\Controllers;

use App\Models\Workout;

class WorkoutListController {
    private $workoutModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        $this->workoutModel = new Workout();
    }

    public function index() {
        $category = isset($_GET['category']) ? $_GET['category'] : 'all';
        $workouts = $this->workoutModel->getWorkoutsByCategory($category);
        $categories = $this->workoutModel->getCategories();
        
        include __DIR__ . '/../views/workout/list.php';
    }
}
?>