<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Usuário</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($userName); ?>!</h1>
    <p>Você está logado no sistema.</p>

    <?php if (!empty($curriculo)): ?>
        <h2>Seu Currículo</h2>
        <p>Nome: <?php echo htmlspecialchars($curriculo['nome']); ?></p>
        <p>Email: <?php echo htmlspecialchars($curriculo['email']); ?></p>
        <!-- Adicione mais campos do currículo conforme necessário -->
    <?php else: ?>
        <p>Você ainda não possui um currículo cadastrado.</p>
        <p><a href="index.php?page=create_resume">Criar Currículo</a></p>
    <?php endif; ?>

    <p><a href="index.php?page=logout">Sair</a></p>
</body>
</html>
