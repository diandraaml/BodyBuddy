<?php
session_start();

// Import namespaces
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Controllers\WorkoutController;
use App\Controllers\ProfileController;
use App\Controllers\ConsultationController;
use App\Controllers\ProgressController;

// Autoloader PSR-4
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/App/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// ---------------------------
//   FIXED ROUTING
// ---------------------------

// Page & action dari URL
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Routing utama
switch ($page) {
    case 'home':
        $controller = new HomeController();
        break;

    case 'auth':
        $controller = new AuthController();
        break;

    case 'dashboard':
        $controller = new DashboardController();
        break;

    case 'workout':
        // Tentukan controller berdasarkan action
        if ($action == 'detail') {
            $controller = new App\Controllers\WorkoutDetailController();
            $action = 'show'; // Method name
        } elseif ($action == 'complete') {
            $controller = new App\Controllers\WorkoutDetailController();
        } elseif ($action == 'create' || $action == 'store') {
            $controller = new App\Controllers\WorkoutCreateController();
        } else {
            $controller = new App\Controllers\WorkoutListController();
        }
        break;

    // ROUTING UNTUK MEMBER FOOD (CASE TERPISAH!)
    case 'member-food':
        require_once 'App/Models/Food.php';
        require_once 'App/Models/FoodHistoryLog.php';
        require_once 'App/Controllers/MemberFoodController.php';
        
        $controller = new App\Controllers\MemberFoodController();
        
        switch ($action) {
            case 'add':
                $controller->add();
                exit();
            case 'delete':
                $controller->delete();
                exit();
            case 'history':
                $controller->history();
                exit();
            default:
                $controller->index();
                exit();
        }
        break;

    // ROUTING UNTUK TRAINER FOOD (CASE TERPISAH!)
    case 'trainer-food':
        require_once 'App/Models/Food.php';
        require_once 'App/Controllers/TrainerFoodController.php';
        
        $controller = new App\Controllers\TrainerFoodController();
        
        switch ($action) {
            case 'create':
                $controller->create();
                exit();
            case 'store':
                $controller->store();
                exit();
            case 'edit':
                $controller->edit();
                exit();
            case 'update':
                $controller->update();
                exit();
            case 'delete':
                $controller->delete();
                exit();
            default:
                // Show trainer list
                $foodModel = new App\Models\Food();
                $foods = $foodModel->getAllFoods();
                include 'App/views/food/trainer_list.php';
                exit();
        }
        break;

    // BACKWARD COMPATIBILITY - Redirect old 'food' page
    case 'food':
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'trainer') {
            header('Location: index.php?page=trainer-food');
        } else {
            header('Location: index.php?page=member-food');
        }
        exit();
        break;
    
    case 'profile':
        $controller = new ProfileController();
        break;

    case 'consultation':
        $controller = new ConsultationController();
        break;

    case 'progress':
        $controller = new ProgressController();
        break;

    default:
        $controller = new HomeController();
        break;
}

// Eksekusi action (hanya jika controller di-set dan belum exit)
if (isset($controller) && method_exists($controller, $action)) {
    $controller->$action();
} elseif (isset($controller)) {
    $controller->index();
}
?>