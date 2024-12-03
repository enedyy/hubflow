<?php
class Usuario {
    private $conn;
    private $table_name = 'Usuarios';

    public $usuarioId;
    public $email;
    public $senha;
    public $tipoUsuario;
    public $clienteId;
    public $funcionarioId;
    public $empresaId;  // Adicionado

    public function __construct($db) {
        $this->conn = $db;
    }

    public function cadastrar() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (Email, Senha, TipoUsuario, ClienteID, FuncionarioID, EmpresaID) 
                  VALUES 
                  (:email, :senha, :tipoUsuario, :clienteId, :funcionarioId, :empresaId)";

        $stmt = $this->conn->prepare($query);

        // Hash da senha
        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':tipoUsuario', $this->tipoUsuario);
        $stmt->bindParam(':clienteId', $this->clienteId);
        $stmt->bindParam(':funcionarioId', $this->funcionarioId);
        $stmt->bindParam(':empresaId', $this->empresaId);

        if($stmt->execute()) {
            $this->usuarioId = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function verificarEmailExistente() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE Email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function login() {
        $query = "SELECT u.*, 
                  c.Nome as NomeCliente,
                  e.NomeEmpresa as NomeEmpresa
                  FROM " . $this->table_name . " u
                  LEFT JOIN Clientes c ON u.ClienteID = c.ClienteID
                  LEFT JOIN Empresa e ON u.EmpresaID = e.EmpresaID
                  WHERE u.Email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(password_verify($this->senha, $row['Senha'])) {
                return $row;
            }
        }
        return false;
    }

    public function atualizarSenha($novaSenha) {
        $query = "UPDATE " . $this->table_name . "
                 SET Senha = :senha
                 WHERE UsuarioID = :usuarioId";

        $stmt = $this->conn->prepare($query);
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':usuarioId', $this->usuarioId);

        return $stmt->execute();
    }

    public function atualizarEmail($novoEmail) {
        // Primeiro verifica se o novo email já existe
        $this->email = $novoEmail;
        if($this->verificarEmailExistente()) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . "
                 SET Email = :email
                 WHERE UsuarioID = :usuarioId";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $novoEmail);
        $stmt->bindParam(':usuarioId', $this->usuarioId);

        return $stmt->execute();
    }

    public function deletarConta() {
        $query = "DELETE FROM " . $this->table_name . "
                 WHERE UsuarioID = :usuarioId";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuarioId', $this->usuarioId);

        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE UsuarioID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}