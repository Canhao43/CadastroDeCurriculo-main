<!DOCTYPE html>
<html>
<head>
    <title>Visualizar Currículo</title>
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/forms.css">
    <link rel="stylesheet" href="../CSS/dashboard_admin.css">
</head>
<body>
    <h1>Visualizar Currículo</h1>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($curriculo['nome'] ?? 'Não informado'); ?></p>
    <p><strong>CPF:</strong> <?php echo htmlspecialchars($curriculo['cpf'] ?? 'Não informado'); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($curriculo['email'] ?? 'Não informado'); ?></p>
    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($curriculo['ddi'] ?? '') . ' ' . htmlspecialchars($curriculo['ddd'] ?? '') . ' ' . htmlspecialchars($curriculo['telefone'] ?? 'Não informado'); ?></p>
    <p><strong>Gênero:</strong> <?php echo htmlspecialchars($curriculo['genero'] ?? 'Não informado'); ?></p>
    <p><strong>Estado Civil:</strong> <?php echo htmlspecialchars($curriculo['estado_civil'] ?? 'Não informado'); ?></p>
    <p><strong>Nacionalidade:</strong> <?php echo htmlspecialchars($curriculo['nacionalidade'] ?? 'Não informado'); ?></p>
    <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($curriculo['cidade'] ?? 'Não informado') . '/' . htmlspecialchars($curriculo['estado'] ?? 'Não informado'); ?></p>
    <p><strong>Foto:</strong><br>
        <?php if (!empty($curriculo['foto']) && file_exists($curriculo['foto'])): ?>
            <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto" style="max-width:150px;">
        <?php else: ?>
            Não informado
        <?php endif; ?>
    </p>
    <p><strong>LinkedIn:</strong> <?php echo htmlspecialchars($curriculo['linkedin'] ?? 'Não informado'); ?></p>
    <p><strong>GitHub:</strong> <?php echo htmlspecialchars($curriculo['github'] ?? 'Não informado'); ?></p>
    <p><strong>Site:</strong> <?php echo htmlspecialchars($curriculo['site'] ?? 'Não informado'); ?></p>
    <p><strong>Formações:</strong>
        <ul>
            <?php foreach ($curriculo['formacoes'] as $formacao): ?>
                <li>
                    Curso: <?php echo htmlspecialchars($formacao['curso'] ?? ''); ?>,
                    Instituição: <?php echo htmlspecialchars($formacao['instituicao'] ?? ''); ?>,
                    Início: <?php echo htmlspecialchars($formacao['anoInicio'] ?? ''); ?>,
                    Conclusão: <?php echo htmlspecialchars($formacao['anoConclusao'] ?? ''); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </p>
    <p><strong>Experiências:</strong>
        <ul>
            <?php foreach ($curriculo['experiencias'] as $experiencia): ?>
                <li>
                    Empresa: <?php echo htmlspecialchars($experiencia['empresa'] ?? ''); ?>,
                    Cargo: <?php echo htmlspecialchars($experiencia['cargo'] ?? ''); ?>,
                    Tempo: <?php echo htmlspecialchars($experiencia['tempo'] ?? ''); ?>,
                    Atividades: <?php echo htmlspecialchars($experiencia['atividades'] ?? ''); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </p>
    <p><strong>Habilidades:</strong>
        <ul>
            <?php foreach ($curriculo['habilidades'] as $habilidade): ?>
                <li><?php echo htmlspecialchars($habilidade); ?></li>
            <?php endforeach; ?>
        </ul>
    </p>
    <p><strong>Idiomas:</strong>
        <ul>
            <?php foreach ($curriculo['idiomas'] as $idioma): ?>
                <li>
                    Idioma: <?php echo htmlspecialchars($idioma['idioma'] ?? ''); ?>,
                    Nível: <?php echo htmlspecialchars($idioma['nivel'] ?? ''); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </p>
    <p><strong>Data de Criação:</strong> <?php echo isset($curriculo['criado_em']) ? htmlspecialchars($curriculo['criado_em']) : 'Não informado'; ?></p>
    <p><strong>Última Atualização:</strong> <?php echo isset($curriculo['atualizado_em']) ? htmlspecialchars($curriculo['atualizado_em']) : 'Não informado'; ?></p>
    <a href="index.php?page=admin_dashboard">Voltar ao Painel Admin</a>
    <form method="POST" action="index.php?action=deleteResumeAdmin" onsubmit="return confirm('Tem certeza que deseja excluir este currículo?');">
        <input type="hidden" name="id" value="<?php echo $curriculo['id'] ?? ''; ?>">
        <button type="submit">Excluir Currículo</button>
    </form>
</body>
</html>
