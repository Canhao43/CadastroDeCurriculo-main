<?php
// Script para criar um usuário admin manualmente

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/User.php';

$db = $pdo; // Variável $pdo do includes/db.php
$userModel = new User($db);

// Dados do novo usuário admin
$nome = 'Admin';
$email = 'admin@example.com';
$password = 'admin123'; // Altere para uma senha segura
$role = 'admin';

if ($userModel->emailExists($email)) {
    echo "Usuário com email $email já existe.\n";
} else {
    $created = $userModel->create($nome, $email, $password, $role);
    if ($created) {
        echo "Usuário admin criado com sucesso.\n";
        echo "Email: $email\nSenha: $password\n";
    } else {
        echo "Erro ao criar usuário admin.\n";
    }
}
?>
