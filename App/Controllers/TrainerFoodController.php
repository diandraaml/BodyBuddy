<?php
namespace App\Controllers;
use App\Models\Food;

class TrainerFoodController {
    private $foodModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'trainer') {
            header('Location: index.php?page=dashboard');
            exit();
        }
        $this->foodModel = new Food();
    }

    public function create() {
        include __DIR__ . '/../views/food/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=food');
            exit();
        }
        
        $data = [
            'food_name' => $_POST['food_name'],
            'calories' => $_POST['calories'],
            'protein' => $_POST['protein'],
            'carbs' => $_POST['carbs'],
            'fats' => $_POST['fats'],
            'description' => $_POST['description'],
            'created_by' => $_SESSION['user_id']
        ];
        
        if ($this->foodModel->createFood($data)) {
            $_SESSION['success'] = 'Makanan berhasil dibuat!';
        } else {
            $_SESSION['error'] = 'Gagal membuat makanan.';
        }
        
        header('Location: index.php?page=food');
        exit();
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=food');
            exit();
        }

        $food = $this->foodModel->getFoodById($id);
        if (!$food) {
            $_SESSION['error'] = 'Makanan tidak ditemukan.';
            header('Location: index.php?page=food');
            exit();
        }

        include __DIR__ . '/../views/food/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=food');
            exit();
        }

        $data = [
            'id' => $_POST['id'],
            'food_name' => $_POST['food_name'],
            'calories' => $_POST['calories'],
            'protein' => $_POST['protein'],
            'carbs' => $_POST['carbs'],
            'fats' => $_POST['fats'],
            'description' => $_POST['description']
        ];

        if ($this->foodModel->updateFood($data)) {
            $_SESSION['success'] = 'Makanan berhasil diupdate!';
        } else {
            $_SESSION['error'] = 'Gagal mengupdate makanan.';
        }

        header('Location: index.php?page=food');
        exit();
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
?>