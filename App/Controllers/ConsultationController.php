<?php
namespace App\Controllers;

use App\Models\Consultation;
use App\Models\User;
use App\Models\PaymentMethod;

class ConsultationController {
    private $consultationModel;
    private $userModel;
    private $paymentModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth');
            exit();
        }
        $this->consultationModel = new Consultation();
        $this->userModel = new User();
        $this->paymentModel = new PaymentMethod();
    }

    public function index() {
        if ($_SESSION['role'] === 'member') {
            // Ambil semua trainer dengan info lengkap
            $trainers = $this->userModel->getAllTrainersWithDetails();
            
            // Ambil booking consultations member
            $myBookings = $this->consultationModel->getBookingsByMember($_SESSION['user_id']);
            
            include __DIR__ . '/../views/consultation/member.php';
        } else {
            // Untuk trainer - ambil semua booking yang ditujukan ke trainer ini
            $bookings = $this->consultationModel->getBookingsByTrainer($_SESSION['user_id']);
            
            include __DIR__ . '/../views/consultation/trainer.php';
        }
    }

    // Member booking konsultasi
    public function book() {
        if ($_SESSION['role'] !== 'member' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=consultation');
            exit();
        }
        
        $data = [
            'member_id' => $_SESSION['user_id'],
            'trainer_id' => $_POST['trainer_id'],
            'topic' => $_POST['topic'],
            'message' => $_POST['message'],
            'preferred_time' => !empty($_POST['preferred_time']) ? $_POST['preferred_time'] : null,
            'status' => 'pending'
        ];
        
        if ($this->consultationModel->createBooking($data)) {
            $_SESSION['success'] = 'Booking konsultasi berhasil! Silakan lakukan pembayaran untuk melanjutkan.';
        } else {
            $_SESSION['error'] = 'Gagal mengirim booking konsultasi.';
        }
        
        header('Location: index.php?page=consultation');
        exit();
    }

    // Get payment methods via AJAX
    public function get_payment_methods() {
        if (!isset($_GET['trainer_id'])) {
            echo json_encode([]);
            exit();
        }
        
        $trainerId = $_GET['trainer_id'];
        $methods = $this->paymentModel->getByTrainer($trainerId);
        
        header('Content-Type: application/json');
        echo json_encode($methods);
        exit();
    }

    // Member upload payment proof
    public function upload_payment() {
        if ($_SESSION['role'] !== 'member' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=consultation');
            exit();
        }
        
        $bookingId = $_POST['booking_id'];
        $paymentMethod = $_POST['payment_method'];
        
        // Validasi booking milik member ini
        $booking = $this->consultationModel->getBookingById($bookingId);
        
        if (!$booking || $booking['member_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Booking tidak valid.';
            header('Location: index.php?page=consultation');
            exit();
        }
        
        // Handle file upload
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'App/uploads/payments/';
            
            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                $_SESSION['error'] = 'Format file tidak valid. Gunakan JPG atau PNG.';
                header('Location: index.php?page=consultation');
                exit();
            }
            
            // Check file size (max 2MB)
            if ($_FILES['payment_proof']['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = 'Ukuran file terlalu besar. Maksimal 2MB.';
                header('Location: index.php?page=consultation');
                exit();
            }
            
            $fileName = 'payment_' . $bookingId . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $filePath)) {
                // Update booking dengan payment info
                $paymentData = [
                    'booking_id' => $bookingId,
                    'payment_method' => $paymentMethod,
                    'payment_proof' => $filePath,
                    'payment_status' => 'pending'
                ];
                
                if ($this->consultationModel->updatePaymentProof($paymentData)) {
                    $_SESSION['success'] = 'Bukti pembayaran berhasil diupload! Menunggu verifikasi trainer.';
                } else {
                    $_SESSION['error'] = 'Gagal menyimpan data pembayaran.';
                }
            } else {
                $_SESSION['error'] = 'Gagal mengupload file.';
            }
        } else {
            $_SESSION['error'] = 'File bukti pembayaran tidak valid.';
        }
        
        header('Location: index.php?page=consultation');
        exit();
    }

    // Trainer verify payment
    public function verify_payment() {
        if ($_SESSION['role'] !== 'trainer' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=consultation');
            exit();
        }
        
        $bookingId = $_POST['booking_id'];
        $trainerNotes = $_POST['trainer_notes'];
        $actionType = $_POST['action_type']; // 'approve' or 'reject'
        
        // Validasi booking ditujukan ke trainer ini
        $booking = $this->consultationModel->getBookingById($bookingId);
        
        if (!$booking || $booking['trainer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Booking tidak valid.';
            header('Location: index.php?page=consultation');
            exit();
        }
        
        if ($actionType === 'approve') {
            // Approve payment dan confirm booking
            if ($this->consultationModel->approvePayment($bookingId, $trainerNotes)) {
                $_SESSION['success'] = 'Pembayaran diverifikasi! Booking dikonfirmasi. Member dapat menghubungi Anda via WhatsApp.';
            } else {
                $_SESSION['error'] = 'Gagal memverifikasi pembayaran.';
            }
        } else {
            // Reject payment
            if ($this->consultationModel->rejectPayment($bookingId, $trainerNotes)) {
                $_SESSION['success'] = 'Pembayaran ditolak. Member akan menerima notifikasi.';
            } else {
                $_SESSION['error'] = 'Gagal menolak pembayaran.';
            }
        }
        
        header('Location: index.php?page=consultation');
        exit();
    }

    // Trainer confirm booking (deprecated - now handled by verify_payment)
    public function confirm() {
        if ($_SESSION['role'] !== 'trainer' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=consultation');
            exit();
        }
        
        $bookingId = $_POST['booking_id'];
        $trainerNotes = $_POST['trainer_notes'];
        
        // Update status menjadi confirmed dan simpan notes
        if ($this->consultationModel->confirmBooking($bookingId, $trainerNotes)) {
            $_SESSION['success'] = 'Booking berhasil dikonfirmasi! Member dapat menghubungi Anda via WhatsApp.';
        } else {
            $_SESSION['error'] = 'Gagal mengkonfirmasi booking.';
        }
        
        header('Location: index.php?page=consultation');
        exit();
    }

    // Trainer tolak booking
    public function reject() {
        if ($_SESSION['role'] !== 'trainer' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=consultation');
            exit();
        }
        
        $bookingId = $_POST['booking_id'];
        $rejectReason = $_POST['reject_reason'];
        
        // Update status menjadi rejected dan simpan alasan
        if ($this->consultationModel->rejectBooking($bookingId, $rejectReason)) {
            $_SESSION['success'] = 'Booking telah ditolak. Member akan menerima notifikasi.';
        } else {
            $_SESSION['error'] = 'Gagal menolak booking.';
        }
        
        header('Location: index.php?page=consultation');
        exit();
    }

    // Trainer tandai konsultasi selesai
    public function complete() {
        if ($_SESSION['role'] !== 'trainer' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=consultation');
            exit();
        }
        
        $bookingId = $_POST['booking_id'];
        
        // Update status menjadi completed
        if ($this->consultationModel->completeBooking($bookingId)) {
            $_SESSION['success'] = 'Konsultasi berhasil ditandai sebagai selesai.';
        } else {
            $_SESSION['error'] = 'Gagal menandai konsultasi selesai.';
        }
        
        header('Location: index.php?page=consultation');
        exit();
    }

    // Member batal booking (opsional)
    public function cancel() {
        if ($_SESSION['role'] !== 'member' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=consultation');
            exit();
        }
        
        $bookingId = $_POST['booking_id'];
        
        // Cek apakah booking milik member ini
        $booking = $this->consultationModel->getBookingById($bookingId);
        
        if ($booking && $booking['member_id'] == $_SESSION['user_id']) {
            if ($this->consultationModel->cancelBooking($bookingId)) {
                $_SESSION['success'] = 'Booking berhasil dibatalkan.';
            } else {
                $_SESSION['error'] = 'Gagal membatalkan booking.';
            }
        } else {
            $_SESSION['error'] = 'Booking tidak ditemukan atau bukan milik Anda.';
        }
        
        header('Location: index.php?page=consultation');
        exit();
    }
}
?>