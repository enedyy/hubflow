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

    public function __construct($db) {
        $this->conn = $db;
    }

    public function cadastrar() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (Email, Senha, TipoUsuario, ClienteID, FuncionarioID) 
                  VALUES 
                  (:email, :senha, :tipoUsuario, :clienteId, :funcionarioId)";

        $stmt = $this->conn->prepare($query);

        // Hash da senha
        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':tipoUsuario', $this->tipoUsuario);
        $stmt->bindParam(':clienteId', $this->clienteId);
        $stmt->bindParam(':funcionarioId', $this->funcionarioId);

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
        $query = "SELECT * FROM " . $this->table_name . " WHERE Email = :email";
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
}