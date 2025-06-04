<?php
// Configurações do banco de dados
$host = 'localhost';
$dbname = 'cadastro_curriculo';
$username = 'root';
$password = '';

// Habilitar exibição e log de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_error.log');

try {
    // Criar conexão PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('Erro na conexão com o banco de dados: ' . $e->getMessage());
    // Em ambiente de produção, você deve logar o erro e mostrar uma mensagem genérica
    die('Erro na conexão com o banco de dados.');
}
