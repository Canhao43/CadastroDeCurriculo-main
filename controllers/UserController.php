<?php
// controllers/UserController.php
// Controlador para funcionalidades do usuário

require_once __DIR__ . '/AuthController.php';

class UserController {
    private $db;
    private $curriculo;

    public function __construct($db) {
        $this->db = $db;
        require_once __DIR__ . '/../models/Curriculo.php';
        $this->curriculo = new Curriculo($db);
    }

    // Dashboard do usuário
    public function dashboard() {
        AuthController::requireLogin();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'];
        $userName = $_SESSION['user_name'] ?? 'Usuário';
        $curriculo = $this->curriculo->getByUser($userId);

        require_once 'views/user/dashboard.php';
    }
}
?>
