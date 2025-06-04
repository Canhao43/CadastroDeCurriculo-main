<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Usuário</title>
    <link rel="stylesheet" href="CSS/style.css">
    <script>
        function confirmDelete() {
            return confirm('Tem certeza que deseja excluir seu currículo?');
        }
    </script>
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($userName); ?>!</h1>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <p><a href="index.php?page=admin_dashboard" class="button">Painel Administrativo</a></p>
    <?php endif; ?>

    <?php if (!empty($curriculo)): ?>
        <h2>Seu Currículo</h2>
        <div class="resume-preview">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($curriculo['nome']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($curriculo['email']); ?></p>
            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($curriculo['telefone'] ?? ''); ?></p>
            <p><strong>Cidade/Estado:</strong> 
                <?php 
                    $cidade = $curriculo['cidade'] ?? '';
                    $estado = $curriculo['estado'] ?? '';
                    echo htmlspecialchars(trim($cidade . ' / ' . $estado, ' / '));
                ?>
            </p>
            <?php if (!empty($curriculo['foto']) && file_exists($curriculo['foto'])): ?>
                <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto do Currículo" style="max-width:150px;">
            <?php endif; ?>
            <p><strong>Última Formação:</strong> 
                <?php 
                    if (!empty($curriculo['formacoes']) && is_array($curriculo['formacoes']) && count($curriculo['formacoes']) > 0) {
                        $ultimaFormacao = $curriculo['formacoes'][0];
                        echo htmlspecialchars($ultimaFormacao['curso'] ?? '') . ' - ' . htmlspecialchars($ultimaFormacao['instituicao'] ?? '');
                    } else {
                        echo '';
                    }
                ?>
            </p>
            <p><strong>Última Experiência:</strong> 
                <?php 
                    if (!empty($curriculo['experiencias']) && is_array($curriculo['experiencias']) && count($curriculo['experiencias']) > 0) {
                        $ultimaExperiencia = $curriculo['experiencias'][0];
                        echo htmlspecialchars($ultimaExperiencia['cargo'] ?? '') . ' - ' . htmlspecialchars($ultimaExperiencia['empresa'] ?? '');
                    } else {
                        echo '';
                    }
                ?>
            </p>
            <p><strong>Habilidades:</strong> 
                <?php 
                    if (!empty($curriculo['habilidades']) && is_array($curriculo['habilidades'])) {
                        echo htmlspecialchars(implode(', ', $curriculo['habilidades']));
                    } else {
                        echo '';
                    }
                ?>
            </p>
        </div>
        <p>
            <a href="index.php?page=edit_resume" class="button">Editar Currículo</a>
            <form method="POST" action="index.php?action=deleteResume" style="display:inline;" onsubmit="return confirmDelete();">
                <button type="submit" class="button delete-button">Excluir Currículo</button>
            </form>
        </p>
    <?php else: ?>
        <p>Você ainda não possui um currículo cadastrado.</p>
        <p><a href="index.php?page=create_resume" class="button">Criar Currículo</a></p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <p><a href="index.php?page=logout" class="button">Sair</a></p>
</body>
</html>
