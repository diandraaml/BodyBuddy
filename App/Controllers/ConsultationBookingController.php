<?php
namespace App\Controllers;

use App\Models\Consultation;

class ConsultationBookingController {

    private $consultationModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->consultationModel = new Consultation();
    }

    public function book() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $data = [
            'member_id' => $_SESSION['user_id'],
            'trainer_id' => $_POST['trainer_id'],
            'topic' => $_POST['topic'],
            'message' => $_POST['message'],
            'preferred_time' => $_POST['preferred_time'] ?? null,
            'status' => 'pending'
        ];

        if ($this->consultationModel->createBooking($data)) {
            $_SESSION['success'] = 'Booking konsultasi berhasil!';
        } else {
            $_SESSION['error'] = 'Gagal membuat booking.';
        }

        header('Location: index.php?page=consultation');
        exit();
    }

}
