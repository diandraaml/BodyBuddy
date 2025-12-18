<?php
namespace App\Controllers;

use App\Models\Food;

class CreateFoodController {
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
            'food_name'   => $_POST['food_name'],
            'calories'    => $_POST['calories'],
            'protein'     => $_POST['protein'],
            'carbs'       => $_POST['carbs'],
            'fats'        => $_POST['fats'],
            'description' => $_POST['description'],
            'created_by'  => $_SESSION['user_id']
        ];

        if ($this->foodModel->createFood($data)) {
            $_SESSION['success'] = 'Makanan berhasil dibuat!';
        } else {
            $_SESSION['error'] = 'Gagal membuat makanan.';
        }

        header('Location: index.php?page=food');
        exit();
    }


}
