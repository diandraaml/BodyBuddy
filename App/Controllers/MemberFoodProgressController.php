<?php
namespace App\Controllers;

use App\Models\FoodHistoryLog;

class MemberFoodProgressController {

    private $historyLogModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
            header('Location: index.php?page=dashboard');
            exit();
        }

        $this->historyLogModel = new FoodHistoryLog();
    }

    public function history() {
        $page = $_GET['history_page'] ?? 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $dateFilter = $_GET['date_filter'] ?? null;

        $history = $this->historyLogModel
            ->getUserHistory($_SESSION['user_id'], $perPage, $offset, $dateFilter);

        $stats = $this->historyLogModel
            ->getUserStats($_SESSION['user_id']);

        include __DIR__ . '/../views/food/history.php';
    }
}
