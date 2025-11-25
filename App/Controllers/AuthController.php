<?php

namespace App\Controllers;
use App\Models\User; 

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->login($username, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                
                header('Location: index.php?page=dashboard');
                exit();
            } else {
                $_SESSION['error'] = 'Username atau password salah!';
                header('Location: index.php?page=auth');
                exit();
            }
        }
    }

    public function register() {
        include __DIR__ . '/../views/auth/register.php';

    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'email' => $_POST['email'],
                'full_name' => $_POST['full_name'],
                'role' => $_POST['role']
            ];

            if ($this->userModel->register($data)) {
                $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
                header('Location: index.php?page=auth');
                exit();
            } else {
                $_SESSION['error'] = 'Registrasi gagal! Username atau email sudah digunakan.';
                header('Location: index.php?page=auth&action=register');
                exit();
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?page=home');
        exit();
    }
}
?>