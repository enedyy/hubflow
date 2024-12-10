<?php
namespace Api\controller\in_bound;

use Api\service\FuncionarioService;
use Api\util\MensagensUtil;

class FuncionarioController {
    public function GET($parametro1 = null) {
        $funcionarioService = new FuncionarioService();

        if ($parametro1 === null) {
            return $funcionarioService->buscarTodos();
        }

        if (isset($_GET['empresaId'])) {
            return $funcionarioService->buscarPorEmpresa($_GET['empresaId']);
        }

        if (is_numeric($parametro1)) {
            return $funcionarioService->buscarPorId($parametro1);
        }
    }

    public function POST() {
        $requestValue = file_get_contents("php://input");
        $json = json_decode($requestValue, TRUE);

        $funcionarioService = new FuncionarioService();
        return $funcionarioService->criar($json);
    }

    public function PUT($parametro1 = null) {
        if (!is_numeric($parametro1)) {
            MensagensUtil::acessoNegado("ID não fornecido");
        }

        $requestValue = file_get_contents("php://input");
        $json = json_decode($requestValue, TRUE);

        $funcionarioService = new FuncionarioService();
        return $funcionarioService->atualizar($parametro1, $json);
    }

    public function DELETE($parametro1 = null) {
        if (!is_numeric($parametro1)) {
            MensagensUtil::acessoNegado("ID não fornecido");
        }

        $funcionarioService = new FuncionarioService();
        return $funcionarioService->deletar($parametro1);
    }
}