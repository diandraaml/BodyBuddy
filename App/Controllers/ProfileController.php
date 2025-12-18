<?php
namespace App\Controllers;

use App\Models\Progress;
use App\Models\User;
use App\Controllers\BmiController;

class ProfileController {
    private $userModel;
    private $progressModel;
    private $bmiController;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->userModel = new User();
        $this->progressModel = new Progress();
        $this->bmiController = new BmiController();
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        include __DIR__ . '/../views/profile/index.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $height = $_POST['height'];
            $weight = $_POST['weight'];
            $target_weight = $_POST['target_weight'] ?? null;

            // HITUNG BMI (DELEGASI)
            $bmiResult = $this->bmiController->calculate($height, $weight);

            $data = [
                'user_id' => $_SESSION['user_id'],
                'height' => $height,
                'weight' => $weight,
                'bmi_category' => $bmiResult['category'],
                'target_weight' => $target_weight
            ];

            if ($this->userModel->updateProfile($data)) {

                $this->progressModel->addProgress(
                    $_SESSION['user_id'],
                    $weight,
                    $bmiResult['bmi']
                );

                $_SESSION['success'] = 'Profile berhasil diupdate!';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate profile.';
            }

            header('Location: index.php?page=profile');
            exit();
        }
    }
}
