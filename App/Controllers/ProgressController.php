<?php
// controllers/ProgressController.php
namespace App\Controllers;
use App\Models\User; 

use App\Models\Progress;
class ProgressController {
    private $progressModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        $this->progressModel = new Progress();
        $this->userModel = new User();
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $progressData = $this->progressModel->getProgressByUser($_SESSION['user_id']);
        
        include __DIR__ . '/../views/progress/index.php';
        
    }
}
?>