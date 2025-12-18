<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Consultation;

class ConsultationListController {

    private $userModel;
    private $consultationModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->userModel = new User();
        $this->consultationModel = new Consultation();
    }

    public function index() {
        if ($_SESSION['role'] === 'member') {
            $trainers = $this->userModel->getAllTrainersWithDetails();
            $myBookings = $this->consultationModel
                              ->getBookingsByMember($_SESSION['user_id']);

            include __DIR__ . '/../views/consultation/member.php';
        } else {
            $bookings = $this->consultationModel
                             ->getBookingsByTrainer($_SESSION['user_id']);

            include __DIR__ . '/../views/consultation/trainer.php';
        }
    }
}
