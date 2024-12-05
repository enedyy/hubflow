<?php

namespace Api\util;

class MensagensUtil
{
    /**
     * Status code  200
     */
    static function sucesso($detalhes = null, $token = null, $codigoMovimento = null)
    {
        http_response_code(200);

        $mensagem = [
            "codigo"   => 200,
            "mensagem" => "Sucesso"
        ];

        if (isset($detalhes)) $mensagem["detalhes"] = $detalhes;
        if (isset($token))$mensagem["token"] = $token;
        if (isset($codigoMovimento)) $mensagem["codigoMovimento"] = $codigoMovimento;

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Status code  200
     */
    static function sucessoCodigoCartao($detalhes = null,$codigo_cartao = null, $token = null, $codigoMovimento = null)
    {
        http_response_code(200);

        $mensagem = [
            "codigo"   => 200,
            "mensagem" => "Sucesso"
        ];

        if (isset($detalhes)) $mensagem["codigo_cartao"] = $codigo_cartao;
        if (isset($detalhes)) $mensagem["detalhes"] = $detalhes;
        if (isset($token))$mensagem["token"] = $token;
        if (isset($codigoMovimento)) $mensagem["codigoMovimento"] = $codigoMovimento;

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

     /**
     * Status code 222
     */
    static function dipositivoAlterado($erro = null)
    {
        http_response_code(203);

        $mensagem = [
            "codigo"   => 203,
            "mensagem" => "Outro dispositivo se conectou"
        ];

        if (isset($erro)) $mensagem["erro"] = $erro;

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Status code 403
     */
    static function acessoNegado($erro = null)
    {
        http_response_code(403);

        $mensagem = [
            "codigo"   => 403,
            "mensagem" => "Acesso negado"
        ];

        if (isset($erro)) $mensagem["erro"] = $erro;

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Status code 400
     */
    static function requisicaoComErro($erro = null)
    {
        http_response_code(400);

        $mensagem = [
            "codigo"   => 400,
            "mensagem" => "Requisição com erro."
        ];
        
        if (isset($erro)) $mensagem["erro"] = $erro;

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Status code 401
     */
    static function falhaDeAutenticacao()
    {
        http_response_code(401);

        $mensagem = [
            "codigo"   => 401,
            "mensagem" => "Falha de autenticação."
        ];

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Status code 404
     */
    static function naoEncontrado($erro = null)
    {
        http_response_code(404);

        $mensagem = [
            "codigo"   => 404,
            "mensagem" => "Não encontrado."
        ];

        if (isset($erro)) $mensagem["erro"] = $erro;

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Status code 405
     */
    static function metodoNaoPermitido()
    {
        http_response_code(405);

        $mensagem = [
            "codigo"   => 405,
            "mensagem" => "Método não permitido."
        ];

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Status code 500
     */
    static function erroInterno($mensagemErro = null,$erro = null)
    {
        http_response_code(500);

        $mensagem = [
            "codigo"   => 500,
            "mensagem" => "Erro interno."
        ];
        if (isset($mensagemErro)) $mensagem["mensagemErro"] = $mensagemErro;
        if (isset($erro)) $mensagem["erro"] = $erro;

        echo json_encode($mensagem, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
