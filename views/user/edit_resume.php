<?php
// views/user/edit_resume.php
// Página para editar currículo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Editar Currículo - Sistema de Currículos</title>
    <link rel="stylesheet" href="../CSS/style.css" />
</head>
<body>
    <div class="container">
        <header>
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <nav>
                <a href="<?php echo $base_url; ?>/user/dashboard" class="btn btn-secondary">Voltar ao Dashboard</a>
                <a href="<?php echo $base_url; ?>/logout" class="btn btn-secondary">Logout</a>
            </nav>
        </header>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h1 class="titulo-principal">Editar Currículo</h1>
            <form id="curriculoForm" method="POST" action="index.php?action=updateResume" enctype="multipart/form-data">
                <h2>Dados Pessoais</h2>

                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome ?? ''); ?>" required />

                <label for="cpf">CPF *</label>
                <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf ?? ''); ?>" required />

                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required />

                <div class="telefone-container">
                    <label for="telefone">Telefone *</label>
                    <div class="telefone-linha">
                        <select id="ddi" name="ddi" class="ddi-select" required>
                            <option disabled hidden>DDI</option>
                            <option value="+55" <?php echo ($ddi ?? '') == '+55' ? 'selected' : ''; ?>>BR +55</option>
                            <option value="+1" <?php echo ($ddi ?? '') == '+1' ? 'selected' : ''; ?>>US +1</option>
                            <option value="+44" <?php echo ($ddi ?? '') == '+44' ? 'selected' : ''; ?>>UK +44</option>
                            <option value="+33" <?php echo ($ddi ?? '') == '+33' ? 'selected' : ''; ?>>FR +33</option>
                            <option value="+49" <?php echo ($ddi ?? '') == '+49' ? 'selected' : ''; ?>>DE +49</option>
                            <option value="+81" <?php echo ($ddi ?? '') == '+81' ? 'selected' : ''; ?>>JP +81</option>
                            <option value="+351" <?php echo ($ddi ?? '') == '+351' ? 'selected' : ''; ?>>PT +351</option>
                        </select>
                        <input type="text" id="ddd" name="ddd" placeholder="DDD" maxlength="2" value="<?php echo htmlspecialchars($ddd ?? ''); ?>" required />
                        <input type="text" id="numero" name="numero" placeholder="Número" value="<?php echo htmlspecialchars($telefone ?? ''); ?>" required />
                    </div>
                </div>

                <label for="nascimento">Data de Nascimento</label>
                <input type="date" id="nascimento" name="nascimento" value="<?php echo htmlspecialchars($nascimento ?? ''); ?>" />

                <label for="genero">Gênero</label>
                <select id="genero" name="genero">
                    <option disabled hidden>Selecione</option>
                    <option value="masculino" <?php echo ($genero ?? '') == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="feminino" <?php echo ($genero ?? '') == 'feminino' ? 'selected' : ''; ?>>Feminino</option>
                    <option value="outro" <?php echo ($genero ?? '') == 'outro' ? 'selected' : ''; ?>>Outro</option>
                    <option value="prefiro_nao_dizer" <?php echo ($genero ?? '') == 'prefiro_nao_dizer' ? 'selected' : ''; ?>>Prefiro não dizer</option>
                </select>

                <label for="estadoCivil">Estado Civil</label>
                <select id="estadoCivil" name="estadoCivil">
                    <option disabled hidden>Selecione</option>
                    <option value="solteiro" <?php echo ($estado_civil ?? '') == 'solteiro' ? 'selected' : ''; ?>>Solteiro(a)</option>
                    <option value="casado" <?php echo ($estado_civil ?? '') == 'casado' ? 'selected' : ''; ?>>Casado(a)</option>
                    <option value="divorciado" <?php echo ($estado_civil ?? '') == 'divorciado' ? 'selected' : ''; ?>>Divorciado(a)</option>
                    <option value="viuvo" <?php echo ($estado_civil ?? '') == 'viuvo' ? 'selected' : ''; ?>>Viúvo(a)</option>
                </select>

                <label for="nacionalidade">Nacionalidade</label>
                <input type="text" id="nacionalidade" name="nacionalidade" value="<?php echo htmlspecialchars($nacionalidade ?? ''); ?>" />

                <label for="cidade">Cidade</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade ?? ''); ?>" />

                <label for="estado">Estado</label>
                <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($estado ?? ''); ?>" />

                <h2>Formação Acadêmica</h2>
                <div id="formacoes-container">
                    <?php 
                    if (isset($formacoes) && is_array($formacoes) && count($formacoes) > 0):
                        foreach ($formacoes as $index => $formacao): ?>
                            <div class="formacao">
                                <label>Curso</label>
                                <input type="text" name="curso[]" placeholder="Ex: Sistemas de Informação" value="<?php echo htmlspecialchars($formacao['curso'] ?? ''); ?>" />

                                <label>Instituição</label>
                                <input type="text" name="instituicao[]" placeholder="Ex: Universidade FMU" value="<?php echo htmlspecialchars($formacao['instituicao'] ?? ''); ?>" />

                                <label>Ano de Início</label>
                                <input type="text" name="anoInicio[]" placeholder="Ex: 2020" value="<?php echo htmlspecialchars($formacao['anoInicio'] ?? ''); ?>" />

                                <label>Ano de Conclusão</label>
                                <input type="text" name="anoConclusao[]" placeholder="Ex: 2024" value="<?php echo htmlspecialchars($formacao['anoConclusao'] ?? ''); ?>" />
                            </div>
                        <?php endforeach;
                    else: ?>
                        <div class="formacao">
                            <label>Curso</label>
                            <input type="text" name="curso[]" placeholder="Ex: Sistemas de Informação" />

                            <label>Instituição</label>
                            <input type="text" name="instituicao[]" placeholder="Ex: Universidade FMU" />

                            <label>Ano de Início</label>
                            <input type="text" name="anoInicio[]" placeholder="Ex: 2020" />

                            <label>Ano de Conclusão</label>
                            <input type="text" name="anoConclusao[]" placeholder="Ex: 2024" />
                        </div>
                    <?php endif; ?>
                </div>
                <div class="botoes-formacao">
                    <button type="button" id="adicionar-formacao">+ Adicionar Formação</button>
                    <button type="button" id="remover-formacao" class="hidden">- Remover Último Adicionado</button>
                </div>

                <h2>Experiência Profissional</h2>

                <label for="temExperiencia">Você possui experiência profissional?</label>
                <select id="temExperiencia" name="temExperiencia">
                    <option disabled hidden>Selecione</option>
                    <option value="sim" <?php echo (!empty($experiencias)) ? 'selected' : ''; ?>>Sim</option>
                    <option value="nao" <?php echo (empty($experiencias)) ? 'selected' : ''; ?>>Não</option>
                </select>

                <div id="experiencias-container" class="hidden">
                    <?php 
                    if (isset($experiencias) && is_array($experiencias) && count($experiencias) > 0):
                        foreach ($experiencias as $index => $experiencia): ?>
                            <div class="experiencia">
                                <label>Nome da Empresa</label>
                                <input type="text" name="empresa[]" placeholder="Ex: Tech Solutions" value="<?php echo htmlspecialchars($experiencia['empresa'] ?? ''); ?>" />

                                <label>Cargo</label>
                                <input type="text" name="cargo[]" placeholder="Ex: Desenvolvedor Frontend" value="<?php echo htmlspecialchars($experiencia['cargo'] ?? ''); ?>" />

                                <label>Tempo de Empresa</label>
                                <input type="text" name="tempo[]" placeholder="Ex: 1 ano e 6 meses" value="<?php echo htmlspecialchars($experiencia['tempo'] ?? ''); ?>" />

                                <label>Atividades Exercidas</label>
                                <textarea name="atividades[]" placeholder="Descreva suas responsabilidades..."><?php echo htmlspecialchars($experiencia['atividades'] ?? ''); ?></textarea>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <div class="experiencia">
                            <label>Nome da Empresa</label>
                            <input type="text" name="empresa[]" placeholder="Ex: Tech Solutions" />

                            <label>Cargo</label>
                            <input type="text" name="cargo[]" placeholder="Ex: Desenvolvedor Frontend" />

                            <label>Tempo de Empresa</label>
                            <input type="text" name="tempo[]" placeholder="Ex: 1 ano e 6 meses" />

                            <label>Atividades Exercidas</label>
                            <textarea name="atividades[]" placeholder="Descreva suas responsabilidades..."></textarea>
                        </div>
                    <?php endif; ?>
                </div>
                <div id="campoExperiencia" class="hidden">
                    <label for="descricaoExp">Descreva sua experiência</label>
                    <textarea id="descricaoExp" name="descricaoExp" maxlength="500"></textarea>
                    <small><span id="contador">0</span>/500 caracteres</small>
                </div>
                <button type="button" id="adicionar-experiencia" class="hidden">+ Adicionar Experiência</button>
                <button type="button" id="remover-experiencia" class="hidden">- Remover Último Adicionado</button>

                <h2>Habilidades</h2>
                <div id="habilidades-wrapper">
                    <div class="adicionar-habilidade">
                        <input type="text" id="nova-habilidade" placeholder="Digite uma habilidade e pressione Enter ou clique em Adicionar" />
                    </div>
                    <button type="button" id="btn-adicionar-habilidade">Adicionar</button>
                    <div id="habilidades-lista">
                        <?php 
                        if (isset($habilidades) && is_array($habilidades)):
                            foreach ($habilidades as $habilidade): ?>
                                <span class="habilidade-tag"><?php echo htmlspecialchars($habilidade); ?> <button type="button" class="remover-habilidade">×</button></span>
                                <input type="hidden" name="habilidades[]" value="<?php echo htmlspecialchars($habilidade); ?>">
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>

                <h2>Idiomas</h2>
                <div id="idiomas-container">
                    <?php 
                    if (isset($idiomas) && is_array($idiomas) && count($idiomas) > 0):
                        foreach ($idiomas as $index => $idioma): ?>
                            <div class="idioma">
                                <label for="idioma">Idioma</label>
                                <input type="text" name="idioma[]" placeholder="Ex: Inglês" value="<?php echo htmlspecialchars($idioma['idioma'] ?? $idioma); ?>" />

                                <label for="nivelIdioma">Nível</label>
                                <select name="nivelIdioma[]">
                                    <option disabled hidden>Selecione</option>
                                    <option value="basico" <?php echo (isset($idioma['nivel']) && $idioma['nivel'] == 'basico') ? 'selected' : ''; ?>>Básico</option>
                                    <option value="intermediario" <?php echo (isset($idioma['nivel']) && $idioma['nivel'] == 'intermediario') ? 'selected' : ''; ?>>Intermediário</option>
                                    <option value="avancado" <?php echo (isset($idioma['nivel']) && $idioma['nivel'] == 'avancado') ? 'selected' : ''; ?>>Avançado</option>
                                    <option value="fluente" <?php echo (isset($idioma['nivel']) && $idioma['nivel'] == 'fluente') ? 'selected' : ''; ?>>Fluente</option>
                                </select>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <div class="idioma">
                            <label for="idioma">Idioma</label>
                            <input type="text" name="idioma[]" placeholder="Ex: Inglês" />

                            <label for="nivelIdioma">Nível</label>
                            <select name="nivelIdioma[]">
                                <option disabled selected hidden>Selecione</option>
                                <option value="basico">Básico</option>
                                <option value="intermediario">Intermediário</option>
                                <option value="avancado">Avançado</option>
                                <option value="fluente">Fluente</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" id="adicionar-idioma">+ Adicionar Idioma</button>
                <button type="button" id="remover-idioma" class="hidden">- Remover Último Adicionado</button>

                <h2>Portfólio</h2>

                <label for="linkedin">LinkedIn</label>
                <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($curriculo['linkedin']); ?>" />

                <label for="github">GitHub</label>
                <input type="url" id="github" name="github" value="<?php echo htmlspecialchars($curriculo['github']); ?>" />

                <label for="site">Site Pessoal / Portfólio</label>
                <input type="url" id="site" name="site" value="<?php echo htmlspecialchars($curriculo['site']); ?>" />

                <label for="foto">Foto (opcional)</label>
                <input type="file" id="foto" name="foto" accept="image/*" />
                <?php if (!empty($foto)): ?>
                    <p>Foto atual: <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto atual" style="max-width: 100px;"></p>
                <?php endif; ?>

                <div class="botoes-enviar">
                    <button type="submit">Atualizar Currículo</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="JS/script.js"></script>
</body>
</html>
