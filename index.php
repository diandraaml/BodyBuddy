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
use App\Controllers\FoodController;

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
        $controller = new WorkoutController();
        break;

    case 'food':
        $controller = new FoodController();
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

// Eksekusi action
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->index();
}
?>

