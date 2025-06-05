<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="resume-details">

    <h1>Visualizar Currículo</h1>

    <section class="resume-section personal-info">
        <h3>Dados Pessoais</h3>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($curriculo['nome'] ?? 'Não informado'); ?></p>
        <p><strong>CPF:</strong> <?php echo htmlspecialchars($curriculo['cpf'] ?? 'Não informado'); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($curriculo['email'] ?? 'Não informado'); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($curriculo['ddi'] ?? '') . ' ' . htmlspecialchars($curriculo['ddd'] ?? '') . ' ' . htmlspecialchars($curriculo['telefone'] ?? 'Não informado'); ?></p>
        <p><strong>Gênero:</strong> <?php echo htmlspecialchars($curriculo['genero'] ?? 'Não informado'); ?></p>
        <p><strong>Estado Civil:</strong> <?php echo htmlspecialchars($curriculo['estado_civil'] ?? 'Não informado'); ?></p>
        <p><strong>Nacionalidade:</strong> <?php echo htmlspecialchars($curriculo['nacionalidade'] ?? 'Não informado'); ?></p>
        <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($curriculo['cidade'] ?? 'Não informado') . '/' . htmlspecialchars($curriculo['estado'] ?? 'Não informado'); ?></p>
    </section>

    <section class="resume-section photo-section">
        <h3>Foto</h3>
        <?php if (!empty($curriculo['foto']) && file_exists($curriculo['foto'])): ?>
            <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto" class="profile-photo">
        <?php else: ?>
            <p>Não informado</p>
        <?php endif; ?>
    </section>

    <section class="resume-section portfolio-section">
        <h3>Portfólio</h3>
        <p><strong>LinkedIn:</strong> 
            <?php if (!empty($curriculo['linkedin'])): ?>
                <a href="<?php echo htmlspecialchars($curriculo['linkedin']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($curriculo['linkedin']); ?></a>
            <?php else: ?>
                Não informado
            <?php endif; ?>
        </p>
        <p><strong>GitHub:</strong> 
            <?php if (!empty($curriculo['github'])): ?>
                <a href="<?php echo htmlspecialchars($curriculo['github']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($curriculo['github']); ?></a>
            <?php else: ?>
                Não informado
            <?php endif; ?>
        </p>
        <p><strong>Site:</strong> 
            <?php if (!empty($curriculo['site'])): ?>
                <a href="<?php echo htmlspecialchars($curriculo['site']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($curriculo['site']); ?></a>
            <?php else: ?>
                Não informado
            <?php endif; ?>
        </p>
    </section>

    <section class="resume-section education-section">
        <h3>Formações</h3>
        <ul class="resume-list">
            <?php foreach ($curriculo['formacoes'] as $formacao): ?>
                <li class="resume-card">
                    <p><strong>Curso:</strong> <?php echo htmlspecialchars($formacao['curso'] ?? ''); ?></p>
                    <p><strong>Instituição:</strong> <?php echo htmlspecialchars($formacao['instituicao'] ?? ''); ?></p>
                    <p><strong>Início:</strong> <?php echo htmlspecialchars($formacao['anoInicio'] ?? ''); ?></p>
                    <p><strong>Conclusão:</strong> <?php echo htmlspecialchars($formacao['anoConclusao'] ?? ''); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="resume-section experience-section">
        <h3>Experiências</h3>
        <ul class="resume-list">
            <?php foreach ($curriculo['experiencias'] as $experiencia): ?>
                <li class="resume-card">
                    <p><strong>Empresa:</strong> <?php echo htmlspecialchars($experiencia['empresa'] ?? ''); ?></p>
                    <p><strong>Cargo:</strong> <?php echo htmlspecialchars($experiencia['cargo'] ?? ''); ?></p>
                    <p><strong>Tempo:</strong> <?php echo htmlspecialchars($experiencia['tempo'] ?? ''); ?></p>
                    <p><strong>Atividades:</strong> <?php echo htmlspecialchars($experiencia['atividades'] ?? ''); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="resume-section skills-section">
        <h3>Habilidades</h3>
        <ul class="skills-list">
            <?php foreach ($curriculo['habilidades'] as $habilidade): ?>
                <li class="skill-tag"><?php echo htmlspecialchars($habilidade); ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="resume-section languages-section">
        <h3>Idiomas</h3>
        <ul class="resume-list">
            <?php foreach ($curriculo['idiomas'] as $idioma): ?>
                <li class="resume-card">
                    <p><strong>Idioma:</strong> <?php echo htmlspecialchars($idioma['idioma'] ?? ''); ?></p>
                    <p><strong>Nível:</strong> <?php echo htmlspecialchars($idioma['nivel'] ?? ''); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="resume-section system-info-section">
        <h3>Informações do Sistema</h3>
        <p><strong>Data de Criação:</strong> <?php echo isset($curriculo['criado_em']) ? htmlspecialchars($curriculo['criado_em']) : 'Não informado'; ?></p>
        <p><strong>Última Atualização:</strong> <?php echo isset($curriculo['atualizado_em']) ? htmlspecialchars($curriculo['atualizado_em']) : 'Não informado'; ?></p>
    </section>

    <div class="button-container">
        <a href="index.php?page=admin_dashboard" class="button">Voltar ao Painel Admin</a>
        <form method="POST" action="index.php?action=deleteResumeAdmin" onsubmit="return confirm('Tem certeza que deseja excluir este currículo?');" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $curriculo['id'] ?? ''; ?>">
            <button type="submit" class="button delete-button">Excluir Currículo</button>
        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
