<?php
// Inclui os headers primeiro, antes de qualquer outro require
require_once './headers.php';

// Agora os outros requires
require_once '../config/database.php';
require_once '../models/Usuario.php';
require_once '../models/Cliente.php';
require_once '../models/Empresa.php';

// Preflight check
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Log para debug
    error_log("Dados recebidos: " . print_r($data, true));
    
    // Verifica se o email já existe
    $usuario = new Usuario($db);
    $usuario->email = $data['email'];
    
    if($usuario->verificarEmailExistente()) {
        throw new Exception("Email já cadastrado");
    }
    
    // Prepara dados do usuário
    $usuario->senha = $data['senha'];
    $usuario->tipoUsuario = ucfirst($data['user']); // Converte para 'Cliente' ou 'Empresa'
    
    // Inicia transação
    $db->beginTransaction();
    
    error_log('Headers enviados: ' . print_r(headers_list(), true));
    
    try {
        if ($data['user'] === 'cliente') {
            $cliente = new Cliente($db);
            $cliente->nome = $data['nome'];
            $cliente->telefone = $data['tel'];
            $cliente->email = $data['email'];
            $cliente->dataNascimento = $data['dataNascimento'];
            // Monta o endereço em uma string
            $cliente->endereco = sprintf(
                "%s, %s, %s-%s, %s, CEP: %s",
                $data['rua'],
                $data['numero'],
                $data['cidade'],
                $data['estado'],
                $data['bairro'],
                $data['cep']
            );
            
            if (!$cliente->criar()) {
                throw new Exception("Erro ao criar cliente");
            }
            
            $usuario->clienteId = $cliente->clienteId;
            $usuario->funcionarioId = null;
            
        } elseif ($data['user'] === 'empresa') {
            $empresa = new Empresa($db);
            $empresa->nomeEmpresa = $data['empresa'];
            $empresa->nomeDono = $data['nome'];
            $empresa->email = $data['email'];
            $empresa->telefone = $data['tel'];
            // Monta o endereço em uma string
            $empresa->endereco = sprintf(
                "%s, %s, %s-%s, %s, CEP: %s",
                $data['rua'],
                $data['numero'],
                $data['cidade'],
                $data['estado'],
                $data['bairro'],
                $data['cep']
            );
            $empresa->descricao = ''; // Campo opcional
            
            if (!$empresa->criar()) {
                throw new Exception("Erro ao criar empresa");
            }
            
            $usuario->clienteId = null;
            $usuario->funcionarioId = null;
        } else {
            throw new Exception("Tipo de usuário inválido");
        }
        
        if (!$usuario->cadastrar()) {
            throw new Exception("Erro ao criar usuário");
        }
        
        $db->commit();
        
        $response = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'id' => $data['user'] === 'cliente' ? $cliente->clienteId : $empresa->empresaId,
                'email' => $data['email'],
                'nome' => $data['user'] === 'cliente' ? $data['nome'] : $data['empresa'],
                'tipoUser' => $data['user']
            ]
        ];
        
        http_response_code(200);
        echo json_encode($response);
        
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}