<?php

namespace Api\controller\in_bound;


use Api\config\Sessao;
use Api\util\MensagensUtil;


class AutenticacaoController
{
    private static $authKey = "";

    public static function authenticar()
    {
    
            $header = [
                "typ"=> "JWT",
                "alg"=> "HS256"
            ];

            $payload = [];

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
                return true;
            }
        }

        return false;
    }

}