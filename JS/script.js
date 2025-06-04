$(document).ready(function () {
    // Máscaras
    $("#cpf").mask("000.000.000-00", { reverse: true });
    $("#ddd").mask("00");
    $("#numero").mask("00000-0000");

    // Contador de caracteres da experiência
    $("#descricaoExp").on("input", function () {
        $("#contador").text($(this).val().length);
    });

    // Mostrar/ocultar campo de experiência
    $("#experiencia, #temExperiencia").on("change", function () {
        if ($(this).val() === "sim") {
            $("#campoExperiencia, #experiencias-container, #adicionar-experiencia, #remover-experiencia").removeClass("hidden");
        } else {
            $("#campoExperiencia, #experiencias-container, #adicionar-experiencia, #remover-experiencia").addClass("hidden");
            $("#experiencias-container").html("");
        }
    });

    // Adicionar formação
    $("#adicionar-formacao").on("click", function () {
        $("#formacoes-container").append(`
            <div class="formacao">
                <label>Curso</label>
                <input type="text" name="curso[]" placeholder="Ex: Sistemas de Informação">
                <label>Instituição</label>
                <input type="text" name="instituicao[]" placeholder="Ex: Universidade FMU">
                <label>Ano de Início</label>
                <input type="text" name="anoInicio[]" placeholder="Ex: 2020">
                <label>Ano de Conclusão</label>
                <input type="text" name="anoConclusao[]" placeholder="Ex: 2024">
            </div>
        `);
        $("#remover-formacao").removeClass("hidden");
    });

    $("#remover-formacao").on("click", function () {
        $("#formacoes-container .formacao").last().remove();
        if ($("#formacoes-container .formacao").length === 0) {
            $(this).addClass("hidden");
        }
    });

    // Adicionar experiência
    $("#adicionar-experiencia").on("click", function () {
        $("#experiencias-container").append(`
            <div class="experiencia">
                <label>Nome da Empresa</label>
                <input type="text" name="empresa[]" placeholder="Ex: Tech Solutions">
                <label>Cargo</label>
                <input type="text" name="cargo[]" placeholder="Ex: Desenvolvedor Frontend">
                <label>Tempo de Empresa</label>
                <input type="text" name="tempo[]" placeholder="Ex: 1 ano e 6 meses">
                <label>Atividades Exercidas</label>
                <textarea name="atividades[]" placeholder="Descreva suas responsabilidades..."></textarea>
            </div>
        `);
        $("#remover-experiencia").removeClass("hidden");
    });

    $("#remover-experiencia").on("click", function () {
        $("#experiencias-container .experiencia").last().remove();
        if ($("#experiencias-container .experiencia").length === 0) {
            $(this).addClass("hidden");
        }
    });

    // Adicionar idioma
    $("#adicionar-idioma").on("click", function () {
        $("#idiomas-container").append(`
            <div class="idioma">
                <label>Idioma</label>
                <input type="text" name="idioma[]" placeholder="Ex: Inglês">
                <label>Nível</label>
                <select name="nivelIdioma[]">
                    <option disabled selected hidden>Selecione</option>
                    <option value="basico">Básico</option>
                    <option value="intermediario">Intermediário</option>
                    <option value="avancado">Avançado</option>
                    <option value="fluente">Fluente</option>
                </select>
            </div>
        `);
        $("#remover-idioma").removeClass("hidden");
    });

    $("#remover-idioma").on("click", function () {
        $("#idiomas-container .idioma").last().remove();
        if ($("#idiomas-container .idioma").length === 0) {
            $(this).addClass("hidden");
        }
    });

    // Adicionar habilidade
    function adicionarHabilidade() {
        const habilidade = $("#nova-habilidade").val().trim();
        if (habilidade === "") return;

        const tag = $(`
            <span class="habilidade-tag">
                ${habilidade} <button type="button" class="remover-habilidade">×</button>
                <input type="hidden" name="habilidades[]" value="${habilidade}">
            </span>
        `);

        $("#habilidades-lista").append(tag);
        $("#nova-habilidade").val("");
    }

    // Delegação de evento para remover habilidade
    $("#habilidades-lista").on("click", ".remover-habilidade", function () {
        $(this).parent(".habilidade-tag").remove();
    });

    $("#btn-adicionar-habilidade").on("click", adicionarHabilidade);
    $("#nova-habilidade").on("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            adicionarHabilidade();
        }
    });

    // Validação de CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, "");
        if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

        let soma = 0;
        for (let i = 0; i < 9; i++) soma += parseInt(cpf[i]) * (10 - i);
        let resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf[9])) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) soma += parseInt(cpf[i]) * (11 - i);
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;

        return resto === parseInt(cpf[10]);
    }

    // Envio do formulário com validações
    $("#curriculoForm").on("submit", function (e) {
        // Reconstruir inputs hidden de habilidades com base nas tags visíveis
        $("#habilidades-lista input[name='habilidades[]']").remove();
        $(".habilidade-tag").each(function() {
            const habilidade = $(this).text().slice(0, -2).trim(); // Remove o " ×" do texto
            const inputHidden = $('<input>').attr({
                type: 'hidden',
                name: 'habilidades[]',
                value: habilidade
            });
            $(this).append(inputHidden);
        });

        // Log das habilidades antes do envio para depuração
        const habilidadesEnviadas = $('input[name="habilidades[]"]').map(function() {
            return $(this).val();
        }).get();
        console.log("Habilidades enviadas no formulário:", habilidadesEnviadas);

        const nome = $("#nome").val().trim();
        const cpf = $("#cpf").val().trim();
        const email = $("#email").val().trim();
        const telefone = $("#numero").val().trim();

        if (!nome || !cpf || !email || !telefone) {
            alert("Por favor, preencha todos os campos obrigatórios.");
            e.preventDefault();
            return;
        }

        if (!validarCPF(cpf)) {
            $("#cpf").addClass("input-erro");
            alert("CPF inválido!");
            e.preventDefault();
            return;
        } else {
            $("#cpf").removeClass("input-erro");
        }

        // Validação da data de nascimento
        var nascimento = $("#nascimento").val();
        var partes = nascimento.split("/");
        var dataNascimento = new Date(partes[2], partes[1] - 1, partes[0]);
        var hoje = new Date();
        var idade = hoje.getFullYear() - dataNascimento.getFullYear();
        var m = hoje.getMonth() - dataNascimento.getMonth();
        if (m < 0 || (m === 0 && hoje.getDate() < dataNascimento.getDate())) {
            idade--;
        }

        if (idade < 16) {
            alert("Este site não gera currículo para trabalho infantojuvenil.");
            e.preventDefault();
            return;
        }

        const possuiFormacao = $('input[name="curso[]"]').toArray().some((input) => input.value.trim() !== "");
        if (!possuiFormacao) {
            alert("Adicione pelo menos uma formação.");
            e.preventDefault();
            return;
        }

        if (!$("#experiencias-container").hasClass("hidden")) {
            const possuiExperiencia = $('input[name="empresa[]"]').toArray().some((input) => input.value.trim() !== "");
            if (!possuiExperiencia) {
                alert("Adicione pelo menos uma experiência.");
                e.preventDefault();
                return;
            }

            const tempos = $('input[name="tempo[]"]');
            for (let i = 0; i < tempos.length; i++) {
                if (tempos[i].value.trim() === "") {
                    alert("Preencha o tempo de empresa para cada experiência.");
                    e.preventDefault();
                    return;
                }
            }
        }

        const habilidades = $('input[name="habilidades[]"]');
        if (habilidades.length === 0) {
            alert("Adicione pelo menos uma habilidade.");
            e.preventDefault();
            return;
        }

        const anosInicio = document.querySelectorAll('input[name="anoInicio[]"]');
        const anosConclusao = document.querySelectorAll('input[name="anoConclusao[]"]');
        const anoAtual = new Date().getFullYear();

        for (let i = 0; i < anosConclusao.length; i++) {
            const inicio = anosInicio[i].value;
            const conclusao = anosConclusao[i].value;

            if (conclusao && (isNaN(conclusao) || parseInt(conclusao) > anoAtual)) {
                alert("Ano de conclusão inválido.");
                e.preventDefault();
                return;
            }

            if (inicio && conclusao && parseInt(inicio) > parseInt(conclusao)) {
                alert("O ano de início não pode ser maior que o de conclusão.");
                e.preventDefault();
                return;
            }
        }

        alert("Formulário enviado com sucesso!");
    });
});
