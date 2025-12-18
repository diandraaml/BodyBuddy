<?php
namespace App\Controllers;

use App\Models\Consultation;

class TrainerProcessBookingController {
    private $consultationModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'trainer') {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->consultationModel = new Consultation();
    }

    // Verifikasi pembayaran (approve / reject)
    public function verifyPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=trainer-booking');
            exit();
        }

        $bookingId = $_POST['booking_id'];
        $notes     = $_POST['trainer_notes'];
        $action    = $_POST['action_type']; // approve / reject

        if ($action === 'approve') {
            $this->consultationModel->approvePayment($bookingId, $notes);
            $_SESSION['success'] = 'Pembayaran disetujui.';
        } else {
            $this->consultationModel->rejectPayment($bookingId, $notes);
            $_SESSION['success'] = 'Pembayaran ditolak.';
        }

        header('Location: index.php?page=trainer-booking');
        exit();
    }

    // Tolak booking sebelum pembayaran
    public function rejectBooking() {
        $bookingId = $_POST['booking_id'];
        $reason    = $_POST['reject_reason'];

        $this->consultationModel->rejectBooking($bookingId, $reason);
        $_SESSION['success'] = 'Booking ditolak.';
        header('Location: index.php?page=trainer-booking');
        exit();
    }

    // Tandai konsultasi selesai
    public function completeBooking() {
        $bookingId = $_POST['booking_id'];

        $this->consultationModel->completeBooking($bookingId);
        $_SESSION['success'] = 'Konsultasi ditandai selesai.';
        header('Location: index.php?page=trainer-booking');
        exit();
    }
}
