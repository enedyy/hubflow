<?php
class Empresa {
   private $conn;
   private $table_name = 'Empresa';

   // Propriedades que correspondem às colunas da tabela
   public $empresaId;
   public $nomeEmpresa;
   public $nomeDono; 
   public $email;
   public $telefone;
   public $endereco;
   public $dataCriacao;
   public $descricao;

   // Construtor que recebe a conexão com o banco
   public function __construct($db) {
       $this->conn = $db;
   }

   // Método para criar uma nova empresa
   public function criar() {
       $query = "INSERT INTO " . $this->table_name . " 
                 (NomeEmpresa, NomeDono, Email, Telefone, Endereco, Descricao) 
                 VALUES 
                 (:nomeEmpresa, :nomeDono, :email, :telefone, :endereco, :descricao)";

       $stmt = $this->conn->prepare($query);

       // Limpa e vincula os valores
       $this->nomeEmpresa = htmlspecialchars(strip_tags($this->nomeEmpresa));
       $this->nomeDono = htmlspecialchars(strip_tags($this->nomeDono));
       $this->email = htmlspecialchars(strip_tags($this->email));
       $this->telefone = htmlspecialchars(strip_tags($this->telefone));
       $this->endereco = htmlspecialchars(strip_tags($this->endereco));
       $this->descricao = htmlspecialchars(strip_tags($this->descricao));

       // Vincula os valores aos parâmetros
       $stmt->bindParam(":nomeEmpresa", $this->nomeEmpresa);
       $stmt->bindParam(":nomeDono", $this->nomeDono);
       $stmt->bindParam(":email", $this->email);
       $stmt->bindParam(":telefone", $this->telefone);
       $stmt->bindParam(":endereco", $this->endereco);
       $stmt->bindParam(":descricao", $this->descricao);

       // Executa a query
       if($stmt->execute()) {
           $this->empresaId = $this->conn->lastInsertId();
           return true;
       }
       return false;
   }

   // Método para atualizar uma empresa existente
   public function atualizar() {
       $query = "UPDATE " . $this->table_name . "
               SET 
                   NomeEmpresa = :nomeEmpresa,
                   NomeDono = :nomeDono,
                   Email = :email,
                   Telefone = :telefone,
                   Endereco = :endereco,
                   Descricao = :descricao
               WHERE 
                   EmpresaID = :empresaId";

       $stmt = $this->conn->prepare($query);

       // Limpa e vincula os valores
       $this->nomeEmpresa = htmlspecialchars(strip_tags($this->nomeEmpresa));
       $this->nomeDono = htmlspecialchars(strip_tags($this->nomeDono));
       $this->email = htmlspecialchars(strip_tags($this->email));
       $this->telefone = htmlspecialchars(strip_tags($this->telefone));
       $this->endereco = htmlspecialchars(strip_tags($this->endereco));
       $this->descricao = htmlspecialchars(strip_tags($this->descricao));
       $this->empresaId = htmlspecialchars(strip_tags($this->empresaId));

       // Vincula os valores
       $stmt->bindParam(":nomeEmpresa", $this->nomeEmpresa);
       $stmt->bindParam(":nomeDono", $this->nomeDono);
       $stmt->bindParam(":email", $this->email);
       $stmt->bindParam(":telefone", $this->telefone);
       $stmt->bindParam(":endereco", $this->endereco);
       $stmt->bindParam(":descricao", $this->descricao);
       $stmt->bindParam(":empresaId", $this->empresaId);

       // Executa a query
       if($stmt->execute()) {
           return true;
       }
       return false;
   }

   // Método para buscar uma empresa pelo ID
   public function buscarPorId($id) {
       $query = "SELECT * FROM " . $this->table_name . " WHERE EmpresaID = :id";
       $stmt = $this->conn->prepare($query);
       $stmt->bindParam(":id", $id);
       $stmt->execute();

       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       if($row) {
           $this->empresaId = $row['EmpresaID'];
           $this->nomeEmpresa = $row['NomeEmpresa'];
           $this->nomeDono = $row['NomeDono'];
           $this->email = $row['Email'];
           $this->telefone = $row['Telefone'];
           $this->endereco = $row['Endereco'];
           $this->dataCriacao = $row['DataCriacao'];
           $this->descricao = $row['Descricao'];
           return true;
       }
       return false;
   }

   // Método para excluir uma empresa
   public function excluir() {
       $query = "DELETE FROM " . $this->table_name . " WHERE EmpresaID = :empresaId";
       $stmt = $this->conn->prepare($query);
       
       $this->empresaId = htmlspecialchars(strip_tags($this->empresaId));
       $stmt->bindParam(":empresaId", $this->empresaId);

       if($stmt->execute()) {
           return true;
       }
       return false;
   }
}
?>