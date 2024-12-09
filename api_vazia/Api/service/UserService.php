<?php
namespace Api\service;

use Api\database\mysql\dao\UserDAO;
use Api\database\mysql\dao\ClienteDAO;
use Api\database\mysql\dao\EmpresaDAO;
use Api\database\mysql\dao\FuncionarioDAO;
use Api\database\mysql\dao\ServicoDAO;
use Api\database\mysql\model\User;
use Api\database\mysql\model\Cliente;
use Api\database\mysql\model\Empresa;
use Api\database\mysql\model\Funcionario;
use Api\database\mysql\model\Servico;
use Api\util\MensagensUtil;

class UserService {

    public function cadastrar($json) {
        $user = new User();
        $user->email = $json["email"];
        $user->senha = $json["senha"];
        $user->tipoUsuario = $json["user"];
    
        $userDao = new UserDAO();
    
        if($user->tipoUsuario == "cliente") {
            $clienteDao = new ClienteDao();
            $Cliente = new Cliente();
            $Cliente->Nome = $json["nome"];
            $Cliente->Telefone = $json["tel"];
            $Cliente->Email = $json["email"];
            $Cliente->DataNascimento = $json["dataNascimento"];
            $Cliente->CEP = $json["cep"];
            $Cliente->Estado = $json["estado"];
            $Cliente->Cidade = $json["cidade"];
            $Cliente->Bairro = $json["bairro"];
            $Cliente->Rua = $json["rua"];
            $Cliente->Numero = $json["numero"];
            $user->clienteId = $clienteDao->insert($Cliente);
        } 
        else if($user->tipoUsuario == "empresa") {
            $empresaDao = new EmpresaDAO();
            $empresa = new Empresa();
            $empresa->NomeEmpresa = $json["nome"];
            $empresa->NomeDono = $json["nomeDono"];
            $empresa->Email = $json["email"];
            $empresa->Telefone = $json["tel"];
            $empresa->CNPJ = $json["cnpj"];
            $empresa->CPF = $json["cpf"];
            $empresa->CEP = $json["cep"];
            $empresa->Estado = $json["estado"];
            $empresa->Cidade = $json["cidade"];
            $empresa->Bairro = $json["bairro"];
            $empresa->Rua = $json["rua"];
            $empresa->Numero = $json["numero"];
            $empresa->Descricao = $json["descricao"];

            $user->empresaId = $empresaDao->insert($empresa);
        }
        
        $userDao->insert($user);
    
        return [
            "clienteId" => $user->clienteId,
            "empresaId" => $user->empresaId,
            "email" => $user->email,
            "nome" => $json["nome"],
            "tipoUser" => $user->tipoUsuario
        ];
    }

    public function cadastrarFuncionario($json) {
        $funcionarioDao = new FuncionarioDAO();
        $funcionario = new Funcionario();
        
        $funcionario->Nome = $json["nome"];
        $funcionario->Cargo = $json["function"];
        $funcionario->Salario = $json["salario"] ?? 0;
        $funcionario->DataContratacao = date('Y-m-d');
        $funcionario->EmpresaID = $json["empresaId"];
        
        $funcionarioId = $funcionarioDao->insert($funcionario);

        if ($funcionarioId) {
            return [
                "success" => true,
                "funcionarioId" => $funcionarioId,
                "message" => "Funcionário cadastrado com sucesso"
            ];
        }

        return [
            "success" => false,
            "message" => "Erro ao cadastrar funcionário"
        ];
    }

    public function cadastrarServico($json) {
        $servicoDao = new ServicoDAO();
        $servico = new Servico();
        
        $servico->NomeServico = $json["nome"];
        $servico->Descricao = $json["description"];
        $servico->Preco = $json["value"];
        $servico->Duracao = $json["duracao"] ?? "01:00:00";
        $servico->EmpresaID = $json["empresaId"];
        
        $servicoId = $servicoDao->insert($servico);

        if ($servicoId) {
            return [
                "success" => true,
                "servicoId" => $servicoId,
                "message" => "Serviço cadastrado com sucesso"
            ];
        }

        return [
            "success" => false,
            "message" => "Erro ao cadastrar serviço"
        ];
    }

    public function buscarTodos() {
        $ClienteDAO = new ClienteDAO();
        return $ClienteDAO->getAll();
    }

    public function buscarPorId($id) {
        return ["user" => "todos", "id" => $id];
    }

    public function buscarFuncionariosPorEmpresa($empresaId) {
        $funcionarioDao = new FuncionarioDAO();
        return $funcionarioDao->getByEmpresa($empresaId);
    }

    public function buscarServicosPorEmpresa($empresaId) {
        $servicoDao = new ServicoDAO();
        return $servicoDao->getByEmpresa($empresaId);
    }

    public function editarServico($id, $json) {
        $servicoDao = new ServicoDAO();
        $servico = new Servico();
        
        $servico->ServicoID = $id;
        $servico->NomeServico = $json["nome"];
        $servico->Descricao = $json["description"];
        $servico->Preco = $json["value"];
        $servico->Duracao = $json["duracao"] ?? "01:00:00";
        
        if ($servicoDao->update($servico)) {
            return [
                "success" => true,
                "message" => "Serviço atualizado com sucesso"
            ];
        }

        return [
            "success" => false,
            "message" => "Erro ao atualizar serviço"
        ];
    }

    public function editarFuncionario($id, $json) {
        $funcionarioDao = new FuncionarioDAO();
        $funcionario = new Funcionario();
        
        $funcionario->FuncionarioID = $id;
        $funcionario->Nome = $json["nome"];
        $funcionario->Cargo = $json["function"];
        $funcionario->Salario = $json["salario"] ?? 0;
        
        if ($funcionarioDao->update($funcionario)) {
            return [
                "success" => true,
                "message" => "Funcionário atualizado com sucesso"
            ];
        }

        return [
            "success" => false,
            "message" => "Erro ao atualizar funcionário"
        ];
    }

    public function deletarServico($id) {
        $servicoDao = new ServicoDAO();
        
        if ($servicoDao->delete($id)) {
            return [
                "success" => true,
                "message" => "Serviço deletado com sucesso"
            ];
        }

        return [
            "success" => false,
            "message" => "Erro ao deletar serviço"
        ];
    }

    public function deletarFuncionario($id) {
        $funcionarioDao = new FuncionarioDAO();
        
        if ($funcionarioDao->delete($id)) {
            return [
                "success" => true,
                "message" => "Funcionário deletado com sucesso"
            ];
        }

        return [
            "success" => false,
            "message" => "Erro ao deletar funcionário"
        ];
    }
}