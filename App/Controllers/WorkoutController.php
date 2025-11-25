<?php
// controllers/WorkoutController.php
namespace App\Controllers;

use App\Models\Workout;
class WorkoutController {
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

    public function detail() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $workout = $this->workoutModel->getWorkoutById($id);
        
        if (!$workout) {
            header('Location: index.php?page=workout');
            exit();
        }
        
        include __DIR__ . '/../views/workout/detail.php';
    }

    public function complete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'workout_id' => $_POST['workout_id'],
                'sets_completed' => $_POST['sets_completed']
            ];
            
            if ($this->workoutModel->completeWorkout($data)) {
                $_SESSION['success'] = 'Workout berhasil diselesaikan!';
            } else {
                $_SESSION['error'] = 'Gagal menyimpan workout.';
            }
            
            header('Location: index.php?page=workout&action=detail&id=' . $_POST['workout_id']);
            exit();
        }
    }

    public function create() {
        if ($_SESSION['role'] !== 'trainer') {
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $categories = $this->workoutModel->getCategories();
        include __DIR__ . '/../views/workout/create.php';
        
    }

    public function store() {
        if ($_SESSION['role'] !== 'trainer' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $data = [
            'category_id' => $_POST['category_id'],
            'workout_name' => $_POST['workout_name'],
            'description' => $_POST['description'],
            'repetitions' => $_POST['repetitions'],
            'duration_minutes' => $_POST['duration_minutes'],
            'calories_burned' => $_POST['calories_burned'],
            'created_by' => $_SESSION['user_id']
        ];
        
        if ($this->workoutModel->createWorkout($data)) {
            $_SESSION['success'] = 'Workout berhasil dibuat!';
        } else {
            $_SESSION['error'] = 'Gagal membuat workout.';
        }
        
        header('Location: index.php?page=workout');
        exit();
    }
}
?>