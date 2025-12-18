<?php
namespace App\Controllers;

use App\Models\Food;

class MemberFoodListController {
    private $foodModel;
    private $dailyCalorieLimit = 2000;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
            header('Location: index.php?page=dashboard');
            exit();
        }
        $this->foodModel = new Food();
    }

    public function index() {
        $foods = $this->foodModel->getAllFoods();
        $userFoods = $this->foodModel->getUserFoodsToday($_SESSION['user_id']);
        $totalCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);

        $isOverLimit = $totalCalories >= $this->dailyCalorieLimit;

        include __DIR__ . '/../views/food/member_list.php';
    }
}
