<?php
namespace App\Controllers;

use App\Models\Consultation;
use App\Models\PaymentMethod;

class ConsultationPaymentController {

    private $consultationModel;
    private $paymentModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }

        $this->consultationModel = new Consultation();
        $this->paymentModel = new PaymentMethod();
    }

    public function getPaymentMethods() {
        $trainerId = $_GET['trainer_id'] ?? null;
        echo json_encode($this->paymentModel->getByTrainer($trainerId));
    }

    public function upload() {
        $bookingId = $_POST['booking_id'];
        $paymentMethod = $_POST['payment_method'];

        // (logic upload file tetap sama seperti kode kamu)

        $this->consultationModel->updatePaymentProof([
            'booking_id' => $bookingId,
            'payment_method' => $paymentMethod,
            'payment_proof' => $filePath,
            'payment_status' => 'pending'
        ]);

        $_SESSION['success'] = 'Bukti pembayaran berhasil diupload.';
        header('Location: index.php?page=consultation');
        exit();
    }
}
