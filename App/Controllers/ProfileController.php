<?php
// controllers/ProfileController.php

namespace App\Controllers;
use App\Models\Progress;
use App\Models\User;
class ProfileController {
    private $userModel;
    private $progressModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        $this->userModel = new User();
        $this->progressModel = new Progress();
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        include __DIR__ . '/../views/profile/index.php';
        
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $height = $_POST['height'];
            $weight = $_POST['weight'];
            $target_weight = isset($_POST['target_weight']) ? $_POST['target_weight'] : null;
            
            // Calculate BMI
            $heightInMeters = $height / 100;
            $bmi = $weight / ($heightInMeters * $heightInMeters);
            
            // Determine BMI category
            if ($bmi < 18.5) {
                $category = 'Kekurangan Berat Badan';
            } elseif ($bmi >= 18.5 && $bmi < 25) {
                $category = 'Ideal';
            } elseif ($bmi >= 25 && $bmi < 30) {
                $category = 'Kelebihan Berat Badan';
            } else {
                $category = 'Obesitas';
            }
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'height' => $height,
                'weight' => $weight,
                'bmi_category' => $category,
                'target_weight' => $target_weight
            ];
            
            if ($this->userModel->updateProfile($data)) {
                // Save to progress
                $this->progressModel->addProgress($_SESSION['user_id'], $weight, $bmi);
                
                $_SESSION['success'] = 'Profile berhasil diupdate!';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate profile.';
            }
            
            header('Location: index.php?page=profile');
            exit();
        }
    }
}
?>