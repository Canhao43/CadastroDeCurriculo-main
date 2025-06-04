<?php
// views/user/create_resume.php
// Página para criar currículo
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Criar Currículo - Sistema de Currículos</title>
    <link rel="stylesheet" href="../CSS/style.css" />
</head>
<body>
    <div class="container">
        <header>
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <nav>
                <a href="/user/dashboard" class="btn btn-secondary">Voltar ao Dashboard</a>
                <a href="/logout" class="btn btn-secondary">Logout</a>
            </nav>
        </header>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h1 class="titulo-principal">Criar Currículo</h1>
            <form id="curriculoForm" method="POST" action="index.php?action=storeResume" enctype="multipart/form-data">
                <h2>Dados Pessoais</h2>

                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" required />

                <label for="cpf">CPF *</label>
                <input type="text" id="cpf" name="cpf" required />

                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required />

                <div class="telefone-container">
                    <label for="telefone">Telefone *</label>
                    <div class="telefone-linha">
                        <select id="ddi" name="ddi" class="ddi-select" required>
                            <option disabled selected hidden>DDI</option>
                            <option value="+55">BR +55</option>
                            <option value="+1">US +1</option>
                            <option value="+44">UK +44</option>
                            <option value="+33">FR +33</option>
                            <option value="+49">DE +49</option>
                            <option value="+81">JP +81</option>
                            <option value="+351">PT +351</option>
                        </select>
                        <input type="text" id="ddd" name="ddd" placeholder="DDD" maxlength="2" required />
                        <input type="text" id="numero" name="numero" placeholder="Número" required />
                    </div>
                </div>

                <label for="nascimento">Data de Nascimento</label>
                <input type="date" id="nascimento" name="nascimento" />

                <label for="genero">Gênero</label>
                <select id="genero" name="genero">
                    <option disabled selected hidden>Selecione</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                    <option value="prefiro_nao_dizer">Prefiro não dizer</option>
                </select>

                <label for="estadoCivil">Estado Civil</label>
                <select id="estadoCivil" name="estadoCivil">
                    <option disabled selected hidden>Selecione</option>
                    <option value="solteiro">Solteiro(a)</option>
                    <option value="casado">Casado(a)</option>
                    <option value="divorciado">Divorciado(a)</option>
                    <option value="viuvo">Viúvo(a)</option>
                </select>

                <label for="nacionalidade">Nacionalidade</label>
                <input type="text" id="nacionalidade" name="nacionalidade" />

                <label for="cidade">Cidade</label>
                <input type="text" id="cidade" name="cidade" />

                <label for="estado">Estado</label>
                <input type="text" id="estado" name="estado" />

                <h2>Formação Acadêmica</h2>
                <div id="formacoes-container">
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
                </div>
                <div class="botoes-formacao">
                    <button type="button" id="adicionar-formacao">+ Adicionar Formação</button>
                    <button type="button" id="remover-formacao" class="hidden">- Remover Último Adicionado</button>
                </div>

                <h2>Experiência Profissional</h2>

                <label for="temExperiencia">Você possui experiência profissional?</label>
                <select id="temExperiencia">
                    <option disabled selected hidden>Selecione</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>

                <div id="experiencias-container" class="hidden">
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
                    <div id="habilidades-lista"></div>
                </div>

                <h2>Idiomas</h2>
                <div id="idiomas-container">
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
                </div>
                <button type="button" id="adicionar-idioma">+ Adicionar Idioma</button>
                <button type="button" id="remover-idioma" class="hidden">- Remover Último Adicionado</button>

                <h2>Portfólio</h2>

                <label for="linkedin">LinkedIn</label>
                <input type="url" id="linkedin" name="linkedin" />

                <label for="github">GitHub</label>
                <input type="url" id="github" name="github" />

                <label for="site">Site Pessoal / Portfólio</label>
                <input type="url" id="site" name="site" />

                <label for="foto">Foto (opcional)</label>
                <input type="file" id="foto" name="foto" accept="image/*" />

                <div class="botoes-enviar">
                    <button type="submit">Criar Currículo</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="JS/script.js"></script>
</body>
</html>
