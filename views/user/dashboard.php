<?php
// views/user/dashboard.php
// Dashboard do usuário
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema de Currículos</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <nav>
                <a href="<?php echo $base_url; ?>/logout" class="btn btn-secondary">Logout</a>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?php echo $base_url; ?>/admin/dashboard" class="btn btn-info">Painel Admin</a>
                <?php endif; ?>
            </nav>
        </header>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <main>
            <?php if ($curriculo): ?>
                <h2>Seu Currículo</h2>
                <div class="curriculo-preview">
                    <h3><?php echo htmlspecialchars($curriculo['nome']); ?></h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($curriculo['email']); ?></p>
                    <p><strong>CPF:</strong> <?php echo htmlspecialchars($curriculo['cpf']); ?></p>
                    
                    <?php if ($curriculo['foto']): ?>
                        <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto" style="max-width: 150px;">
                    <?php endif; ?>

                    <div class="formacoes">
                        <h4>Formações:</h4>
                        <?php 
                        $formacoes = json_decode($curriculo['formacoes'], true);
                        if ($formacoes && is_array($formacoes)):
                            foreach ($formacoes as $formacao): ?>
                                <p><?php echo htmlspecialchars($formacao['curso'] ?? ''); ?> - <?php echo htmlspecialchars($formacao['instituicao'] ?? ''); ?></p>
                            <?php endforeach;
                        endif; ?>
                    </div>

                    <div class="experiencias">
                        <h4>Experiências:</h4>
                        <?php 
                        $experiencias = json_decode($curriculo['experiencias'], true);
                        if ($experiencias && is_array($experiencias)):
                            foreach ($experiencias as $experiencia): ?>
                                <p><?php echo htmlspecialchars($experiencia['cargo'] ?? ''); ?> - <?php echo htmlspecialchars($experiencia['empresa'] ?? ''); ?></p>
                            <?php endforeach;
                        endif; ?>
                    </div>

                    <div class="habilidades">
                        <h4>Habilidades:</h4>
                        <?php
                        $habilidades = json_decode($curriculo['habilidades'], true);
                        if ($habilidades && is_array($habilidades)):
                            foreach ($habilidades as $habilidade): ?>
                                <span><?php echo htmlspecialchars($habilidade); ?></span>
                            <?php endforeach;
                        endif; ?>
                    </div>

                    <div class="idiomas">
                        <h4>Idiomas:</h4>
                        <?php
                        $idiomas = json_decode($curriculo['idiomas'], true);
                        if ($idiomas && is_array($idiomas)):
                            foreach ($idiomas as $idioma): ?>
                                <p><?php echo htmlspecialchars($idioma['idioma'] ?? ''); ?> - <?php echo htmlspecialchars($idioma['nivel'] ?? ''); ?></p>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>

                <div class="actions">
                    <a href="index.php?page=edit_resume" class="btn btn-primary">Editar Currículo</a>
                    <form method="POST" action="index.php?action=deleteResume" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar seu currículo?')">
                        <button type="submit" class="btn btn-danger">Deletar Currículo</button>
                    </form>
                </div>
            <?php else: ?>
                <h2>Você ainda não possui um currículo</h2>
                <p>Crie seu primeiro currículo para começar!</p>
                <a href="index.php?page=create_resume" class="btn btn-primary">Criar Currículo</a>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
