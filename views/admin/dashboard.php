<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <script>
        function confirmDelete() {
            return confirm('Tem certeza que deseja excluir este currículo?');
        }
    </script>
</head>
<body>
    <h1>Painel Administrativo - Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>!</h1>

    <p>
        <a href="index.php?page=user_dashboard" class="button">Dashboard do Usuário</a>
        <a href="index.php?page=logout" class="button">Sair</a>
    </p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <h2>Todos os Currículos</h2>
    <?php if (!empty($curriculos)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Foto</th>
                    <th>Resumo</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($curriculos as $curriculo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($curriculo['nome']); ?></td>
                        <td><?php echo htmlspecialchars($curriculo['email']); ?></td>
                        <td>
                            <?php if (!empty($curriculo['foto']) && file_exists($curriculo['foto'])): ?>
                                <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto" style="max-width:100px;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $resumo = '';
                                if (!empty($curriculo['ultima_formacao'])) {
                                    $resumo .= 'Formação: ' . htmlspecialchars($curriculo['ultima_formacao']) . '<br>';
                                }
                                if (!empty($curriculo['ultima_experiencia'])) {
                                    $resumo .= 'Experiência: ' . htmlspecialchars($curriculo['ultima_experiencia']);
                                }
                                echo $resumo;
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($curriculo['created_at']); ?></td>
                        <td>
                            <a href="index.php?page=view_resume_admin&id=<?php echo $curriculo['id']; ?>" class="button">Ver Currículo</a>
                            <form method="POST" action="index.php?action=deleteResumeAdmin" style="display:inline;" onsubmit="return confirmDelete();">
                                <input type="hidden" name="id" value="<?php echo $curriculo['id']; ?>">
                                <button type="submit" class="button delete-button">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum currículo cadastrado.</p>
    <?php endif; ?>
</body>
</html>
