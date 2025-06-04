<?php
// controllers/AuthController.php
// Controlador para autenticação de usuários

class AuthController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        require_once 'models/User.php';
        $this->user = new User($db);
    }

    // Exibir formulário de login
    public function showLogin() {
        require_once 'views/auth/login.php';
    }

    // Processar login
    public function loginUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $errors = [];
            if (empty($email)) $errors[] = "Email é obrigatório";
            if (empty($password)) $errors[] = "Senha é obrigatória";

            if (!empty($errors)) {
                header("Location: index.php?page=login&error=" . urlencode(implode(", ", $errors)));
                exit;
            }

            $user = $this->user->authenticate($email, $password);
            if ($user) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                header("Location: index.php?page=user_dashboard");
                exit;
            } else {
                header("Location: index.php?page=login&error=Email ou senha inválidos");
                exit;
            }
        } else {
            header("Location: index.php?page=login");
            exit;
        }
    }

    // Processar logout
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?page=login&success=Logout realizado com sucesso");
        exit;
    }

    // Verificar se usuário está logado
    public static function requireLogin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login&error=Faça login para continuar");
            exit;
        }
    }
}
?>
