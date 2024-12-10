<?php
namespace Api\database\mysql\dao;

use Api\database\mysql\model\Cliente;
use Api\database\mysql\MySqlPDO;
use PDO;

class ClienteDAO {
    public function getAll() {
        $pdo = MySqlPDO::getInstance();

        $sql = "SELECT * FROM Clientes";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $dados;
        } catch(\PDOException $e) {
            echo "". $e->getMessage();
        }
    }

    public function insert(Cliente $Cliente) {
        $pdo = MySqlPDO::getInstance();

        $ClienteID = $Cliente->ClienteID;
        $Nome = $Cliente->Nome; 
        $Telefone = $Cliente->Telefone; 
        $Email = $Cliente->Email; 
        $DataNascimento = $Cliente->DataNascimento;
        $CEP = $Cliente->CEP;
        $Estado = $Cliente->Estado;
        $Cidade = $Cliente->Cidade;
        $Bairro = $Cliente->Bairro;
        $Rua = $Cliente->Rua;
        $Numero = $Cliente->Numero;

        $sql = "INSERT INTO clientes (Nome, Telefone, Email, DataNascimento, CEP, Estado, Cidade, Bairro, Rua, Numero) 
                VALUES ('$Nome', '$Telefone', '$Email', '$DataNascimento', '$CEP', '$Estado', '$Cidade', '$Bairro', '$Rua', '$Numero')";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute();
            return $pdo->lastInsertId();
        } catch(\PDOException $th) {
            var_dump($th->getMessage());
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
        }
    }

    // Novo método para atualizar as informações de um cliente
    public function update(Cliente $Cliente) {
        $pdo = MySqlPDO::getInstance();

        // Recebe os dados a serem atualizados
        $ClienteID = $Cliente->ClienteID;
        $Nome = $Cliente->Nome; 
        $Telefone = $Cliente->Telefone; 
        $Email = $Cliente->Email; 
        $DataNascimento = $Cliente->DataNascimento;
        $CEP = $Cliente->CEP;
        $Estado = $Cliente->Estado;
        $Cidade = $Cliente->Cidade;
        $Bairro = $Cliente->Bairro;
        $Rua = $Cliente->Rua;
        $Numero = $Cliente->Numero;

        // A consulta SQL de atualização
        $sql = "UPDATE clientes 
                SET Nome = '$Nome', Telefone = '$Telefone', Email = '$Email', 
                    DataNascimento = '$DataNascimento', CEP = '$CEP', 
                    Estado = '$Estado', Cidade = '$Cidade', Bairro = '$Bairro', 
                    Rua = '$Rua', Numero = '$Numero' 
                WHERE ClienteID = $ClienteID";

        $stmt = $pdo->prepare($sql);

        try {
            // Executa a consulta de atualização
            $stmt->execute();
            return true;  // Retorna true se a atualização for bem-sucedida
        } catch(\PDOException $e) {
            var_dump($e->getMessage());
            return false;  // Retorna false em caso de erro
        }
    }
}
