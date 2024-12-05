<?php

    namespace Api\service;

    use Api\database\mysql\dao\UserDAO;
    use Api\database\mysql\dao\ClienteDAO;
    use Api\database\mysql\model\User;
    use Api\database\mysql\model\Cliente;

    class UserService{

        public function cadastrarEmpresa($json){
           return $json;
        }

        public function cadastrarCliente($json){
            return $json;
        }
        public function cadastrar($json){


            $user = new User();
            $user->email = $json["email"];
            $user->senha = $json["senha"];
            $user->tipoUsuario = $json["user"];

            $userDao = new UserDAO();

            if($user->tipoUsuario == "cliente"){
                $clienteDao = new ClienteDao();
                $Cliente = new Cliente();
                $Cliente->Nome =$json["nome"];
                $Cliente->Telefone =$json["tel"];
                $Cliente->Email =$json["email"];
                $Cliente->DataNascimento =$json["dataNascimento"];
                $Cliente->CEP =$json["cep"];
                $Cliente->Estado =$json["estado"];
                $Cliente->Cidade =$json["cidade"];
                $Cliente->Bairro =$json["bairro"];
                $Cliente->Rua =$json["rua"];
                $Cliente->Numero =$json["numero"];
                // $clienteDao->insert($Cliente);
                $user->clienteId = $clienteDao->insert($Cliente);
            }else if($user->tipoUsuario == "empresa"){
                
                
             }
            $userDao->insert($user);

            return [
            "clienteId" => $user->clienteId,
            "empresaId" => $user->empresaId,
            "email" => $user->email,
            "nome" => $json["nome"],
            "tipoUser" => $user->tipoUsuario
            ];
        }

        public function buscarTodos(){
            $ClienteDAO = new ClienteDAO();
            return $ClienteDAO->getAll();
        }

        public function buscarPorId($id){
            return ["user"=> "todos", "id" => $id];
        }

    }