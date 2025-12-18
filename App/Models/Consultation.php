<?php
namespace App\Models;

use App\Config\Database;

class Consultation {
    private $conn;
    private $table = 'consultations';

    private $id;
    private $member_id;
    private $trainer_id;
    private $message;
    private $response;
    private $status;
    private $created_at;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); 
    }

    // =============================
    // MEMBER - GET BOOKINGS
    // =============================
    public function getBookingsByMember($memberId) {

        $query = "SELECT 
                    c.*,
                    t.full_name AS trainer_name,
                    t.email AS trainer_email,
                    t.phone AS trainer_phone,
                    t.whatsapp AS trainer_whatsapp,
                    t.photo AS trainer_photo,
                    t.specialization,
                    t.rating
                FROM consultations c
                JOIN users t ON c.trainer_id = t.id
                WHERE c.member_id = ?
                ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $memberId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // =============================
    // GET BOOKINGS BY TRAINER
    // =============================
    public function getBookingsByTrainer($trainerId) {

        $query = "SELECT 
                    c.*,
                    m.full_name AS member_name,
                    m.email AS member_email,
                    m.phone AS member_phone,
                    m.whatsapp AS member_whatsapp,
                    m.photo AS member_photo,
                    m.height AS member_height,
                    m.weight AS member_weight,
                    m.fitness_goal AS member_goal,
                    m.fitness_level AS member_level
                FROM consultations c
                JOIN users m ON c.member_id = m.id
                WHERE c.trainer_id = ?
                ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $trainerId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // =============================
    // GET BOOKING BY ID
    // =============================
    public function getBookingById($bookingId) {

        $query = "SELECT * FROM consultations WHERE id = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // =============================
    // MEMBER CREATE BOOKING
    // =============================
    public function createBooking($data) {

        $query = "INSERT INTO consultations
                    (member_id, trainer_id, topic, message, preferred_time, status, created_at)
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "iissss",
            $data['member_id'],
            $data['trainer_id'],
            $data['topic'],
            $data['message'],
            $data['preferred_time'],
            $data['status']
        );

        return $stmt->execute();
    }

    // =============================
    // UPDATE PAYMENT PROOF
    // =============================
    public function updatePaymentProof($data) {

        $query = "UPDATE consultations SET 
                    payment_method = ?,
                    payment_proof = ?,
                    payment_status = ?,
                    payment_date = NOW(),
                    updated_at = NOW()
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "sssi",
            $data['payment_method'],
            $data['payment_proof'],
            $data['payment_status'],
            $data['booking_id']
        );

        return $stmt->execute();
    }

    // =============================
    // APPROVE PAYMENT
    // =============================
    public function approvePayment($bookingId, $trainerNotes) {

        $query = "UPDATE consultations SET 
                    payment_status = 'paid',
                    payment_verified_at = NOW(),
                    status = 'confirmed',
                    trainer_notes = ?,
                    updated_at = NOW()
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $trainerNotes, $bookingId);

        return $stmt->execute();
    }

    // =============================
    // REJECT PAYMENT
    // =============================
    public function rejectPayment($bookingId, $rejectReason) {

        $query = "UPDATE consultations SET 
                    payment_status = 'failed',
                    status = 'rejected',
                    trainer_notes = ?,
                    updated_at = NOW()
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $rejectReason, $bookingId);

        return $stmt->execute();
    }

    // =============================
    // CONFIRM BOOKING (NO PAYMENT)
    // =============================
    public function confirmBooking($bookingId, $trainerNotes) {

        $query = "UPDATE consultations SET 
                    status = 'confirmed',
                    trainer_notes = ?,
                    updated_at = NOW()
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $trainerNotes, $bookingId);

        return $stmt->execute();
    }

    // =============================
    // REJECT BOOKING
    // =============================
    public function rejectBooking($bookingId, $rejectReason) {

        $query = "UPDATE consultations SET 
                    status = 'rejected',
                    trainer_notes = ?,
                    updated_at = NOW()
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $rejectReason, $bookingId);

        return $stmt->execute();
    }

    // =============================
    // COMPLETE BOOKING
    // =============================
    public function completeBooking($bookingId) {

        $query = "UPDATE consultations SET 
                    status = 'completed',
                    updated_at = NOW()
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $bookingId);

        return $stmt->execute();
    }

    // =============================
    // CANCEL BOOKING
    // =============================
    public function cancelBooking($bookingId) {

        $query = "UPDATE consultations SET 
                    status = 'cancelled',
                    updated_at = NOW()
                WHERE id = ?
                AND status = 'pending'";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $bookingId);

        return $stmt->execute();
    }
}
?>
