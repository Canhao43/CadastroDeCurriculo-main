<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Currículo</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <script>
        function confirmDelete() {
            return confirm('Tem certeza que deseja excluir este currículo?');
        }
    </script>
</head>
<body>
    <h1>Visualizar Currículo</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="resume-details">
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($curriculo['nome']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($curriculo['email']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($curriculo['telefone'] ?? ''); ?></p>
        <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($curriculo['cidade_estado'] ?? ''); ?></p>
        <?php if (!empty($curriculo['foto']) && file_exists($curriculo['foto'])): ?>
            <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto do Currículo" style="max-width:150px;">
        <?php endif; ?>
        <p><strong>Formações:</strong> <?php echo nl2br(htmlspecialchars($curriculo['formacoes'] ?? '')); ?></p>
        <p><strong>Experiências:</strong> <?php echo nl2br(htmlspecialchars($curriculo['experiencias'] ?? '')); ?></p>
        <p><strong>Habilidades:</strong> <?php echo nl2br(htmlspecialchars($curriculo['habilidades'] ?? '')); ?></p>
        <p><strong>Idiomas:</strong> <?php echo nl2br(htmlspecialchars($curriculo['idiomas'] ?? '')); ?></p>
        <p><strong>Portfólio:</strong> <?php echo nl2br(htmlspecialchars($curriculo['portfolio'] ?? '')); ?></p>
        <p><strong>Data de Criação:</strong> <?php echo htmlspecialchars($curriculo['created_at']); ?></p>
        <p><strong>Última Atualização:</strong> <?php echo htmlspecialchars($curriculo['updated_at']); ?></p>
    </div>

    <p>
        <a href="index.php?page=admin_dashboard" class="button">Voltar ao Painel Admin</a>
        <form method="POST" action="index.php?action=deleteResumeAdmin" style="display:inline;" onsubmit="return confirmDelete();">
            <input type="hidden" name="id" value="<?php echo $curriculo['id']; ?>">
            <button type="submit" class="button delete-button">Excluir Currículo</button>
        </form>
    </p>
</body>
</html>
