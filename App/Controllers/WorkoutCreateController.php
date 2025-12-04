<?php
// App/Controllers/WorkoutCreateController.php
namespace App\Controllers;

use App\Models\Workout;

class WorkoutCreateController {
    private $workoutModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        
        // Only trainers can create workouts
        if ($_SESSION['role'] !== 'trainer') {
            $_SESSION['error'] = 'Hanya trainer yang dapat membuat workout!';
            header('Location: index.php?page=dashboard');
            exit();
        }
        
        $this->workoutModel = new Workout();
    }

    public function create() {
        $categories = $this->workoutModel->getCategories();
        include __DIR__ . '/../views/workout/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=workout&action=create');
            exit();
        }

        // Validate video URL
        $videoUrl = trim($_POST['video_url']);
        
        if (empty($videoUrl)) {
            $_SESSION['error'] = 'Link video YouTube harus diisi!';
            header('Location: index.php?page=workout&action=create');
            exit();
        }

        // Check if it's a valid YouTube URL
        if (!$this->isValidYoutubeUrl($videoUrl)) {
            $_SESSION['error'] = 'Format link YouTube tidak valid! Gunakan format: https://www.youtube.com/watch?v=... atau https://youtu.be/...';
            header('Location: index.php?page=workout&action=create');
            exit();
        }

        // Prepare data
        $data = [
            'category_id' => $_POST['category_id'],
            'workout_name' => trim($_POST['workout_name']),
            'description' => trim($_POST['description']),
            'repetitions' => (int)$_POST['repetitions'],
            'duration_minutes' => (int)$_POST['duration_minutes'],
            'calories_burned' => (int)$_POST['calories_burned'],
            'video_url' => $videoUrl,
            'created_by' => $_SESSION['user_id']
        ];

        // Validate data
        if (empty($data['workout_name']) || empty($data['description'])) {
            $_SESSION['error'] = 'Nama workout dan deskripsi harus diisi!';
            header('Location: index.php?page=workout&action=create');
            exit();
        }

        if ($data['repetitions'] < 1 || $data['duration_minutes'] < 1 || $data['calories_burned'] < 1) {
            $_SESSION['error'] = 'Repetisi, durasi, dan kalori harus lebih dari 0!';
            header('Location: index.php?page=workout&action=create');
            exit();
        }

        // Save to database
        if ($this->workoutModel->createWorkout($data)) {
            $_SESSION['success'] = 'Workout "' . $data['workout_name'] . '" berhasil dibuat dengan video tutorial! 🎉';
        } else {
            $_SESSION['error'] = 'Gagal membuat workout. Silakan coba lagi.';
        }

        header('Location: index.php?page=workout');
        exit();
    }

    private function isValidYoutubeUrl($url) {
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtu\.be\/([^?]+)/',
            '/youtube\.com\/embed\/([^?]+)/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }
}
?>