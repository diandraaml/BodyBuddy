<?php
namespace App\Controllers;
use App\Models\Food;
// controllers/FoodController.php
class FoodController {
    private $foodModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        $this->foodModel = new Food();
    }

    public function index() {
        $foods = $this->foodModel->getAllFoods();
        $userFoods = $this->foodModel->getUserFoodsToday($_SESSION['user_id']);
        $totalCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);
        
        include __DIR__ . '/../views/food/list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'food_id' => $_POST['food_id'],
                'quantity' => $_POST['quantity']
            ];
            
            if ($this->foodModel->addUserFood($data)) {
                $_SESSION['success'] = 'Makanan berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan makanan.';
            }
            
            header('Location: index.php?page=food');
            exit();
        }
    }

    public function create() {
        if ($_SESSION['role'] !== 'trainer') {
            header('Location: index.php?page=dashboard');
            exit();
        }

        $foods = $this->foodModel->getAllFoods();
        $totalCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);
        $userFoods = $this->foodModel->getUserFoodsToday($_SESSION['user_id']);

        include __DIR__ . '/../views/food/create.php';
        
    }


    public function store() {
        if ($_SESSION['role'] !== 'trainer' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=dashboard');
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

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            
            if ($this->foodModel->deleteUserFood($id, $_SESSION['user_id'])) {
                $_SESSION['success'] = 'Makanan berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus makanan.';
            }
            
            header('Location: index.php?page=food');
            exit();
        }
    }
}
?>