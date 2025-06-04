<?php
// index.php - Front Controller / Router simples

session_start();

require_once __DIR__ . '/includes/db.php';

$page = $_GET['page'] ?? '';
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

global $pdo;

function loadController($name, $pdo) {
    $file = __DIR__ . "/controllers/{$name}Controller.php";
    if (file_exists($file)) {
        require_once $file;
        $className = $name . 'Controller';
        if (class_exists($className)) {
            return new $className($pdo);
        }
    }
    return null;
}

switch ($page) {
    case 'register':
        $auth = loadController('Auth', $pdo);
        $auth->showRegister();
        break;

    case 'login':
        $auth = loadController('Auth', $pdo);
        $auth->showLogin();
        break;

    case 'logout':
        $auth = loadController('Auth', $pdo);
        $auth->logout();
        break;

    case 'user_dashboard':
        $user = loadController('User', $pdo);
        $user->dashboard();
        break;

    case 'create_resume':
        $user = loadController('User', $pdo);
        $user->showCreate();
        break;

    case 'edit_resume':
        $user = loadController('User', $pdo);
        $user->showEdit();
        break;

    case 'admin_dashboard':
        $admin = loadController('Admin', $pdo);
        $admin->dashboard();
        break;

    case 'view_resume_admin':
        $admin = loadController('Admin', $pdo);
        $id = $_GET['id'] ?? null;
        if ($id) {
            $admin->viewResume($id);
        } else {
            header("Location: index.php?page=admin_dashboard&error=" . urlencode("ID do currículo não fornecido."));
        }
        break;

    default:
        // Ações POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($action) {
                case 'registerUser':
                    $auth = loadController('Auth', $pdo);
                    $auth->registerUser();
                    break;

                case 'loginUser':
                    $auth = loadController('Auth', $pdo);
                    $auth->loginUser();
                    break;

                case 'storeResume':
                    $user = loadController('User', $pdo);
                    $user->storeResume();
                    break;

                case 'updateResume':
                    $user = loadController('User', $pdo);
                    $user->updateResume();
                    break;

                case 'deleteResume':
                    $user = loadController('User', $pdo);
                    $user->deleteResume();
                    break;

                case 'deleteResumeAdmin':
                    $admin = loadController('Admin', $pdo);
                    $id = $_POST['id'] ?? null;
                    if ($id) {
                        $admin->deleteResume($id);
                    } else {
                        header("Location: index.php?page=admin_dashboard&error=" . urlencode("ID do currículo não fornecido."));
                    }
                    break;

                default:
                    header("Location: index.php?page=login");
                    break;
            }
        } else {
            // Página padrão
            header("Location: index.php?page=login");
        }
        break;
}
?>
