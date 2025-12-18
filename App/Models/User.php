<?php
namespace App\Models;

use App\Config\Database;

class User {
    private $conn;
    private $table = 'users';

    private $id;
    private $username;
    private $password;
    private $email;
    private $name;
    private $role;
    private $height;
    private $weight;
    private $bmi_category;
    private $target_weight;
    private $created_at;
    private $phone;
    private $date_of_birth;
    private $gender;


    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection(); // mysqli connection
    }

    public function login($username, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function register($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO users (username, password, email, full_name, role)
             VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "sssss",
            $data['username'],
            $data['password'],
            $data['email'],
            $data['full_name'],
            $data['role']
        );

        return $stmt->execute();
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProfile($data) {
        $stmt = $this->conn->prepare(
            "UPDATE users 
             SET height = ?, weight = ?, bmi_category = ?, target_weight = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ddssi",
            $data['height'],
            $data['weight'],
            $data['bmi_category'],
            $data['target_weight'],
            $data['user_id']
        );

        return $stmt->execute();
    }

    public function getAllTrainers() {
        $result = $this->conn->query(
            "SELECT id, full_name, email FROM users WHERE role = 'trainer'"
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* ============================================================
       ✅ METHOD BARU: getAllTrainersWithDetails()
       ============================================================ */
  public function getAllTrainersWithDetails() {
        $query = "SELECT 
                    id,
                    full_name,
                    email,
                    phone,
                    photo,
                    specialization,
                    expertise,
                    rating,
                    bio,
                    total_clients,
                    experience,
                    consultation_price
                FROM users
                WHERE role = 'trainer'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    }
?>
