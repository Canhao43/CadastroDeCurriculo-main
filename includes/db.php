<?php
// Configurações do banco de dados
$host = 'localhost';
$dbname = 'cadastro_curriculo';
$username = 'root';
$password = '';

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
    // Em ambiente de produção, você deve logar o erro e mostrar uma mensagem genérica
    die('Erro na conexão com o banco de dados: ' . $e->getMessage());
}
