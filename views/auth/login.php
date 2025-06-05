<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="auth-container">
    <h1>Login</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <input type="hidden" name="action" value="loginUser" />
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>

    <p>Ainda não tem uma conta? <a href="index.php?page=register">Registre-se</a></p>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
