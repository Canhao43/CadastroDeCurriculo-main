<?php
// views/admin/view_resume.php
// Visualização completa de currículo pelo admin
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Currículo - Painel Administrativo</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Visualizar Currículo</h1>
            <nav>
                <a href="/admin/dashboard" class="btn btn-secondary">Voltar ao Painel</a>
                <a href="/logout" class="btn btn-secondary">Logout</a>
            </nav>
        </header>

        <?php if ($curriculo): ?>
            <div class="curriculo-completo">
                <div class="curriculo-header">
                    <div class="dados-principais">
                        <h2><?php echo htmlspecialchars($curriculo['nome']); ?></h2>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($curriculo['email']); ?></p>
                        <p><strong>CPF:</strong> <?php echo htmlspecialchars($curriculo['cpf']); ?></p>
                        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($curriculo['ddi'] . ' ' . $curriculo['ddd'] . ' ' . $curriculo['telefone']); ?></p>
                    </div>
                    <?php if ($curriculo['foto']): ?>
                        <div class="foto-curriculo">
                            <img src="<?php echo htmlspecialchars($curriculo['foto']); ?>" alt="Foto do candidato">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="secao">
                    <h3>Dados Pessoais</h3>
                    <div class="dados-grid">
                        <p><strong>Data de Nascimento:</strong> <?php echo $curriculo['nascimento'] ? date('d/m/Y', strtotime($curriculo['nascimento'])) : 'Não informado'; ?></p>
                        <p><strong>Gênero:</strong> <?php echo htmlspecialchars($curriculo['genero'] ?: 'Não informado'); ?></p>
                        <p><strong>Estado Civil:</strong> <?php echo htmlspecialchars($curriculo['estado_civil'] ?: 'Não informado'); ?></p>
                        <p><strong>Nacionalidade:</strong> <?php echo htmlspecialchars($curriculo['nacionalidade'] ?: 'Não informado'); ?></p>
                        <p><strong>Cidade:</strong> <?php echo htmlspecialchars($curriculo['cidade'] ?: 'Não informado'); ?></p>
                        <p><strong>Estado:</strong> <?php echo htmlspecialchars($curriculo['estado'] ?: 'Não informado'); ?></p>
                    </div>
                </div>

                <div class="secao">
                    <h3>Formação Acadêmica</h3>
                    <?php 
                    $formacoes = json_decode($curriculo['formacoes'], true);
                    if ($formacoes && is_array($formacoes) && count($formacoes) > 0):
                        foreach ($formacoes as $formacao): ?>
                            <div class="item-formacao">
                                <h4><?php echo htmlspecialchars($formacao['curso'] ?? ''); ?></h4>
                                <p><strong>Instituição:</strong> <?php echo htmlspecialchars($formacao['instituicao'] ?? ''); ?></p>
                                <p><strong>Período:</strong> <?php echo htmlspecialchars($formacao['anoInicio'] ?? ''); ?> - <?php echo htmlspecialchars($formacao['anoConclusao'] ?? ''); ?></p>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p>Nenhuma formação informada.</p>
                    <?php endif; ?>
                </div>

                <div class="secao">
                    <h3>Experiência Profissional</h3>
                    <?php 
                    $experiencias = json_decode($curriculo['experiencias'], true);
                    if ($experiencias && is_array($experiencias) && count($experiencias) > 0):
                        foreach ($experiencias as $experiencia): ?>
                            <div class="item-experiencia">
                                <h4><?php echo htmlspecialchars($experiencia['cargo'] ?? ''); ?></h4>
                                <p><strong>Empresa:</strong> <?php echo htmlspecialchars($experiencia['empresa'] ?? ''); ?></p>
                                <p><strong>Tempo:</strong> <?php echo htmlspecialchars($experiencia['tempo'] ?? ''); ?></p>
                                <?php if (!empty($experiencia['atividades'])): ?>
                                    <p><strong>Atividades:</strong></p>
                                    <p><?php echo nl2br(htmlspecialchars($experiencia['atividades'])); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p>Nenhuma experiência profissional informada.</p>
                    <?php endif; ?>
                </div>

                <div class="secao">
                    <h3>Habilidades</h3>
                    <?php 
                    $habilidades = json_decode($curriculo['habilidades'], true);
                    if ($habilidades && is_array($habilidades) && count($habilidades) > 0): ?>
                        <div class="habilidades-lista">
                            <?php foreach ($habilidades as $habilidade): ?>
                                <span class="habilidade-tag"><?php echo htmlspecialchars($habilidade); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Nenhuma habilidade informada.</p>
                    <?php endif; ?>
                </div>

                <div class="secao">
                    <h3>Idiomas</h3>
                    <?php 
                    $idiomas = json_decode($curriculo['idiomas'], true);
                    if ($idiomas && is_array($idiomas) && count($idiomas) > 0):
                        foreach ($idiomas as $idioma): ?>
                            <div class="item-idioma">
                                <p><strong><?php echo htmlspecialchars($idioma['idioma'] ?? $idioma); ?>:</strong> 
                                <?php echo htmlspecialchars($idioma['nivel'] ?? 'Não informado'); ?></p>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p>Nenhum idioma informado.</p>
                    <?php endif; ?>
                </div>

                <div class="secao">
                    <h3>Portfólio</h3>
                    <div class="portfolio-links">
                        <?php if ($curriculo['linkedin']): ?>
                            <p><strong>LinkedIn:</strong> <a href="<?php echo htmlspecialchars($curriculo['linkedin']); ?>" target="_blank"><?php echo htmlspecialchars($curriculo['linkedin']); ?></a></p>
                        <?php endif; ?>
                        
                        <?php if ($curriculo['github']): ?>
                            <p><strong>GitHub:</strong> <a href="<?php echo htmlspecialchars($curriculo['github']); ?>" target="_blank"><?php echo htmlspecialchars($curriculo['github']); ?></a></p>
                        <?php endif; ?>
                        
                        <?php if ($curriculo['site']): ?>
                            <p><strong>Site Pessoal:</strong> <a href="<?php echo htmlspecialchars($curriculo['site']); ?>" target="_blank"><?php echo htmlspecialchars($curriculo['site']); ?></a></p>
                        <?php endif; ?>
                        
                        <?php if (!$curriculo['linkedin'] && !$curriculo['github'] && !$curriculo['site']): ?>
                            <p>Nenhum link de portfólio informado.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="secao">
                    <h3>Informações do Sistema</h3>
                    <p><strong>Criado em:</strong> <?php echo date('d/m/Y H:i:s', strtotime($curriculo['criado_em'])); ?></p>
                </div>

                <div class="acoes-admin">
                    <form method="POST" action="index.php?action=deleteResumeAdmin" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este currículo?')">
                        <input type="hidden" name="id" value="<?php echo $curriculo['id']; ?>">
                        <button type="submit" class="btn btn-danger">Deletar Currículo</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <p>Currículo não encontrado.</p>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .curriculo-completo {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .curriculo-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .foto-curriculo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
        }

        .secao {
            margin-bottom: 30px;
        }

        .secao h3 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .dados-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
        }

        .item-formacao, .item-experiencia, .item-idioma {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .item-formacao h4, .item-experiencia h4 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .habilidades-lista {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .habilidade-tag {
            background: #007bff;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }

        .portfolio-links a {
            color: #007bff;
            text-decoration: none;
        }

        .portfolio-links a:hover {
            text-decoration: underline;
        }

        .acoes-admin {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</body>
</html>
