<?php
// controllers/UserController.php
// Controlador para funcionalidades do usuário

class UserController {
    private $db;
    private $curriculo;

    public function __construct($db) {
        $this->db = $db;
        require_once 'controllers/AuthController.php';
        require_once 'models/Curriculo.php';
        $this->curriculo = new Curriculo($db);
    }

    // Dashboard do usuário
    public function dashboard() {
        AuthController::requireLogin();
        
        $userId = $_SESSION['user_id'];
        $curriculo = $this->curriculo->getByUser($userId);

        $base_url = '/CadastroDeCurriculo-main';
        extract(['base_url' => $base_url]);
        
        require_once 'views/user/dashboard.php';
    }

    // Exibir formulário de criação de currículo
    public function showCreate() {
        AuthController::requireLogin();
        
        $userId = $_SESSION['user_id'];
        
        // Verificar se usuário já possui currículo
        if ($this->curriculo->userHasResume($userId)) {
            header("Location: index.php?page=user_dashboard&error=Você já possui um currículo cadastrado");
            exit;
        }
        
        require_once 'views/user/create_resume.php';
    }

    // Processar criação de currículo
    public function storeResume() {
        AuthController::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Verificar se usuário já possui currículo
            if ($this->curriculo->userHasResume($userId)) {
                header("Location: index.php?page=user_dashboard&error=Você já possui um currículo cadastrado");
                exit;
            }

            // Validação básica
            $errors = [];
            if (empty($_POST['nome'])) $errors[] = "Nome é obrigatório";
            if (empty($_POST['cpf'])) $errors[] = "CPF é obrigatório";
            if (empty($_POST['email'])) $errors[] = "Email é obrigatório";
            if (empty($_POST['ddi'])) $errors[] = "DDI é obrigatório";
            if (empty($_POST['ddd'])) $errors[] = "DDD é obrigatório";
            if (empty($_POST['numero'])) $errors[] = "Número de telefone é obrigatório";

            // Verificar se CPF já está cadastrado
            $sql = "SELECT COUNT(*) FROM curriculos WHERE cpf = :cpf";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cpf' => $_POST['cpf']]);
            $cpfCount = $stmt->fetchColumn();
            if ($cpfCount > 0) {
                $errors[] = "CPF já cadastrado";
            }

            if (!empty($errors)) {
                header("Location: index.php?page=create_resume&error=" . urlencode(implode(", ", $errors)));
                exit;
            }

            // Upload da foto
            $fotoPath = '';
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fotoPath = $this->uploadFoto($_FILES['foto']);
                if (!$fotoPath) {
                    header("Location: index.php?page=create_resume&error=Erro no upload da foto");
                    exit;
                }
            }

            // Processar dados do formulário
            $this->curriculo->id_usuario = $userId;
            $this->curriculo->nome = htmlspecialchars(trim($_POST['nome']));
            $this->curriculo->cpf = htmlspecialchars(trim($_POST['cpf']));
            $this->curriculo->email = htmlspecialchars(trim($_POST['email']));
            $this->curriculo->ddi = htmlspecialchars(trim($_POST['ddi']));
            $this->curriculo->ddd = htmlspecialchars(trim($_POST['ddd']));
            $this->curriculo->telefone = htmlspecialchars(trim($_POST['numero']));
            $this->curriculo->nascimento = $_POST['nascimento'] ?: null;
            $this->curriculo->genero = $_POST['genero'] ?: null;
            $this->curriculo->estado_civil = $_POST['estadoCivil'] ?: null;
            $this->curriculo->nacionalidade = htmlspecialchars(trim($_POST['nacionalidade'] ?? ''));
            $this->curriculo->cidade = htmlspecialchars(trim($_POST['cidade'] ?? ''));
            $this->curriculo->estado = htmlspecialchars(trim($_POST['estado'] ?? ''));
            $this->curriculo->linkedin = htmlspecialchars(trim($_POST['linkedin'] ?? ''));
            $this->curriculo->github = htmlspecialchars(trim($_POST['github'] ?? ''));
            $this->curriculo->site = htmlspecialchars(trim($_POST['site'] ?? ''));
            $this->curriculo->foto = $fotoPath;

            // Processar formações
            $formacoes = [];
            if (isset($_POST['curso']) && is_array($_POST['curso'])) {
                for ($i = 0; $i < count($_POST['curso']); $i++) {
                    if (!empty($_POST['curso'][$i])) {
                        $formacoes[] = [
                            'curso' => htmlspecialchars(trim($_POST['curso'][$i])),
                            'instituicao' => htmlspecialchars(trim($_POST['instituicao'][$i] ?? '')),
                            'anoInicio' => htmlspecialchars(trim($_POST['anoInicio'][$i] ?? '')),
                            'anoConclusao' => htmlspecialchars(trim($_POST['anoConclusao'][$i] ?? ''))
                        ];
                    }
                }
            }
            $this->curriculo->formacoes = $formacoes;

            // Processar experiências
            $experiencias = [];
            if (isset($_POST['empresa']) && is_array($_POST['empresa'])) {
                for ($i = 0; $i < count($_POST['empresa']); $i++) {
                    if (!empty($_POST['empresa'][$i])) {
                        $experiencias[] = [
                            'empresa' => htmlspecialchars(trim($_POST['empresa'][$i])),
                            'cargo' => htmlspecialchars(trim($_POST['cargo'][$i] ?? '')),
                            'tempo' => htmlspecialchars(trim($_POST['tempo'][$i] ?? '')),
                            'atividades' => htmlspecialchars(trim($_POST['atividades'][$i] ?? ''))
                        ];
                    }
                }
            }
            $this->curriculo->experiencias = $experiencias;

            // Processar habilidades
            $habilidades = [];
            if (isset($_POST['habilidades']) && is_array($_POST['habilidades'])) {
                foreach ($_POST['habilidades'] as $habilidade) {
                    if (!empty(trim($habilidade))) {
                        $habilidades[] = htmlspecialchars(trim($habilidade));
                    }
                }
            }
            $this->curriculo->habilidades = $habilidades;

            // Processar idiomas
            $idiomas = [];
            if (isset($_POST['idioma']) && is_array($_POST['idioma'])) {
                for ($i = 0; $i < count($_POST['idioma']); $i++) {
                    if (!empty($_POST['idioma'][$i])) {
                        $idiomas[] = [
                            'idioma' => htmlspecialchars(trim($_POST['idioma'][$i])),
                            'nivel' => htmlspecialchars(trim($_POST['nivelIdioma'][$i] ?? ''))
                        ];
                    }
                }
            }
            $this->curriculo->idiomas = $idiomas;

            $dados = [
                'nome' => $this->curriculo->nome,
                'cpf' => $this->curriculo->cpf,
                'email' => $this->curriculo->email,
                'ddi' => $this->curriculo->ddi,
                'ddd' => $this->curriculo->ddd,
                'telefone' => $this->curriculo->telefone,
                'nascimento' => $this->curriculo->nascimento,
                'genero' => $this->curriculo->genero,
                'estado_civil' => $this->curriculo->estado_civil,
                'nacionalidade' => $this->curriculo->nacionalidade,
                'cidade' => $this->curriculo->cidade,
                'estado' => $this->curriculo->estado,
                'formacoes' => $this->curriculo->formacoes,
                'experiencias' => $this->curriculo->experiencias,
                'habilidades' => $this->curriculo->habilidades,
                'idiomas' => $this->curriculo->idiomas,
                'linkedin' => $this->curriculo->linkedin,
                'github' => $this->curriculo->github,
                'site' => $this->curriculo->site,
                'foto' => $this->curriculo->foto
            ];

            if ($this->curriculo->create($dados, $userId)) {
                header("Location: index.php?page=user_dashboard&success=Currículo criado com sucesso!");
                exit;
            } else {
                header("Location: index.php?page=create_resume&error=Erro ao criar currículo");
                exit;
            }
        }
    }

    // Exibir formulário de edição de currículo
    public function showEdit() {
        AuthController::requireLogin();
        
        $userId = $_SESSION['user_id'];
        $curriculo = $this->curriculo->getByUser($userId);
        
        if (!$curriculo) {
            header("Location: index.php?page=user_dashboard&error=Currículo não encontrado");
            exit;
        }
        
        // Decodificar campos JSON para arrays
        $curriculo['formacoes'] = json_decode($curriculo['formacoes'], true) ?: [];
        $curriculo['experiencias'] = json_decode($curriculo['experiencias'], true) ?: [];
        $curriculo['habilidades'] = json_decode($curriculo['habilidades'], true) ?: [];
        $curriculo['idiomas'] = json_decode($curriculo['idiomas'], true) ?: [];
        
        // Remover global e usar extract para passar variáveis para a view
        extract($curriculo);
        require_once 'views/user/edit_resume.php';
    }

    // Processar atualização de currículo
    public function updateResume() {
        AuthController::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $curriculoData = $this->curriculo->getByUser($userId);
            
            if (!$curriculoData) {
                header("Location: index.php?page=user_dashboard&error=Currículo não encontrado");
                exit;
            }

            // Validação básica
            $errors = [];
            if (empty($_POST['nome'])) $errors[] = "Nome é obrigatório";
            if (empty($_POST['cpf'])) $errors[] = "CPF é obrigatório";
            if (empty($_POST['email'])) $errors[] = "Email é obrigatório";
            if (empty($_POST['ddi'])) $errors[] = "DDI é obrigatório";
            if (empty($_POST['ddd'])) $errors[] = "DDD é obrigatório";
            if (empty($_POST['numero'])) $errors[] = "Número de telefone é obrigatório";

            if (!empty($errors)) {
                header("Location: index.php?page=edit_resume&error=" . urlencode(implode(", ", $errors)));
                exit;
            }

            // Upload da foto (se nova foto foi enviada)
            $fotoPath = $curriculoData['foto']; // Manter foto atual por padrão
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $newFotoPath = $this->uploadFoto($_FILES['foto']);
                if ($newFotoPath) {
                    // Deletar foto antiga se existir
                    if ($fotoPath && file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                    $fotoPath = $newFotoPath;
                }
            }

            // Montar array de dados para update
            $dados = [];
            $dados['nome'] = htmlspecialchars(trim($_POST['nome']));
            $dados['cpf'] = htmlspecialchars(trim($_POST['cpf']));
            $dados['email'] = htmlspecialchars(trim($_POST['email']));
            $dados['ddi'] = htmlspecialchars(trim($_POST['ddi']));
            $dados['ddd'] = htmlspecialchars(trim($_POST['ddd']));
            $dados['telefone'] = htmlspecialchars(trim($_POST['numero']));
            $dados['nascimento'] = $_POST['nascimento'] ?: null;
            $dados['genero'] = $_POST['genero'] ?: null;
            $dados['estado_civil'] = $_POST['estadoCivil'] ?: null;
            $dados['nacionalidade'] = htmlspecialchars(trim($_POST['nacionalidade'] ?? ''));
            $dados['cidade'] = htmlspecialchars(trim($_POST['cidade'] ?? ''));
            $dados['estado'] = htmlspecialchars(trim($_POST['estado'] ?? ''));
            $dados['linkedin'] = htmlspecialchars(trim($_POST['linkedin'] ?? ''));
            $dados['github'] = htmlspecialchars(trim($_POST['github'] ?? ''));
            $dados['site'] = htmlspecialchars(trim($_POST['site'] ?? ''));
            $dados['foto'] = $fotoPath;

            // Processar formações
            $formacoes = [];
            if (isset($_POST['curso']) && is_array($_POST['curso'])) {
                for ($i = 0; $i < count($_POST['curso']); $i++) {
                    if (!empty($_POST['curso'][$i])) {
                        $formacoes[] = [
                            'curso' => htmlspecialchars(trim($_POST['curso'][$i])),
                            'instituicao' => htmlspecialchars(trim($_POST['instituicao'][$i] ?? '')),
                            'anoInicio' => htmlspecialchars(trim($_POST['anoInicio'][$i] ?? '')),
                            'anoConclusao' => htmlspecialchars(trim($_POST['anoConclusao'][$i] ?? ''))
                        ];
                    }
                }
            }
            $dados['formacoes'] = $formacoes;

            // Processar experiências
            $experiencias = [];
            if (isset($_POST['empresa']) && is_array($_POST['empresa'])) {
                for ($i = 0; $i < count($_POST['empresa']); $i++) {
                    if (!empty($_POST['empresa'][$i])) {
                        $experiencias[] = [
                            'empresa' => htmlspecialchars(trim($_POST['empresa'][$i])),
                            'cargo' => htmlspecialchars(trim($_POST['cargo'][$i] ?? '')),
                            'tempo' => htmlspecialchars(trim($_POST['tempo'][$i] ?? '')),
                            'atividades' => htmlspecialchars(trim($_POST['atividades'][$i] ?? ''))
                        ];
                    }
                }
            }
            $dados['experiencias'] = $experiencias;

            // Processar habilidades
            $habilidades = [];
            if (isset($_POST['habilidades']) && is_array($_POST['habilidades'])) {
                foreach ($_POST['habilidades'] as $habilidade) {
                    $habilidadeTrim = trim($habilidade);
                    if ($habilidadeTrim !== '') {
                        $habilidades[] = htmlspecialchars($habilidadeTrim);
                    }
                }
            }
            $dados['habilidades'] = $habilidades;

            // Processar idiomas
            $idiomas = [];
            if (isset($_POST['idioma']) && is_array($_POST['idioma'])) {
                for ($i = 0; $i < count($_POST['idioma']); $i++) {
                    if (!empty($_POST['idioma'][$i])) {
                        $idiomas[] = [
                            'idioma' => htmlspecialchars(trim($_POST['idioma'][$i])),
                            'nivel' => htmlspecialchars(trim($_POST['nivelIdioma'][$i] ?? ''))
                        ];
                    }
                }
            }
            $dados['idiomas'] = $idiomas;

            if ($this->curriculo->update($dados, $curriculoData['id'], $userId)) {
                header("Location: index.php?page=user_dashboard&success=Currículo atualizado com sucesso!");
                exit;
            } else {
                header("Location: index.php?page=edit_resume&error=Erro ao atualizar currículo");
                exit;
            }
        }
    }

    // Deletar currículo do usuário
    public function deleteResume() {
        AuthController::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $curriculoData = $this->curriculo->getByUser($userId);
            
            if (!$curriculoData) {
                header("Location: index.php?page=user_dashboard&error=Currículo não encontrado");
                exit;
            }

            $this->curriculo->id = $curriculoData['id'];
            $this->curriculo->id_usuario = $userId;

            // Deletar foto se existir
            if ($curriculoData['foto'] && file_exists($curriculoData['foto'])) {
                unlink($curriculoData['foto']);
            }

            if ($this->curriculo->delete($this->curriculo->id, $userId)) {
                header("Location: index.php?page=user_dashboard&success=Currículo deletado com sucesso!");
                exit;
            } else {
                header("Location: index.php?page=user_dashboard&error=Erro ao deletar currículo");
                exit;
            }
        }
    }

    // Upload de foto
    private function uploadFoto($file) {
        $uploadDir = 'uploads/';
        
        // Criar diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validar tipo de arquivo
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        // Validar tamanho (máximo 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }

        // Gerar nome único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }

        return false;
    }
}
?>
