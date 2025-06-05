<?php
// views/auth/register.php
// Página de registro
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="auth-container">
    <h1>Registro</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=registerUser">
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirmar Senha:</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>

        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>

    <p>Já tem uma conta? <a href="index.php?page=login">Faça login</a></p>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
