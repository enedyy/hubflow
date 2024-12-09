<?php
namespace Api\controller\in_bound;

use Api\database\mysql\dao\AgendamentoDAO;
use Api\database\mysql\model\Agendamento;
use Api\util\MensagensUtil;

class AgendamentoController {
    private $agendamentoDAO;

    public function __construct() {
        $this->agendamentoDAO = new AgendamentoDAO();
    }

    public function buscarTodos() {
        return $this->agendamentoDAO->getAll();
    }

    public function buscarPorId($id) {
        $agendamento = $this->agendamentoDAO->getById($id);
        if (!$agendamento) {
            MensagensUtil::acessoNegado("Agendamento não encontrado");
        }
        return $agendamento;
    }

    public function criar($dados) {
        if (!isset($dados['ClienteID']) || !isset($dados['ServicoID']) || 
            !isset($dados['FuncionarioID']) || !isset($dados['DataHoraAgendamento'])) {
            MensagensUtil::acessoNegado("Dados incompletos para criar agendamento");
        }

        $agendamento = new Agendamento();
        $agendamento->ClienteID = $dados['ClienteID'];
        $agendamento->ServicoID = $dados['ServicoID'];
        $agendamento->FuncionarioID = $dados['FuncionarioID'];
        $agendamento->DataHoraAgendamento = $dados['DataHoraAgendamento'];
        $agendamento->Status = $dados['Status'] ?? 'Pendente';

        $id = $this->agendamentoDAO->insert($agendamento);
        
        return [
            'message' => 'Agendamento criado com sucesso',
            'id' => $id
        ];
    }

    public function atualizar($id, $dados) {
        $agendamentoExistente = $this->agendamentoDAO->getById($id);
        if (!$agendamentoExistente) {
            MensagensUtil::acessoNegado("Agendamento não encontrado");
        }

        $agendamento = new Agendamento();
        $agendamento->AgendamentoID = $id;
        $agendamento->ClienteID = $dados['ClienteID'] ?? $agendamentoExistente['ClienteID'];
        $agendamento->ServicoID = $dados['ServicoID'] ?? $agendamentoExistente['ServicoID'];
        $agendamento->FuncionarioID = $dados['FuncionarioID'] ?? $agendamentoExistente['FuncionarioID'];
        $agendamento->DataHoraAgendamento = $dados['DataHoraAgendamento'] ?? $agendamentoExistente['DataHoraAgendamento'];
        $agendamento->Status = $dados['Status'] ?? $agendamentoExistente['Status'];

        $this->agendamentoDAO->update($agendamento);
        
        return [
            'message' => 'Agendamento atualizado com sucesso'
        ];
    }

    public function deletar($id) {
        if (!$this->agendamentoDAO->getById($id)) {
            MensagensUtil::acessoNegado("Agendamento não encontrado");
        }

        $this->agendamentoDAO->delete($id);
        
        return [
            'message' => 'Agendamento deletado com sucesso'
        ];
    }
}