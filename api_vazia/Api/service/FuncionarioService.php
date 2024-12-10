<?php
namespace Api\service;

use Api\database\mysql\dao\FuncionarioDao;
use Api\database\mysql\model\Funcionario;
use Api\util\MensagensUtil;

class FuncionarioService {
    private $funcionarioDao;

    public function __construct() {
        $this->funcionarioDao = new FuncionarioDao();
    }

    public function buscarTodos() {
        try {
            $funcionarios = $this->funcionarioDao->getAll();
            return $funcionarios;
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao buscar funcionários: " . $e->getMessage());
        }
    }

    public function buscarPorId($id) {
        try {
            $funcionario = $this->funcionarioDao->getById($id);
            
            if (!$funcionario) {
                MensagensUtil::acessoNegado("Funcionário não encontrado");
            }
            
            return $funcionario;
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao buscar funcionário: " . $e->getMessage());
        }
    }

    public function buscarPorEmpresa($empresaId) {
        try {
            $funcionarios = $this->funcionarioDao->getByEmpresa($empresaId);
            return $funcionarios;
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao buscar funcionários da empresa: " . $e->getMessage());
        }
    }

    public function criar($dados) {
        try {
            $this->validarFuncionario($dados);

            $funcionario = new Funcionario();
            $funcionario->Nome = $dados['nome'];
            $funcionario->Cargo = $dados['function'];
            $funcionario->Salario = $dados['salario'] ?? 0;
            $funcionario->DataContratacao = date('Y-m-d');
            $funcionario->EmpresaID = $dados['empresaId'];

            $id = $this->funcionarioDao->insert($funcionario);

            if (!$id) {
                MensagensUtil::acessoNegado("Erro ao criar funcionário");
            }

            return [
                "message" => "Funcionário criado com sucesso",
                "funcionarioId" => $id
            ];
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao criar funcionário: " . $e->getMessage());
        }
    }

    public function atualizar($id, $dados) {
        try {
            $funcionarioExistente = $this->funcionarioDao->getById($id);
            if (!$funcionarioExistente) {
                MensagensUtil::acessoNegado("Funcionário não encontrado");
            }

            $funcionario = new Funcionario();
            $funcionario->FuncionarioID = $id;
            $funcionario->Nome = $dados['nome'] ?? $funcionarioExistente['Nome'];
            $funcionario->Cargo = $dados['function'] ?? $funcionarioExistente['Cargo'];
            $funcionario->Salario = $dados['salario'] ?? $funcionarioExistente['Salario'];
            $funcionario->EmpresaID = $funcionarioExistente['EmpresaID'];

            $resultado = $this->funcionarioDao->update($funcionario);

            if (!$resultado) {
                MensagensUtil::acessoNegado("Erro ao atualizar funcionário");
            }

            return [
                "message" => "Funcionário atualizado com sucesso"
            ];
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao atualizar funcionário: " . $e->getMessage());
        }
    }

    public function deletar($id) {
        try {
            $funcionarioExistente = $this->funcionarioDao->getById($id);
            if (!$funcionarioExistente) {
                MensagensUtil::acessoNegado("Funcionário não encontrado");
            }

            $resultado = $this->funcionarioDao->delete($id);

            if (!$resultado) {
                MensagensUtil::acessoNegado("Erro ao deletar funcionário");
            }

            return [
                "message" => "Funcionário deletado com sucesso"
            ];
        } catch (\Exception $e) {
            MensagensUtil::acessoNegado("Erro ao deletar funcionário: " . $e->getMessage());
        }
    }

    private function validarFuncionario($dados) {
        if (empty($dados['nome'])) {
            MensagensUtil::acessoNegado("Nome do funcionário é obrigatório");
        }

        if (empty($dados['function'])) {
            MensagensUtil::acessoNegado("Função do funcionário é obrigatória");
        }

        if (isset($dados['salario']) && !is_numeric($dados['salario'])) {
            MensagensUtil::acessoNegado("Salário deve ser um valor numérico");
        }

        if (empty($dados['empresaId'])) {   
            MensagensUtil::acessoNegado("ID da empresa é obrigatório");
        }

        return true;
    }
}