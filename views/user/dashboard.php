<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Usuário</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($userName); ?>!</h1>
    <p>Você está logado no sistema.</p>
    <p><a href="index.php?page=logout">Sair</a></p>
</body>
</html>
