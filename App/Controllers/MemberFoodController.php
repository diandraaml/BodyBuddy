<?php
namespace App\Controllers;
use App\Models\Food;
use App\Models\FoodHistoryLog;

class MemberFoodController {
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

    public function index() {
        $foods = $this->foodModel->getAllFoods();
        $userFoods = $this->foodModel->getUserFoodsToday($_SESSION['user_id']);
        $totalCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);
        
        // Cek apakah sudah melebihi limit
        $isOverLimit = $totalCalories >= $this->dailyCalorieLimit;
        
        include __DIR__ . '/../views/food/member_list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=member-food');
            exit();
        }

        // Cek total kalori saat ini
        $currentCalories = $this->foodModel->getTodayCalories($_SESSION['user_id']);
        $food = $this->foodModel->getFoodById($_POST['food_id']);
        $additionalCalories = $food['calories'] * $_POST['quantity'];
        
        // Validasi limit kalori
        if (($currentCalories + $additionalCalories) > $this->dailyCalorieLimit) {
            $_SESSION['error'] = 'Tidak bisa menambahkan! Sudah melebihi kalori harian (Max: ' . $this->dailyCalorieLimit . ' kal)';
            header('Location: index.php?page=member-food');
            exit();
        }

        $data = [
            'user_id' => $_SESSION['user_id'],
            'food_id' => $_POST['food_id'],
            'quantity' => $_POST['quantity']
        ];
        
        if ($this->foodModel->addUserFood($data)) {
            // Log ke history
            $logData = [
                'user_id' => $_SESSION['user_id'],
                'food_id' => $_POST['food_id'],
                'food_name' => $food['food_name'],
                'quantity' => $_POST['quantity'],
                'calories_per_unit' => $food['calories'],
                'total_calories' => $additionalCalories,
                'action_type' => 'add'
            ];
            $this->historyLogModel->logAction($logData);
            
            $_SESSION['success'] = 'Makanan berhasil ditambahkan!';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan makanan.';
        }
        
        header('Location: index.php?page=member-food');
        exit();
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=member-food');
            exit();
        }

        $id = $_POST['id'];
        
        // Ambil data makanan sebelum dihapus untuk log
        $userFood = $this->foodModel->getUserFoodById($id, $_SESSION['user_id']);
        
        if ($userFood && $this->foodModel->deleteUserFood($id, $_SESSION['user_id'])) {
            // Log ke history
            $logData = [
                'user_id' => $_SESSION['user_id'],
                'food_id' => $userFood['food_id'],
                'food_name' => $userFood['food_name'],
                'quantity' => $userFood['quantity'],
                'calories_per_unit' => $userFood['calories'],
                'total_calories' => $userFood['total_calories'],
                'action_type' => 'delete'
            ];
            $this->historyLogModel->logAction($logData);
            
            $_SESSION['success'] = 'Makanan berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus makanan.';
        }
        
        header('Location: index.php?page=member-food');
        exit();
    }

    public function history() {
        $page = isset($_GET['history_page']) ? (int)$_GET['history_page'] : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $dateFilter = $_GET['date_filter'] ?? null;
        
        $history = $this->historyLogModel->getUserHistory($_SESSION['user_id'], $perPage, $offset, $dateFilter);
        $totalRecords = $this->historyLogModel->getTotalHistoryCount($_SESSION['user_id'], $dateFilter);
        $totalPages = ceil($totalRecords / $perPage);

        // Statistik
        $stats = $this->historyLogModel->getUserStats($_SESSION['user_id']);
        
        include __DIR__ . '/../views/food/history.php';
    }

    public function getDailyLimit() {
        return $this->dailyCalorieLimit;
    }
}
?>