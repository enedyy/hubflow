<?php
    namespace Api\database\mysql\dao;

    use Api\database\mysql\model\User;
    use Api\database\mysql\MySqlPDO;
    use Api\util\MensagensUtil;
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

        public function getByEmail($email){
            $pdo = MySqlPDO::getInstance();

            
            $sql = "SELECT * FROM usuarios WHERE Email = '$email'";
            // exit($sql);

            try {
                $stmt = $pdo->prepare($sql);

                $stmt->execute();

                $usuario = $stmt->fetch(PDO::FETCH_OBJ);

                if($usuario != false){
                    return $usuario;
                }

                MensagensUtil::acessoNegado("Email não encontrado");
            
            } catch (\Throwable $th) {
                //throw $th;
            }
            exit($sql);
        }

        public function insert(User $user){ // aqui ele prepara para poder madnar pro banco.
            $pdo = MySqlPDO::getInstance();
            
            $email = $user->email;
            $senha = $user->getHashSenha();
            $tipoUsuario = $user->tipoUsuario;
            $clienteId = $user->clienteId ?? "NULL";
            $empresaId = $user->empresaId ?? "NULL";
            $funcionarioId = "null";

            $sql = "INSERT INTO usuarios VALUES (null, '$email', '$senha', '$tipoUsuario', $clienteId, $funcionarioId, $empresaId)";

            $stmt = $pdo->prepare($sql);

            try { // e só no execute que ele realmente envia pro banco.
                $stmt->execute();

            }catch(\PDOException $th) {
                var_dump($th->getMessage());
            } catch (\Throwable $th) {
                var_dump($th->getMessage());
            } 
        }
    }