<?php

namespace Api\controller\in_bound;


use Api\config\Sessao;
use Api\database\mysql\dao\UserDAO;
use Api\database\mysql\model\User;
use Api\util\MensagensUtil;


class AutenticacaoController
{
    private static $authKey = "tccetec";

    public static function authenticar()
    {

        $requestValue = file_get_contents("php://input");
        $json = json_decode($requestValue, TRUE);

            // Pegando variaveis vinda do front end    
            $user = new User();
            $user->email = $json["email"];
            $user->senha = $json["senha"];

            //Buscar um usuario pelo e-mail 
            $userDAO = new UserDAO();
            $usuario = $userDAO->getByEmail($user->email);

            // Verificar a senha
            if(!password_verify($user->senha, $usuario->Senha)){
                MensagensUtil::acessoNegado("Senha invaldia");
            }

            $header = [
                "typ"=> "JWT",
                "alg"=> "HS256"
            ];

            $payload = [
                "email"=> $user->email
            ];

            //JSON
            $header = json_encode($header);
            $payload = json_encode($payload);

            //Base 64
            $header = base64_encode($header);
            $payload = base64_encode($payload);

            //Sing
            $sing = hash_hmac('sha256', $header . "." .$payload, self::$authKey, true);
            $sing = base64_encode($sing);

            $token = $header. '.' . $payload . '.' . $sing;
        
            return [
                "token" => $token
            ];
        
        
    }

    public static function autorizar(): bool
    {

        $http_header = apache_request_headers();

        if ((isset($http_header["Authorization"]) && $http_header["Authorization"] != null)
            || (isset($http_header["authorization"]) && $http_header["authorization"] != null)
        ) {
            if (isset($http_header["Authorization"]) && $http_header["Authorization"] != null) {
                $bearer = explode(" ", $http_header["Authorization"]);
            } else {
                $bearer = explode(" ", $http_header["authorization"]);
            }

            $token = explode(".", $bearer[1]);
            $header = $token[0];
            $payload = $token[1];
            $sign = $token[2];

            // Conferir Assinatura
            $valid = hash_hmac('sha256', $header . "." .$payload, self::$authKey, true);
            $valid = base64_encode($valid);

            if ($sign === $valid) {
                $payl = json_decode(base64_decode($payload));

                return true;
            }
        }

        return false;
    }

}