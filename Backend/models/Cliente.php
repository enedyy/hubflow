<?php
class Cliente {
    private $conn;
    private $table_name = 'Clientes';

    public $clienteId;
    public $nome;
    public $endereco;
    public $telefone;
    public $email;
    public $dataNascimento;
    public $usuarioId;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (Nome, Endereco, Telefone, Email, DataNascimento) 
                  VALUES 
                  (:nome, :endereco, :telefone, :email, :dataNascimento)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':endereco', $this->endereco);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':dataNascimento', $this->dataNascimento);

        if($stmt->execute()) {
            $this->clienteId = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function vincularUsuario($usuarioId) {
        $query = "UPDATE " . $this->table_name . " 
                  SET UsuarioID = :usuarioId 
                  WHERE ClienteID = :clienteId";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuarioId', $usuarioId);
        $stmt->bindParam(':clienteId', $this->clienteId);
        
        return $stmt->execute();
    }
}