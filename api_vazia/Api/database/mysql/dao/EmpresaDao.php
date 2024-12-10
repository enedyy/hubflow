<?php
namespace Api\database\mysql\dao;

use Api\database\mysql\model\Empresa;
use Api\database\mysql\MySqlPDO;
use PDO;

class EmpresaDAO {
    public function getAll() {
        $pdo = MySqlPDO::getInstance();
        $sql = "SELECT * FROM Empresa";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function insert(Empresa $Empresa) {
        $pdo = MySqlPDO::getInstance();

        $NomeEmpresa = $Empresa->NomeEmpresa;
        $NomeDono = $Empresa->NomeDono;
        $Email = $Empresa->Email;
        $Telefone = $Empresa->Telefone;
        $CNPJ = $Empresa->CNPJ;
        $CPF = $Empresa->CPF;
        $CEP = $Empresa->CEP;
        $Estado = $Empresa->Estado;
        $Cidade = $Empresa->Cidade;
        $Bairro = $Empresa->Bairro;
        $Rua = $Empresa->Rua;
        $Numero = $Empresa->Numero;
        $Descricao = $Empresa->Descricao;

        $sql = "INSERT INTO Empresa (NomeEmpresa, NomeDono, Email, Telefone, CNPJ, CPF, CEP, Estado, Cidade, Bairro, Rua, Numero, Descricao) 
                VALUES ('$NomeEmpresa', '$NomeDono', '$Email', '$Telefone', '$CNPJ', '$CPF', '$CEP', '$Estado', '$Cidade', '$Bairro', '$Rua', '$Numero', '$Descricao')";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute();
            return $pdo->lastInsertId();
        } catch(\PDOException $th) {
            echo "Erro: " . $th->getMessage();
        } catch(\Throwable $th) {
            echo "Erro inesperado: " . $th->getMessage();
        }
    }

    public function update(Empresa $Empresa) {
        $pdo = MySqlPDO::getInstance();

        $id = $Empresa->id; // Certifique-se de que a classe Empresa possui o atributo id
        $NomeEmpresa = $Empresa->NomeEmpresa;
        $NomeDono = $Empresa->NomeDono;
        $Email = $Empresa->Email;
        $Telefone = $Empresa->Telefone;
        $CNPJ = $Empresa->CNPJ;
        $CPF = $Empresa->CPF;
        $CEP = $Empresa->CEP;
        $Estado = $Empresa->Estado;
        $Cidade = $Empresa->Cidade;
        $Bairro = $Empresa->Bairro;
        $Rua = $Empresa->Rua;
        $Numero = $Empresa->Numero;
        $Descricao = $Empresa->Descricao;

        $sql = "UPDATE Empresa 
                SET NomeEmpresa = :NomeEmpresa, 
                    NomeDono = :NomeDono, 
                    Email = :Email, 
                    Telefone = :Telefone, 
                    CNPJ = :CNPJ, 
                    CPF = :CPF, 
                    CEP = :CEP, 
                    Estado = :Estado, 
                    Cidade = :Cidade, 
                    Bairro = :Bairro, 
                    Rua = :Rua, 
                    Numero = :Numero, 
                    Descricao = :Descricao 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':NomeEmpresa', $NomeEmpresa, PDO::PARAM_STR);
            $stmt->bindParam(':NomeDono', $NomeDono, PDO::PARAM_STR);
            $stmt->bindParam(':Email', $Email, PDO::PARAM_STR);
            $stmt->bindParam(':Telefone', $Telefone, PDO::PARAM_STR);
            $stmt->bindParam(':CNPJ', $CNPJ, PDO::PARAM_STR);
            $stmt->bindParam(':CPF', $CPF, PDO::PARAM_STR);
            $stmt->bindParam(':CEP', $CEP, PDO::PARAM_STR);
            $stmt->bindParam(':Estado', $Estado, PDO::PARAM_STR);
            $stmt->bindParam(':Cidade', $Cidade, PDO::PARAM_STR);
            $stmt->bindParam(':Bairro', $Bairro, PDO::PARAM_STR);
            $stmt->bindParam(':Rua', $Rua, PDO::PARAM_STR);
            $stmt->bindParam(':Numero', $Numero, PDO::PARAM_STR);
            $stmt->bindParam(':Descricao', $Descricao, PDO::PARAM_STR);

            return $stmt->execute();
        } catch(\PDOException $e) {
            echo "Erro ao atualizar empresa: " . $e->getMessage();
        }
    }
}