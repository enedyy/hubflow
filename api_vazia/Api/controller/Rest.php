<?php

namespace Api\controller;

use Api\util\MensagensUtil;
use Api\controller\in_bound\AutenticacaoController;
use Throwable;

class Rest
{
    private $request;
    private $classe;
    private $parametros = array();

    public function __construct($request)
    {
        $this->request = $request;
        $this->carregar();
    }

    private function carregar()
    {
        $novaUrl = explode("/", $this->request["url"]);

        if (isset($novaUrl[0])) {
            $this->classe = substr(ucfirst($novaUrl[0]), 0, -1) . "Controller";
            array_shift($novaUrl);

            if (isset($novaUrl[0])) {
                $this->parametros = $novaUrl;
            }
        }
    }

    public function iniciar()
    {
        if (class_exists("Api\controller\in_bound\\" . $this->classe)) {
            try {
                // if ($this->classe === "AutenticacaoController") {
                //     return json_encode(AutenticacaoController::authenticar());
                // }
               
                // if (AutenticacaoController::autorizar() === false) {
                //     MensagensUtil::acessoNegado();
                // }
                
                $controller = "\Api\controller\in_bound\\" . $this->classe;
                $response = call_user_func_array(array(new $controller, $_SERVER["REQUEST_METHOD"]), $this->parametros);

                return json_encode($response);
            } catch (Throwable $throwable) {
                MensagensUtil::erroInterno($throwable->getTrace());
            }
        } else {
            MensagensUtil::requisicaoComErro("Rota inexistente");
        }
    }
}
