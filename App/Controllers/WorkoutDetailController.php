<?php
// App/Controllers/WorkoutDetailController.php
namespace App\Controllers;

use App\Models\Workout;
use App\Models\WorkoutHistory;

class WorkoutDetailController {
    private $workoutModel;
    private $historyModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        $this->workoutModel = new Workout();
        $this->historyModel = new WorkoutHistory();
    }

    public function show() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $workout = $this->workoutModel->getWorkoutById($id);
        
        if (!$workout) {
            $_SESSION['error'] = 'Workout tidak ditemukan!';
            header('Location: index.php?page=workout');
            exit();
        }
        
        // Get workout history for this user and workout
        $history = $this->historyModel->getWorkoutHistory($_SESSION['user_id'], $id);
        
        include __DIR__ . '/../views/workout/detail.php';
    }

    public function complete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=workout');
            exit();
        }

        $workoutId = $_POST['workout_id'];
        $setsCompleted = $_POST['sets_completed'];
        $userId = $_SESSION['user_id'];

        // Get workout details
        $workout = $this->workoutModel->getWorkoutById($workoutId);
        
        if (!$workout) {
            $_SESSION['error'] = 'Workout tidak ditemukan!';
            header('Location: index.php?page=workout');
            exit();
        }

        // Calculate total calories
        $totalCalories = $workout['calories_burned'] * $setsCompleted;

        // Save to workout history
        $data = [
            'user_id' => $userId,
            'workout_id' => $workoutId,
            'sets_completed' => $setsCompleted,
            'total_calories_burned' => $totalCalories,
            'duration_minutes' => $workout['duration_minutes']
        ];

        if ($this->historyModel->addWorkoutHistory($data)) {
            $_SESSION['success'] = 'Workout berhasil diselesaikan! 🎉 Total kalori terbakar: ' . $totalCalories . ' kal';
        } else {
            $_SESSION['error'] = 'Gagal menyimpan workout. Silakan coba lagi.';
        }

        header('Location: index.php?page=workout&action=detail&id=' . $workoutId);
        exit();
    }
}
?>