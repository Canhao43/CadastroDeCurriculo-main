<?php
// views/admin/dashboard.php
// Dashboard administrativo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Sistema de Currículos</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Painel Administrativo</h1>
            <nav>
                <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="index.php?page=user_dashboard" class="btn btn-secondary">Dashboard Usuário</a>
                <a href="index.php?page=logout" class="btn btn-secondary">Logout</a>
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
            <h2>Todos os Currículos Cadastrados</h2>
            
            <?php if ($curriculos && count($curriculos) > 0): ?>
                <div class="curriculos-grid">
                    <?php foreach ($curriculos as $curriculo): ?>
                        <div class="curriculo-card">
                            <div class="curriculo-header">
                                <h3><?php echo htmlspecialchars($curriculo['nome']); ?></h3>
                                <?php if ($curriculo['foto']): ?>
                                    <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto" class="curriculo-foto">
                                <?php endif; ?>
                            </div>
                            
                            <div class="curriculo-info">
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($curriculo['email']); ?></p>
                                <p><strong>CPF:</strong> <?php echo htmlspecialchars($curriculo['cpf']); ?></p>
                                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($curriculo['ddi'] . ' ' . $curriculo['ddd'] . ' ' . $curriculo['telefone']); ?></p>
                                <p><strong>Cidade:</strong> <?php echo htmlspecialchars($curriculo['cidade'] . ', ' . $curriculo['estado']); ?></p>
                                <p><strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($curriculo['criado_em'])); ?></p>
                            </div>

                            <div class="curriculo-preview">
                                <?php 
                                $formacoes = json_decode($curriculo['formacoes'], true);
                                if ($formacoes && is_array($formacoes) && count($formacoes) > 0): ?>
                                    <p><strong>Última Formação:</strong> <?php echo htmlspecialchars($formacoes[0]['curso'] ?? ''); ?></p>
                                <?php endif; ?>

                                <?php 
                                $experiencias = json_decode($curriculo['experiencias'], true);
                                if ($experiencias && is_array($experiencias) && count($experiencias) > 0): ?>
                                    <p><strong>Última Experiência:</strong> <?php echo htmlspecialchars($experiencias[0]['cargo'] ?? ''); ?></p>
                                <?php endif; ?>

                                <?php 
                                $habilidades = json_decode($curriculo['habilidades'], true);
                                if ($habilidades && is_array($habilidades) && count($habilidades) > 0): ?>
                                    <p><strong>Habilidades:</strong> <?php echo htmlspecialchars(implode(', ', array_slice($habilidades, 0, 3))); ?><?php echo count($habilidades) > 3 ? '...' : ''; ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="curriculo-actions">
                                <a href="index.php?page=view_resume_admin&id=<?php echo $curriculo['id']; ?>" class="btn btn-primary">Ver Completo</a>
                                <form method="POST" action="index.php?action=deleteResumeAdmin" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este currículo?')">
                                    <input type="hidden" name="id" value="<?php echo $curriculo['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Deletar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nenhum currículo cadastrado ainda.</p>
            <?php endif; ?>
        </main>
    </div>

    <style>
        .curriculos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .curriculo-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background: #f9f9f9;
        }

        .curriculo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .curriculo-foto {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .curriculo-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .curriculo-preview {
            margin: 15px 0;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }

        .curriculo-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</body>
</html>
