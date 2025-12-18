<?php
namespace App\Controllers;

use App\Models\Food;

class TrainerDeleteFoodController {
    private $foodModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'trainer') {
            header('Location: index.php?page=dashboard');
            exit();
        }
        $this->foodModel = new Food();
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=food');
            exit();
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID makanan tidak valid.';
            header('Location: index.php?page=food');
            exit();
        }

        if ($this->foodModel->deleteFood($id)) {
            $_SESSION['success'] = 'Makanan berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus makanan.';
        }

        header('Location: index.php?page=food');
        exit();
    }
}
