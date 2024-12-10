<?php
namespace Api\service;

use Api\database\mysql\dao\ServicoDao;
use Api\database\mysql\model\Servico;
use Api\util\MensagensUtil;

class ServicoService {
    private $servicoDao;

    public function __construct() {
        $this->servicoDao = new ServicoDao();
    }

    public function buscarTodos() {
        try {
            $servicos = $this->servicoDao->getAll();
            return $servicos;
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao buscar serviços: " . $e->getMessage());
        }
    }

    public function buscarPorId($id) {
        try {
            $servico = $this->servicoDao->getById($id);
            
            if (!$servico) {
                MensagensUtil::acessoNegado("Serviço não encontrado");
            }
            
            return $servico;
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao buscar serviço: " . $e->getMessage());
        }
    }

    public function buscarPorEmpresa($empresaId) {
        try {
            $servicos = $this->servicoDao->getByEmpresa($empresaId);
            return $servicos;
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao buscar serviços da empresa: " . $e->getMessage());
        }
    }

    public function criar($dados) {
        try {
            $this->validarServico($dados);

            $servico = new Servico();
            $servico->NomeServico = $dados['nome'];
            $servico->Descricao = $dados['description'] ?? '';
            $servico->Preco = $dados['value'];
            $servico->Duracao = $dados['duracao'] ?? '01:00:00';
            $servico->EmpresaID = $dados['empresaId'];

            $id = $this->servicoDao->insert($servico);

            if (!$id) {
                MensagensUtil::acessoNegado("Erro ao criar serviço");
            }

            return [
                "message" => "Serviço criado com sucesso",
                "servicoId" => $id
            ];
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao criar serviço: " . $e->getMessage());
        }
    }

    public function atualizar($id, $dados) {
        try {
            $servicoExistente = $this->servicoDao->getById($id);
            if (!$servicoExistente) {
                MensagensUtil::acessoNegado("Serviço não encontrado");
            }

            $servico = new Servico();
            $servico->ServicoID = $id;
            $servico->NomeServico = $dados['nome'] ?? $servicoExistente['NomeServico'];
            $servico->Descricao = $dados['description'] ?? $servicoExistente['Descricao'];
            $servico->Preco = $dados['value'] ?? $servicoExistente['Preco'];
            $servico->Duracao = $dados['duracao'] ?? $servicoExistente['Duracao'];
            $servico->EmpresaID = $servicoExistente['EmpresaID'];

            $resultado = $this->servicoDao->update($servico);

            if (!$resultado) {
                MensagensUtil::acessoNegado("Erro ao atualizar serviço");
            }

            return [
                "message" => "Serviço atualizado com sucesso"
            ];
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao atualizar serviço: " . $e->getMessage());
        }
    }

    public function deletar($id) {
        try {
            $servicoExistente = $this->servicoDao->getById($id);
            if (!$servicoExistente) {
                MensagensUtil::acessoNegado("Serviço não encontrado");
            }

            $resultado = $this->servicoDao->delete($id);

            if (!$resultado) {
                MensagensUtil::acessoNegado("Erro ao deletar serviço");
            }

            return [
                "message" => "Serviço deletado com sucesso"
            ];
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao deletar serviço: " . $e->getMessage());
        }
    }

    private function validarServico($dados) {
        if (empty($dados['nome'])) {
            MensagensUtil::acessoNegado("Nome do serviço é obrigatório");
        }

        if (!isset($dados['value']) || !is_numeric($dados['value'])) {
            MensagensUtil::acessoNegado("Preço do serviço é obrigatório e deve ser um número");
        }

        if (isset($dados['duracao'])) {
            if (!preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/', $dados['duracao'])) {
                MensagensUtil::acessoNegado("Formato de duração inválido. Use o formato HH:MM:SS");
            }
        }

        if (empty($dados['empresaId'])) {
            MensagensUtil::acessoNegado("ID da empresa é obrigatório");
        }

        return true;
    }
}