<?php
session_start();

// ===========================
// IMPORT CONTROLLERS
// ===========================
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Controllers\ProfileController;
use App\Controllers\ConsultationListController;
use App\Controllers\ConsultationBookingController;
use App\Controllers\ConsultationPaymentController;
use App\Controllers\TrainerViewBookingController;
use App\Controllers\TrainerProcessBookingController;
use App\Controllers\TrainerDeleteFoodController;
use App\Controllers\TrainerCreateFoodController;



use App\Controllers\ProgressController;

// ===========================
// PSR-4 AUTOLOADER
// ===========================
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

// ===========================
// ROUTING
// ===========================
$page   = $_GET['page']   ?? 'home';
$action = $_GET['action'] ?? 'index';

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

    // ===========================
    // WORKOUT
    // ===========================
    case 'workout':
        if ($action === 'detail') {
            $controller = new App\Controllers\WorkoutDetailController();
            $action = 'show';
        } elseif ($action === 'complete') {
            $controller = new App\Controllers\WorkoutDetailController();
        } elseif ($action === 'create' || $action === 'store') {
            $controller = new App\Controllers\WorkoutCreateController();
        } elseif ($action === 'delete') {
            $controller = new App\Controllers\WorkoutDeleteController();
        } else {
            $controller = new App\Controllers\WorkoutListController();
        }
        break;

    // ===========================
    // MEMBER FOOD (DIPISAH)
    // ===========================
    case 'member-food':
        $controller = new App\Controllers\MemberFoodListController();
        $controller->index();
        exit();

    case 'member-food-log':
        $controller = new App\Controllers\MemberFoodLogController();

        if ($action === 'add') {
            $controller->add();
        } elseif ($action === 'delete') {
            $controller->delete();
        }
        exit();

    case 'member-food-history':
        $controller = new App\Controllers\MemberFoodProgressController();
        $controller->history();
        exit();

    // ===========================
    // TRAINER FOOD
    // ===========================
    case 'trainer-food':
        $controller = new App\Controllers\TrainerFoodController();

        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'store':
                $controller->store();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'update':
                $controller->update();
                break;
            case 'delete':
                $controller->delete();
                break;
            default:
                $controller->index();
                break;
        }
        exit();

    // ===========================
    // BACKWARD COMPATIBILITY
    // ===========================
    case 'food':
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'trainer') {
            header('Location: index.php?page=trainer-food');
        } else {
            header('Location: index.php?page=member-food');
        }
        exit();
    
    case 'food-create':
    (new CreateFoodController())->create();
    break;

    case 'food-store':
        (new CreateFoodController())->store();
        break;

    case 'food-edit':
        (new CreateFoodController())->edit();
        break;

    case 'food-update':
        (new CreateFoodController())->update();
        break;

    case 'food-delete':
        (new TrainerDeleteFoodController())->delete();
        break;

    case 'profile':
        $controller = new ProfileController();
        break;

     // ================= MEMBER =================
    case 'consultation':
        $controller = new ConsultationListController();
        $controller->index();
        break;

    case 'consultation-book':
        $controller = new ConsultationBookingController();
        $controller->book();
        break;

    case 'consultation-payment':
        $controller = new ConsultationPaymentController();
        $controller->upload();
        break;

    // ================= TRAINER =================
    case 'trainer-bookings':
        $controller = new TrainerViewBookingController();
        $controller->index();
        break;

    case 'trainer-process-booking':
        $controller = new TrainerProcessBookingController();
        $controller->process();
        break;


    case 'progress':
        $controller = new ProgressController();
        break;

    default:
        $controller = new HomeController();
        break;
}

// ===========================
// EXECUTE CONTROLLER
// ===========================
if (isset($controller) && method_exists($controller, $action)) {
    $controller->$action();
} elseif (isset($controller)) {
    $controller->index();
}
