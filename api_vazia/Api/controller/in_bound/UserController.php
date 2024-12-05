<?php
    namespace Api\controller\in_bound;

    use Api\service\UserService;

    class UserController{
        public function GET($parametro1 = null){
            $userService = new UserService();

            if($parametro1 === null){
                return $userService->buscarTodos();
            }

            switch ($parametro1) {
                case is_numeric($parametro1):
                    return $userService->buscarPorId($parametro1);
            }

        }
        public function POST($parametro1 = null){

            $requestValue = file_get_contents("php://input");
            $json = json_decode($requestValue, TRUE);

            $userService = new UserService();
            $userService->cadastrar($json);
            // if($parametro1 == "empresa"){
            //     $userService->cadastrarEmpresa($json);
            // }else{
            //     $userService->cadastrarCliente($json);
            // }
        }
    }