<?php
    namespace Api\database\mysql\dao;

    use Api\database\mysql\model\Cliente;
    use Api\database\mysql\MySqlPDO;
    use PDO;

    class ClienteDAO{
        public function getAll(){

            $pdo = MySqlPDO::getInstance();

            $sql = "SELECT * FROM Clientes";

            $stmt = $pdo->prepare($sql);

            try{
                $stmt->execute();

                $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $dados;
            }catch(\PDOException $e){
                echo "". $e->getMessage();
            }
        }

        public function insert(Cliente $Cliente){
            $pdo = MySqlPDO::getInstance();

            
         $ClienteID = $Cliente->ClienteID;
         $Nome = $Cliente->Nome; 
         $Telefone = $Cliente->Telefone; 
         $Email= $Cliente->Email; 
         $DataNascimento= $Cliente-> DataNascimento;
            //
         $CEP= $Cliente->CEP;
         $Estado= $Cliente->Estado;
         $Cidade= $Cliente->Cidade;
         $Bairro= $Cliente->Bairro;
         $Rua= $Cliente->Rua;
         $Numero= $Cliente->Numero;

            $sql = "INSERT INTO clientes VALUES (null, '$Nome', '$Telefone', '$Email', $DataNascimento, $CEP, '$Estado','$$Cidade','$Bairro','$Rua','$Numero')";

            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute();
                return $pdo->lastInsertId();
            }catch(\PDOException $th) {
                var_dump($th->getMessage());
            } catch (\Throwable $th) {
                var_dump($th->getMessage());
            } 
        }
    }