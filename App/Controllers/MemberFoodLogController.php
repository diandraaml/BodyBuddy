<?php
namespace App\Controllers;

use App\Models\Food;
use App\Models\FoodHistoryLog;

class MemberFoodLogController {
    private $foodModel;
    private $historyLogModel;
    private $dailyCalorieLimit = 2000;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
            header('Location: index.php?page=dashboard');
            exit();
        }

        $this->foodModel = new Food();
        $this->historyLogModel = new FoodHistoryLog();
    }

    public function add() {
        $currentCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);
        $food = $this->foodModel->getFoodById($_POST['food_id']);
        $additionalCalories = $food['calories'] * $_POST['quantity'];

        if (($currentCalories + $additionalCalories) > $this->dailyCalorieLimit) {
            $_SESSION['error'] = 'Melebihi batas kalori harian!';
            header('Location: index.php?page=member-food');
            exit();
        }

        $this->foodModel->addUserFood([
            'user_id' => $_SESSION['user_id'],
            'food_id' => $_POST['food_id'],
            'quantity' => $_POST['quantity']
        ]);

        $this->historyLogModel->logAction([
            'user_id' => $_SESSION['user_id'],
            'food_id' => $_POST['food_id'],
            'food_name' => $food['food_name'],
            'quantity' => $_POST['quantity'],
            'calories_per_unit' => $food['calories'],
            'total_calories' => $additionalCalories,
            'action_type' => 'add'
        ]);

        header('Location: index.php?page=member-food');
        exit();
    }

    public function delete() {
        $id = $_POST['id'];
        $userFood = $this->foodModel->getUserFoodById($id, $_SESSION['user_id']);

        if ($userFood) {
            $this->foodModel->deleteUserFood($id, $_SESSION['user_id']);

            $this->historyLogModel->logAction([
                'user_id' => $_SESSION['user_id'],
                'food_id' => $userFood['food_id'],
                'food_name' => $userFood['food_name'],
                'quantity' => $userFood['quantity'],
                'calories_per_unit' => $userFood['calories'],
                'total_calories' => $userFood['total_calories'],
                'action_type' => 'delete'
            ]);
        }

        header('Location: index.php?page=member-food');
        exit();
    }
}
