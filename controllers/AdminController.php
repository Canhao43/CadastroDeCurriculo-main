<?php
// controllers/AdminController.php
// Controlador para funcionalidades administrativas

require_once __DIR__ . '/AuthController.php';

class AdminController {
    private $db;
    private $curriculo;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        require_once __DIR__ . '/../models/Curriculo.php';
        require_once __DIR__ . '/../models/User.php';
        $this->curriculo = new Curriculo($db);
        $this->user = new User($db);
    }

    // Dashboard administrativo
    public function dashboard() {
        AuthController::requireAdmin();
        
        $curriculos = $this->curriculo->getAll();
        
        require_once 'views/admin/dashboard.php';
    }

    // Visualizar currículo específico
    public function viewResume() {
        AuthController::requireAdmin();
        
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=admin_dashboard&error=ID do currículo não fornecido");
            exit;
        }

        $id = $_GET['id'];
        $curriculo = $this->curriculo->getById($id);
        
        if (!$curriculo) {
            header("Location: index.php?page=admin_dashboard&error=Currículo não encontrado");
            exit;
        }
        
        require_once 'views/admin/view_resume.php';
    }

    // Deletar currículo (admin)
    public function deleteResume() {
        AuthController::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id'])) {
                header("Location: index.php?page=admin_dashboard&error=ID do currículo não fornecido");
                exit;
            }

            $id = $_POST['id'];
            $curriculoData = $this->curriculo->getById($id);
            
            if (!$curriculoData) {
                header("Location: index.php?page=admin_dashboard&error=Currículo não encontrado");
                exit;
            }

            // Deletar foto se existir
            if ($curriculoData['foto'] && file_exists($curriculoData['foto'])) {
                unlink($curriculoData['foto']);
            }

            if ($this->curriculo->deleteAdmin()) {
                header("Location: index.php?page=admin_dashboard&success=Currículo deletado com sucesso!");
                exit;
            } else {
                header("Location: index.php?page=admin_dashboard&error=Erro ao deletar currículo");
                exit;
            }
        }
    }

    // Listar todos os usuários
    public function listUsers() {
        AuthController::requireAdmin();
        
        $users = $this->user->listAll();
        
        require_once 'views/admin/users.php';
    }

    // Deletar usuário e seu currículo
    public function deleteUser() {
        AuthController::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id'])) {
                header("Location: index.php?page=admin_users&error=ID do usuário não fornecido");
                exit;
            }

            $userId = $_POST['id'];
            
            // Primeiro deletar o currículo do usuário se existir
            $curriculo = $this->curriculo->readByUser($userId);
            if ($curriculo) {
                $this->curriculo->id = $curriculo['id'];
                
                // Deletar foto se existir
                if ($curriculo['foto'] && file_exists($curriculo['foto'])) {
                    unlink($curriculo['foto']);
                }
                
                $this->curriculo->deleteAdmin();
            }

            // Então deletar o usuário
            if ($this->user->delete($userId)) {
                header("Location: index.php?page=admin_users&success=Usuário e currículo deletados com sucesso!");
                exit;
            } else {
                header("Location: index.php?page=admin_users&error=Erro ao deletar usuário");
                exit;
            }
        }
    }

    // Alterar papel do usuário (admin/user)
    public function updateUserRole() {
        AuthController::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id']) || !isset($_POST['role'])) {
                header("Location: index.php?page=admin_users&error=Dados incompletos");
                exit;
            }

            $userId = $_POST['id'];
            $role = $_POST['role'];

            if ($role !== 'admin' && $role !== 'user') {
                header("Location: index.php?page=admin_users&error=Papel inválido");
                exit;
            }

            if ($this->user->updateRole($userId, $role)) {
                header("Location: index.php?page=admin_users&success=Papel do usuário atualizado com sucesso!");
                exit;
            } else {
                header("Location: index.php?page=admin_users&error=Erro ao atualizar papel do usuário");
                exit;
            }
        }
    }

    // Estatísticas do sistema
    public function statistics() {
        AuthController::requireAdmin();
        
        $stats = [
            'total_users' => $this->user->countAll(),
            'total_resumes' => $this->curriculo->countAll(),
            'resumes_by_month' => $this->curriculo->countByMonth(),
            'users_by_month' => $this->user->countByMonth()
        ];
        
        require_once 'views/admin/statistics.php';
    }
}
?>
