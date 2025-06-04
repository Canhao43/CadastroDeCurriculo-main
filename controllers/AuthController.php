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

        // Criar usuário admin automaticamente se não existir
        $this->createAdminIfNotExists();
    }

    private function createAdminIfNotExists() {
        $adminEmail = 'admin@example.com';
        if (!$this->user->emailExists($adminEmail)) {
            $nome = 'Admin';
            $password = 'admin123'; // Senha padrão, pode ser alterada depois
            $role = 'admin';
            $this->user->create($nome, $adminEmail, $password, $role);
        }
    }

    // Exibir formulário de registro
    public function showRegister() {
        require_once 'views/auth/register.php';
    }

    // Processar registro de usuário
    public function registerUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            // Validação
            $errors = [];
            if (empty($nome)) $errors[] = "Nome é obrigatório";
            if (empty($email)) $errors[] = "Email é obrigatório";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido";
            if (empty($password)) $errors[] = "Senha é obrigatória";
            if ($password !== $password_confirm) $errors[] = "As senhas não conferem";
            if (strlen($password) < 6) $errors[] = "A senha deve ter no mínimo 6 caracteres";

            if (empty($errors)) {
                if ($this->user->emailExists($email)) {
                    header("Location: index.php?page=register&error=Email já cadastrado");
                    exit;
                }

                if ($this->user->create($nome, $email, $password)) {
                    header("Location: index.php?page=login&success=Cadastro realizado com sucesso! Faça login para continuar.");
                    exit;
                } else {
                    header("Location: index.php?page=register&error=Erro ao criar usuário");
                    exit;
                }
            } else {
                header("Location: index.php?page=register&error=" . urlencode(implode(", ", $errors)));
                exit;
            }
        }
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

            // Validação
            $errors = [];
            if (empty($email)) $errors[] = "Email é obrigatório";
            if (empty($password)) $errors[] = "Senha é obrigatória";

            if (empty($errors)) {
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
                header("Location: index.php?page=login&error=" . urlencode(implode(", ", $errors)));
                exit;
            }
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

    // Verificar se usuário é admin
    public static function requireAdmin() {
        self::requireLogin();
        if ($_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?page=user_dashboard&error=Acesso não autorizado");
            exit;
        }
    }

    // Verificar se usuário tem permissão para acessar o currículo
    public static function checkResumeAccess($curriculoId, $userId) {
        global $db;
        $curriculo = new Curriculo($db);
        $curriculo->id = $curriculoId;

        if (!$curriculo->belongsToUser($userId) && $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?page=user_dashboard&error=Acesso não autorizado");
            exit;
        }
    }
}
?>
