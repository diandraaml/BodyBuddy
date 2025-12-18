<?php
namespace App\Controllers;

use App\Models\Consultation;

class TrainerViewBookingController {
    private $consultationModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'trainer') {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->consultationModel = new Consultation();
    }

    // Menampilkan daftar booking konsultasi
    public function index() {
        $bookings = $this->consultationModel
            ->getBookingsByTrainer($_SESSION['user_id']);

        include __DIR__ . '/../views/consultation/trainer.php';
    }
}
