<?php
// models/User.php
// Modelo para gerenciamento de usuários

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar novo usuário
    public function create($nome, $email, $password, $role = 'user') {
        // Verifica se o email já existe
        if ($this->emailExists($email)) {
            return false;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (nome, email, password_hash, role, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            return $stmt->execute([$nome, $email, $password_hash, $role]);
        }
        
        return false;
    }

    // Verificar se email já existe
    public function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->execute([$email]);
            return $stmt->rowCount() > 0;
        }
        
        return false;
    }

    // Autenticar usuário
    public function authenticate($email, $password) {
        $sql = "SELECT id, nome, email, password_hash, role FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch();
                if (password_verify($password, $user['password_hash'])) {
                    unset($user['password_hash']); // Remove o hash da senha antes de retornar
                    return $user;
                }
            }
        }
        
        return false;
    }

    // Buscar usuário por ID
    public function getById($id) {
        $sql = "SELECT id, nome, email, role, created_at FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 1) {
                return $stmt->fetch();
            }
        }
        
        return false;
    }

    // Atualizar dados do usuário
    public function update($id, $nome, $email) {
        $sql = "UPDATE users SET nome = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            return $stmt->execute([$nome, $email, $id]);
        }
        
        return false;
    }

    // Atualizar senha
    public function updatePassword($id, $newPassword) {
        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            return $stmt->execute([$password_hash, $id]);
        }
        
        return false;
    }

    // Deletar usuário
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            return $stmt->execute([$id]);
        }
        
        return false;
    }

    // Verificar se usuário é admin
    public function isAdmin($id) {
        $sql = "SELECT role FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch();
                return $user['role'] === 'admin';
            }
        }
        
        return false;
    }

    // Listar todos os usuários (apenas para admin)
    public function listAll() {
        $sql = "SELECT id, nome, email, role, created_at FROM users ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        
        if ($stmt) {
            return $stmt->fetchAll();
        }
        
        return [];
    }
}
?>
