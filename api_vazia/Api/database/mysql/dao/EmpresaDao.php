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
            echo "Erro: " . $e->getMessage();  // Melhoria no tratamento de exceções
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

        // exit($sql);  
        
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute();


            return $pdo->lastInsertId();
        } catch(\PDOException $th) {
            echo "Erro: " . $th->getMessage();  // Melhoria no tratamento de exceções
        } catch(\Throwable $th) {
            echo "Erro inesperado: " . $th->getMessage();  // Melhoria no tratamento de exceções
        }
    }
}
