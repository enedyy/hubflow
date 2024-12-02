<?php
class Empresa {
    private $conn;
    private $table_name = 'Empresa';

    public $empresaId;
    public $nomeEmpresa;
    public $nomeDono;
    public $email;
    public $telefone;
    public $endereco;
    public $descricao;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (NomeEmpresa, NomeDono, Email, Telefone, Endereco, Descricao) 
                  VALUES 
                  (:nomeEmpresa, :nomeDono, :email, :telefone, :endereco, :descricao)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nomeEmpresa', $this->nomeEmpresa);
        $stmt->bindParam(':nomeDono', $this->nomeDono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':endereco', $this->endereco);
        $stmt->bindParam(':descricao', $this->descricao);

        if($stmt->execute()) {
            $this->empresaId = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
}