<?php

class Curriculo {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function create($dados, $id_usuario) {
        $sql = "INSERT INTO curriculos (
            nome, cpf, email, ddi, ddd, telefone, nascimento, 
            genero, estado_civil, nacionalidade, cidade, estado,
            formacoes, experiencias, habilidades, idiomas,
            linkedin, github, site, foto, resumo_experiencia, id_usuario
        ) VALUES (
            :nome, :cpf, :email, :ddi, :ddd, :telefone, :nascimento,
            :genero, :estado_civil, :nacionalidade, :cidade, :estado,
            :formacoes, :experiencias, :habilidades, :idiomas,
            :linkedin, :github, :site, :foto, :resumo_experiencia, :id_usuario
        )";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $dados['nome'],
                ':cpf' => $dados['cpf'],
                ':email' => $dados['email'],
                ':ddi' => $dados['ddi'],
                ':ddd' => $dados['ddd'],
                ':telefone' => $dados['telefone'],
                ':nascimento' => $dados['nascimento'],
                ':genero' => $dados['genero'],
                ':estado_civil' => $dados['estado_civil'],
                ':nacionalidade' => $dados['nacionalidade'],
                ':cidade' => $dados['cidade'],
                ':estado' => $dados['estado'],
                ':formacoes' => $dados['formacoes'],
                ':experiencias' => $dados['experiencias'],
                ':habilidades' => $dados['habilidades'],
                ':idiomas' => $dados['idiomas'],
                ':linkedin' => $dados['linkedin'],
                ':github' => $dados['github'],
                ':site' => $dados['site'],
                ':foto' => $dados['foto'],
                ':resumo_experiencia' => $dados['resumo_experiencia'],
                ':id_usuario' => $id_usuario
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao criar currículo: " . $e->getMessage());
            echo "Erro ao criar currículo: " . $e->getMessage();
            return false;
        }
    }
    
    public function update($dados, $id, $id_usuario) {
        $sql = "UPDATE curriculos SET 
            nome = :nome, cpf = :cpf, email = :email,
            ddi = :ddi, ddd = :ddd, telefone = :telefone,
            nascimento = :nascimento, genero = :genero,
            estado_civil = :estado_civil, nacionalidade = :nacionalidade,
            cidade = :cidade, estado = :estado,
            formacoes = :formacoes, experiencias = :experiencias,
            habilidades = :habilidades, idiomas = :idiomas,
            linkedin = :linkedin, github = :github,
            site = :site, foto = :foto, resumo_experiencia = :resumo_experiencia
            WHERE id = :id AND id_usuario = :id_usuario";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $dados['nome'],
                ':cpf' => $dados['cpf'],
                ':email' => $dados['email'],
                ':ddi' => $dados['ddi'],
                ':ddd' => $dados['ddd'],
                ':telefone' => $dados['telefone'],
                ':nascimento' => $dados['nascimento'],
                ':genero' => $dados['genero'],
                ':estado_civil' => $dados['estado_civil'],
                ':nacionalidade' => $dados['nacionalidade'],
                ':cidade' => $dados['cidade'],
                ':estado' => $dados['estado'],
                ':formacoes' => $dados['formacoes'],
                ':experiencias' => $dados['experiencias'],
                ':habilidades' => $dados['habilidades'],
                ':idiomas' => $dados['idiomas'],
                ':linkedin' => $dados['linkedin'],
                ':github' => $dados['github'],
                ':site' => $dados['site'],
                ':foto' => $dados['foto'],
                ':resumo_experiencia' => $dados['resumo_experiencia'],
                ':id' => $id,
                ':id_usuario' => $id_usuario
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function delete($id, $id_usuario) {
        $sql = "DELETE FROM curriculos WHERE id = :id AND id_usuario = :id_usuario";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id, ':id_usuario' => $id_usuario]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getByUser($id_usuario) {
        $sql = "SELECT * FROM curriculos WHERE id_usuario = :id_usuario";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM curriculos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $sql = "SELECT * FROM curriculos";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function userHasResume($id_usuario) {
        $sql = "SELECT COUNT(*) FROM curriculos WHERE id_usuario = :id_usuario";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
