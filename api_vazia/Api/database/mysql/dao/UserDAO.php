<?php
    namespace Api\database\mysql\dao;

    use Api\database\mysql\model\User;
    use Api\database\mysql\MySqlPDO;
    use PDO;

    class UserDAO{
        public function getAll(){

            $pdo = MySqlPDO::getInstance();

            $sql = "SELECT * FROM usuarios";

            $stmt = $pdo->prepare($sql);

            try{
                $stmt->execute();

                $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $dados;
            }catch(\PDOException $e){
                echo "". $e->getMessage();
            }
        }

        public function insert(User $user){
            $pdo = MySqlPDO::getInstance();

            $email = $user->email;
            $senha = $user->getHashSenha();
            $tipoUsuario = $user->tipoUsuario;
            $clienteId = $user->clienteId ?? "NULL";
            $empresaId = $user->empresaId ?? "NULL";
            $funcionarioId = $user->funcionarioId ?? "NULL";

            $sql = "INSERT INTO usuarios VALUES (null, '$email', '$senha', '$tipoUsuario', $clienteId, $empresaId, $funcionarioId)";

            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute();

            }catch(\PDOException $th) {
                var_dump($th->getMessage());
            } catch (\Throwable $th) {
                var_dump($th->getMessage());
            } 
        }
    }