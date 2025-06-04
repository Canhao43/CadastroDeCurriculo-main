document.addEventListener('DOMContentLoaded', function () {
    const curriculoForm = document.getElementById('curriculoForm');

    if (curriculoForm) {
        curriculoForm.addEventListener('submit', function (e) {
            // Validações aqui (exemplo simples)
            let valid = true;
            const nome = document.getElementById('nome');
            if (!nome || nome.value.trim() === '') {
                alert('O campo Nome é obrigatório.');
                valid = false;
            }

            // Se quiser adicionar mais validações, faça aqui

            if (!valid) {
                e.preventDefault();
                return;
            }

            // Se as validações passarem, permitir o envio padrão do formulário
            // Portanto, não chamar e.preventDefault()
        });
    }
});
