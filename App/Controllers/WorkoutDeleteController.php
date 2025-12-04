<?php
// App/Controllers/WorkoutDeleteController.php
namespace App\Controllers;

use App\Models\Workout;
use App\Models\WorkoutHistory;

class WorkoutDeleteController {
    private $workoutModel;
    private $historyModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        
        // Only trainers can delete workouts
        if ($_SESSION['role'] !== 'trainer') {
            $_SESSION['error'] = 'Hanya trainer yang dapat menghapus workout!';
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $this->workoutModel = new Workout();
        $this->historyModel = new WorkoutHistory();
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=workout');
            exit();
        }

        $workoutId = $_POST['id'] ?? null;

        if (!$workoutId) {
            $_SESSION['error'] = 'ID workout tidak valid!';
            header('Location: index.php?page=workout');
            exit();
        }

        // Get workout details before deleting
        $workout = $this->workoutModel->getWorkoutById($workoutId);
        
        if (!$workout) {
            $_SESSION['error'] = 'Workout tidak ditemukan!';
            header('Location: index.php?page=workout');
            exit();
        }

        // Trainer can delete any workout (no ownership check needed)

        // Delete workout history first (foreign key constraint)
        $this->historyModel->deleteByWorkoutId($workoutId);

        // Delete the workout
        if ($this->workoutModel->deleteWorkout($workoutId)) {
            $_SESSION['success'] = 'Workout "' . $workout['workout_name'] . '" berhasil dihapus beserta semua history-nya! ✓';
        } else {
            $_SESSION['error'] = 'Gagal menghapus workout. Silakan coba lagi.';
        }

        header('Location: index.php?page=workout');
        exit();
    }
}
?>