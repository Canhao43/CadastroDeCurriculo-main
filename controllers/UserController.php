<?php
// controllers/UserController.php
// Controlador para funcionalidades do usuário

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Dashboard do usuário
    public function dashboard() {
        AuthController::requireLogin();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userName = $_SESSION['user_name'] ?? 'Usuário';

        require_once 'views/user/dashboard.php';
    }
}
?>
