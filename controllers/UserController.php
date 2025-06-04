<?php
// controllers/UserController.php
// Controlador para funcionalidades do usuário

require_once __DIR__ . '/AuthController.php';

class UserController {
    private $db;
    private $curriculo;

    public function __construct($db) {
        $this->db = $db;
        require_once __DIR__ . '/../models/Curriculo.php';
        $this->curriculo = new Curriculo($db);
    }

    // Dashboard do usuário
    public function dashboard() {
        AuthController::requireLogin();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'];
        $userName = $_SESSION['user_name'] ?? 'Usuário';
        $curriculo = $this->curriculo->getByUser($userId);

        // Decodificar campos JSON para arrays e garantir arrays válidos
        if ($curriculo) {
            $curriculo['formacoes'] = is_string($curriculo['formacoes']) ? json_decode($curriculo['formacoes'], true) : $curriculo['formacoes'];
            $curriculo['formacoes'] = is_array($curriculo['formacoes']) ? $curriculo['formacoes'] : [];

            $curriculo['experiencias'] = is_string($curriculo['experiencias']) ? json_decode($curriculo['experiencias'], true) : $curriculo['experiencias'];
            $curriculo['experiencias'] = is_array($curriculo['experiencias']) ? $curriculo['experiencias'] : [];

            $curriculo['habilidades'] = is_string($curriculo['habilidades']) ? json_decode($curriculo['habilidades'], true) : $curriculo['habilidades'];
            $curriculo['habilidades'] = is_array($curriculo['habilidades']) ? $curriculo['habilidades'] : [];

            $curriculo['idiomas'] = is_string($curriculo['idiomas']) ? json_decode($curriculo['idiomas'], true) : $curriculo['idiomas'];
            $curriculo['idiomas'] = is_array($curriculo['idiomas']) ? $curriculo['idiomas'] : [];

            // Extrair variáveis individuais para a view
            $nome = $curriculo['nome'] ?? '';
            $email = $curriculo['email'] ?? '';
            $telefone = $curriculo['telefone'] ?? '';
            $cidade = $curriculo['cidade'] ?? '';
            $estado = $curriculo['estado'] ?? '';
            $formacoes = $curriculo['formacoes'];
            $experiencias = $curriculo['experiencias'];
            $habilidades = $curriculo['habilidades'];
            $idiomas = $curriculo['idiomas'];
            $foto = $curriculo['foto'] ?? '';
        } else {
            // Caso não tenha currículo, inicializar variáveis vazias para evitar erros na view
            $nome = '';
            $email = '';
            $telefone = '';
            $cidade = '';
            $estado = '';
            $formacoes = [];
            $experiencias = [];
            $habilidades = [];
            $idiomas = [];
            $foto = '';
        }

        require_once 'views/user/dashboard.php';
    }

    // Exibir formulário de edição do currículo
    public function showEdit() {
        AuthController::requireLogin();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'];
        $curriculo = $this->curriculo->getByUser($userId);

        if (!$curriculo) {
            header("Location: index.php?page=user_dashboard&error=" . urlencode("Currículo não encontrado."));
            exit;
        }

        // Decodificar campos JSON para arrays
        $curriculo['formacoes'] = json_decode($curriculo['formacoes'], true) ?: [];
        $curriculo['experiencias'] = json_decode($curriculo['experiencias'], true) ?: [];
        $curriculo['habilidades'] = json_decode($curriculo['habilidades'], true) ?: [];
        $curriculo['idiomas'] = json_decode($curriculo['idiomas'], true) ?: [];

        // Extrair variáveis individuais para a view
        $nome = $curriculo['nome'] ?? '';
        $cpf = $curriculo['cpf'] ?? '';
        $email = $curriculo['email'] ?? '';
        $ddi = $curriculo['ddi'] ?? '';
        $ddd = $curriculo['ddd'] ?? '';
        $telefone = $curriculo['telefone'] ?? '';
        $nascimento = $curriculo['nascimento'] ?? '';
        $genero = $curriculo['genero'] ?? '';
        $estado_civil = $curriculo['estado_civil'] ?? '';
        $nacionalidade = $curriculo['nacionalidade'] ?? '';
        $cidade = $curriculo['cidade'] ?? '';
        $estado = $curriculo['estado'] ?? '';
        $formacoes = $curriculo['formacoes'];
        $experiencias = $curriculo['experiencias'];
        $habilidades = $curriculo['habilidades'];
        $idiomas = $curriculo['idiomas'];
        $foto = $curriculo['foto'] ?? '';
        $curriculo_linkedin = $curriculo['linkedin'] ?? '';
        $curriculo_github = $curriculo['github'] ?? '';
        $curriculo_site = $curriculo['site'] ?? '';

        require_once 'views/user/edit_resume.php';
    }

    // Processar exclusão do currículo do usuário
    public function deleteResume() {
        AuthController::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $userId = $_SESSION['user_id'];
            $curriculo = $this->curriculo->getByUser($userId);

            if (!$curriculo) {
                header("Location: index.php?page=user_dashboard&error=" . urlencode("Currículo não encontrado."));
                exit;
            }

            // Deletar foto se existir
            if (!empty($curriculo['foto']) && file_exists($curriculo['foto'])) {
                unlink($curriculo['foto']);
            }

            if ($this->curriculo->delete($curriculo['id'], $userId)) {
                header("Location: index.php?page=user_dashboard&success=Currículo deletado com sucesso.");
                exit;
            } else {
                header("Location: index.php?page=user_dashboard&error=Erro ao deletar currículo.");
                exit;
            }
        } else {
            header("Location: index.php?page=user_dashboard");
            exit;
        }
    }

    // Exibir formulário para criação de novo currículo
    public function showCreate() {
        AuthController::requireLogin();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        require_once 'views/user/create_resume.php';
        require_once __DIR__ . '/../models/Curriculo.php';
    }

    // Processar criação do currículo
    public function storeResume() {
        AuthController::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=user_dashboard");
            exit;
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'];

        // Validação básica dos dados recebidos
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $ddi = trim($_POST['ddi'] ?? '');
        $ddd = trim($_POST['ddd'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $nascimento = trim($_POST['nascimento'] ?? '');
        $genero = trim($_POST['genero'] ?? null);
        $estado_civil = trim($_POST['estadoCivil'] ?? null);
        $nacionalidade = trim($_POST['nacionalidade'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = trim($_POST['estado'] ?? '');
        $linkedin = trim($_POST['linkedin'] ?? '');
        $github = trim($_POST['github'] ?? '');
        $site = trim($_POST['site'] ?? '');
        $resumo_experiencia = trim($_POST['descricaoExp'] ?? '');

        // Receber dados das formações, experiências, habilidades e idiomas
        $formacoes = $_POST['curso'] ?? [];
        $instituicoes = $_POST['instituicao'] ?? [];
        $anoInicio = $_POST['anoInicio'] ?? [];
        $anoConclusao = $_POST['anoConclusao'] ?? [];

        $experiencias = $_POST['empresa'] ?? [];
        $cargos = $_POST['cargo'] ?? [];
        $tempos = $_POST['tempo'] ?? [];
        $atividades = $_POST['atividades'] ?? [];

        $habilidades = $_POST['habilidades'] ?? [];

        $idiomas = $_POST['idioma'] ?? [];
        $nivelIdiomas = $_POST['nivelIdioma'] ?? [];

        // Montar arrays associativos para formações, experiências e idiomas
        $formacoes_array = [];
        for ($i = 0; $i < count($formacoes); $i++) {
            if (trim($formacoes[$i]) !== '') {
                $formacoes_array[] = [
                    'curso' => trim($formacoes[$i]),
                    'instituicao' => trim($instituicoes[$i] ?? ''),
                    'anoInicio' => trim($anoInicio[$i] ?? ''),
                    'anoConclusao' => trim($anoConclusao[$i] ?? ''),
                ];
            }
        }

        $experiencias_array = [];
        for ($i = 0; $i < count($experiencias); $i++) {
            if (trim($experiencias[$i]) !== '') {
                $experiencias_array[] = [
                    'empresa' => trim($experiencias[$i]),
                    'cargo' => trim($cargos[$i] ?? ''),
                    'tempo' => trim($tempos[$i] ?? ''),
                    'atividades' => trim($atividades[$i] ?? ''),
                ];
            }
        }

        $idiomas_array = [];
        for ($i = 0; $i < count($idiomas); $i++) {
            if (trim($idiomas[$i]) !== '') {
                $idiomas_array[] = [
                    'idioma' => trim($idiomas[$i]),
                    'nivel' => trim($nivelIdiomas[$i] ?? ''),
                ];
            }
        }

        // Processar upload da foto
        $fotoPath = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileTmpPath = $_FILES['foto']['tmp_name'];
            $fileName = basename($_FILES['foto']['name']);
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = uniqid() . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $fotoPath = $destPath;
            }
        }

        // Montar dados para salvar
        $data = [
            'user_id' => $userId,
            'nome' => $nome,
            'cpf' => $cpf,
            'email' => $email,
            'ddi' => $ddi,
            'ddd' => $ddd,
            'telefone' => $telefone,
            'nascimento' => $nascimento,
            'genero' => $genero,
            'estado_civil' => $estado_civil,
            'nacionalidade' => $nacionalidade,
            'cidade' => $cidade,
            'estado' => $estado,
            'formacoes' => json_encode($formacoes_array),
            'experiencias' => json_encode($experiencias_array),
            'habilidades' => json_encode($habilidades),
            'idiomas' => json_encode($idiomas_array),
            'linkedin' => $linkedin,
            'github' => $github,
            'site' => $site,
            'foto' => $fotoPath,
            'resumo_experiencia' => $resumo_experiencia,
        ];

        // Salvar no banco
        $result = $this->curriculo->create($data, $userId);

        if ($result) {
            header("Location: index.php?page=user_dashboard&success=" . urlencode("Currículo criado com sucesso."));
            exit;
        } else {
            header("Location: index.php?page=user_create&error=" . urlencode("Erro ao criar currículo."));
            exit;
        }
    }
    // Processar atualização do currículo
    public function updateResume() {
        AuthController::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=user_dashboard");
            exit;
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'];

        $curriculoExistente = $this->curriculo->getByUser($userId);
        if (!$curriculoExistente) {
            header("Location: index.php?page=user_dashboard&error=" . urlencode("Currículo não encontrado."));
            exit;
        }

        // Validação básica dos dados recebidos
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $ddi = trim($_POST['ddi'] ?? '');
        $ddd = trim($_POST['ddd'] ?? '');
        $telefone = trim($_POST['numero'] ?? '');
        $nascimento = trim($_POST['nascimento'] ?? '');
        $genero = trim($_POST['genero'] ?? '');
        $estado_civil = trim($_POST['estadoCivil'] ?? '');
        $nacionalidade = trim($_POST['nacionalidade'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = trim($_POST['estado'] ?? '');
        $linkedin = trim($_POST['linkedin'] ?? '');
        $github = trim($_POST['github'] ?? '');
        $site = trim($_POST['site'] ?? '');
        $resumo_experiencia = trim($_POST['descricaoExp'] ?? '');

        // Receber dados das formações, experiências, habilidades e idiomas
        $formacoes = $_POST['curso'] ?? [];
        $instituicoes = $_POST['instituicao'] ?? [];
        $anoInicio = $_POST['anoInicio'] ?? [];
        $anoConclusao = $_POST['anoConclusao'] ?? [];

        $experiencias = $_POST['empresa'] ?? [];
        $cargos = $_POST['cargo'] ?? [];
        $tempos = $_POST['tempo'] ?? [];
        $atividades = $_POST['atividades'] ?? [];

        $habilidades = $_POST['habilidades'] ?? [];

        $idiomas = $_POST['idioma'] ?? [];
        $nivelIdiomas = $_POST['nivelIdioma'] ?? [];

        // Montar arrays associativos para formações, experiências e idiomas
        $formacoes_array = [];
        for ($i = 0; $i < count($formacoes); $i++) {
            if (trim($formacoes[$i]) !== '') {
                $formacoes_array[] = [
                    'curso' => trim($formacoes[$i]),
                    'instituicao' => trim($instituicoes[$i] ?? ''),
                    'anoInicio' => trim($anoInicio[$i] ?? ''),
                    'anoConclusao' => trim($anoConclusao[$i] ?? ''),
                ];
            }
        }

        $experiencias_array = [];
        for ($i = 0; $i < count($experiencias); $i++) {
            if (trim($experiencias[$i]) !== '') {
                $experiencias_array[] = [
                    'empresa' => trim($experiencias[$i]),
                    'cargo' => trim($cargos[$i] ?? ''),
                    'tempo' => trim($tempos[$i] ?? ''),
                    'atividades' => trim($atividades[$i] ?? ''),
                ];
            }
        }

        $idiomas_array = [];
        for ($i = 0; $i < count($idiomas); $i++) {
            if (trim($idiomas[$i]) !== '') {
                $idiomas_array[] = [
                    'idioma' => trim($idiomas[$i]),
                    'nivel' => trim($nivelIdiomas[$i] ?? ''),
                ];
            }
        }

        // Processar upload da foto
        $fotoPath = $curriculoExistente['foto'] ?? '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileTmpPath = $_FILES['foto']['tmp_name'];
            $fileName = basename($_FILES['foto']['name']);
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = uniqid() . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Excluir foto antiga se existir
                if (!empty($fotoPath) && file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
                $fotoPath = $destPath;
            }
        }

        // Montar dados para atualizar
        $data = [
            'nome' => $nome,
            'cpf' => $cpf,
            'email' => $email,
            'ddi' => $ddi,
            'ddd' => $ddd,
            'telefone' => $telefone,
            'nascimento' => $nascimento,
            'genero' => $genero,
            'estado_civil' => $estado_civil,
            'nacionalidade' => $nacionalidade,
            'cidade' => $cidade,
            'estado' => $estado,
            'formacoes' => json_encode($formacoes_array),
            'experiencias' => json_encode($experiencias_array),
            'habilidades' => json_encode($habilidades),
            'idiomas' => json_encode($idiomas_array),
            'linkedin' => $linkedin,
            'github' => $github,
            'site' => $site,
            'foto' => $fotoPath,
            'resumo_experiencia' => $resumo_experiencia,
        ];

        // Atualizar no banco
        $result = $this->curriculo->update($data, $curriculoExistente['id'], $userId);

        if ($result) {
            header("Location: index.php?page=user_dashboard&success=" . urlencode("Currículo atualizado com sucesso."));
            exit;
        } else {
            header("Location: index.php?page=edit_resume&error=" . urlencode("Erro ao atualizar currículo."));
            exit;
        }
    }
}
?>
