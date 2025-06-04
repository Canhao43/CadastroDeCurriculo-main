<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Currículos</title>
</head>
<body>
    <h1>Login</h1>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: red;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: green;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <input type="hidden" name="action" value="loginUser" />
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>

    <p>Ainda não tem uma conta? <a href="index.php?page=register">Registre-se</a></p>
</body>
</html>
