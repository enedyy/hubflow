<?php
namespace Api\controller\in_bound;

use Api\service\ServicoService;
use Api\util\MensagensUtil;

class ServicoController {
    public function GET($parametro1 = null) {
        $servicoService = new ServicoService();

        if ($parametro1 === null) {
            return $servicoService->buscarTodos();
        }

        if (isset($_GET['empresaId'])) {
            return $servicoService->buscarPorEmpresa($_GET['empresaId']);
        }

        if (is_numeric($parametro1)) {
            return $servicoService->buscarPorId($parametro1);
        }
    }

    public function POST() {
        $requestValue = file_get_contents("php://input");
        $json = json_decode($requestValue, TRUE);

        $servicoService = new ServicoService();
        return $servicoService->criar($json);
    }

    public function PUT($parametro1 = null) {
        if (!is_numeric($parametro1)) {
            MensagensUtil::acessoNegado("ID não fornecido");
        }

        $requestValue = file_get_contents("php://input");
        $json = json_decode($requestValue, TRUE);

        $servicoService = new ServicoService();
        return $servicoService->atualizar($parametro1, $json);
    }

    public function DELETE($parametro1 = null) {
        if (!is_numeric($parametro1)) {
            MensagensUtil::acessoNegado("ID não fornecido");
        }

        $servicoService = new ServicoService();
        return $servicoService->deletar($parametro1);
    }
}