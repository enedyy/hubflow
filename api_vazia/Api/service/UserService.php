<?php

    namespace Api\service;

    use Api\database\mysql\dao\UserDAO;
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

            // if($user->tipoUsuario == "cliente"){
            //     $clienteDao = new ClienteDao();
            //     $clienteDao->inserir($Cliente);
            //     $user->clienteId = 1;
            // }//else if(){
                
            // // }

            $userDao->insert($user);

            return [
            "clienteId" => 1,
            "empresaId" => 1,
            "email" => "cliente@teste.com",
            "nome" => "cliente teste",
            "tipoUser" => "cliente"
            ];
        }

        public function buscarTodos(){
            $userDAO = new UserDAO();
            return $userDAO->getAll();
        }

        public function buscarPorId($id){
            return ["user"=> "todos", "id" => $id];
        }

    }