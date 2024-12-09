<?php
namespace Api\database\mysql\dao;

use Api\database\mysql\model\Servico;
use Api\database\mysql\MySqlPDO;
use PDO;

class ServicoDAO {
    public function getAll() {
        $pdo = MySqlPDO::getInstance();
        $sql = "SELECT * FROM Servicos";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function getByEmpresa($empresaId) {
        $pdo = MySqlPDO::getInstance();
        $sql = "SELECT * FROM Servicos WHERE EmpresaID = :empresaId";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([':empresaId' => $empresaId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function insert(Servico $servico) {
        $pdo = MySqlPDO::getInstance();

        $sql = "INSERT INTO Servicos (NomeServico, Descricao, Preco, Duracao, EmpresaID) 
                VALUES (:NomeServico, :Descricao, :Preco, :Duracao, :EmpresaID)";
        
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':NomeServico' => $servico->NomeServico,
                ':Descricao' => $servico->Descricao,
                ':Preco' => $servico->Preco,
                ':Duracao' => $servico->Duracao,
                ':EmpresaID' => $servico->EmpresaID
            ]);
            return $pdo->lastInsertId();
        } catch(\PDOException $th) {
            var_dump($th->getMessage());
        }
    }

    public function update(Servico $servico) {
        $pdo = MySqlPDO::getInstance();
        
        $sql = "UPDATE Servicos 
                SET NomeServico = :NomeServico, 
                    Descricao = :Descricao, 
                    Preco = :Preco, 
                    Duracao = :Duracao 
                WHERE ServicoID = :ServicoID";
        
        $stmt = $pdo->prepare($sql);

        try {
            return $stmt->execute([
                ':ServicoID' => $servico->ServicoID,
                ':NomeServico' => $servico->NomeServico,
                ':Descricao' => $servico->Descricao,
                ':Preco' => $servico->Preco,
                ':Duracao' => $servico->Duracao
            ]);
        } catch(\PDOException $th) {
            var_dump($th->getMessage());
        }
    }

    public function delete($id) {
        $pdo = MySqlPDO::getInstance();
        $sql = "DELETE FROM Servicos WHERE ServicoID = :id";
        $stmt = $pdo->prepare($sql);

        try {
            return $stmt->execute([':id' => $id]);
        } catch(\PDOException $th) {
            var_dump($th->getMessage());
        }
    }
}