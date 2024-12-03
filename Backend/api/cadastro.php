

// //exit("teste");
// // require_once '../config/Cors.php';
// require_once '../config/database.php';
// require_once '../models/Usuario.php';
// require_once '../models/Cliente.php';
// require_once '../models/Empresa.php';

// // Aplica CORS antes de qualquer outro código
// // Cors::handleCors();

// // header("Content-Type: application/json");
// // header("Access-Control-Allow-Origin: *  ");
// // header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// // header("Access-Control-Allow-Headers: Origin, Content-Type");
// // // header("Access-Control-Allow-Credentials: true");

// // if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
// //     header('HTTP/1.1 200 OK');
// //     exit();
// // }

// // Se não for OPTIONS, continua com o processamento
// if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
//     try {
//         $database = new Database();
//         $db = $database->getConnection();
        
//         $data = json_decode(file_get_contents('php://input'), true);
        
//         // Resto do seu código...
//     } catch (Exception $e) {
//         http_response_code(400);
//         echo json_encode([
//             'success' => false,
//             'message' => $e->getMessage()
//         ]);
//     }
// }

// try {
//     $database = new Database();
//     $db = $database->getConnection();
    
//     $data = json_decode(file_get_contents('php://input'), true);
    
//     // Log para debug
//     error_log("Dados recebidos: " . print_r($data, true));
    
//     // Verifica se o email já existe
//     $usuario = new Usuario($db);
//     $usuario->email = $data['email'];
    
//     if($usuario->verificarEmailExistente()) {
//         throw new Exception("Email já cadastrado");
//     }
    
//     // Inicia transação
//     $db->beginTransaction();
    
//     try {
//         if ($data['user'] === 'cliente') {
//             $cliente = new Cliente($db);
//             $cliente->nome = $data['nome'];
//             $cliente->telefone = $data['tel'];
//             $cliente->email = $data['email'];
//             $cliente->dataNascimento = $data['dataNascimento'];
//             // Monta o endereço em uma única string
//             $cliente->endereco = sprintf(
//                 "%s, %s, %s-%s, %s, CEP: %s",
//                 $data['rua'],
//                 $data['numero'],
//                 $data['cidade'],
//                 $data['estado'],
//                 $data['bairro'],
//                 $data['cep']
//             );
            
//             if (!$cliente->criar()) {
//                 throw new Exception("Erro ao criar cliente");
//             }
            
//             // Cria o usuário
//             $usuario->senha = password_hash($data['senha'], PASSWORD_DEFAULT);
//             $usuario->tipoUsuario = 'Cliente';
//             $usuario->clienteId = $cliente->clienteId;
//             $usuario->funcionarioId = null;
            
//             if (!$usuario->cadastrar()) {
//                 throw new Exception("Erro ao criar usuário");
//             }
            
//             // Atualiza o ID do usuário no cliente
//             $cliente->atualizarUsuarioId($usuario->usuarioId);
            
//         } elseif ($data['user'] === 'empresa') {
//             $empresa = new Empresa($db);
//             $empresa->nomeEmpresa = $data['empresa'];
//             $empresa->nomeDono = $data['nome'];
//             $empresa->email = $data['email'];
//             $empresa->telefone = $data['tel'];
//             $empresa->endereco = sprintf(
//                 "%s, %s, %s-%s, %s, CEP: %s",
//                 $data['rua'],
//                 $data['numero'],
//                 $data['cidade'],
//                 $data['estado'],
//                 $data['bairro'],
//                 $data['cep']
//             );
            
//             if (!$empresa->criar()) {
//                 throw new Exception("Erro ao criar empresa");
//             }
            
//             // Cria o usuário
//             $usuario->senha = password_hash($data['senha'], PASSWORD_DEFAULT);
//             $usuario->tipoUsuario = 'Empresa';
//             $usuario->clienteId = null;
//             $usuario->funcionarioId = null;
            
//             if (!$usuario->cadastrar()) {
//                 throw new Exception("Erro ao criar usuário");
//             }
//         } else {
//             throw new Exception("Tipo de usuário inválido");
//         }
        
//         $db->commit();
        
//         $response = [
//             'success' => true,
//             'message' => 'Cadastro realizado com sucesso',
//             'data' => [
//                 'id' => $data['user'] === 'cliente' ? $cliente->clienteId : $empresa->empresaId,
//                 'email' => $data['email'],
//                 'nome' => $data['user'] === 'cliente' ? $data['nome'] : $data['empresa'],
//                 'tipoUser' => $data['user']
//             ]
//         ];
        
//         http_response_code(200);
//         echo json_encode($response);
        
//     } catch (Exception $e) {
//         $db->rollBack();
//         throw $e;
//     }
    
// } catch (Exception $e) {
//     http_response_code(400);
//     echo json_encode([
//         'success' => false,
//         'message' => $e->getMessage()
//     ]);
// }

<?php
require_once '../config/Cors.php';
require_once '../config/database.php';
require_once '../models/Usuario.php';
require_once '../models/Cliente.php';
require_once '../models/Empresa.php';

// Aplica CORS
Cors::handleCors();

// Se for uma requisição OPTIONS, já foi tratada pelo Cors::handleCors()
if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Recebe os dados da requisição
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validação básica dos dados
        if (!isset($data['email']) || !isset($data['senha']) || !isset($data['user'])) {
            throw new Exception("Dados incompletos para cadastro");
        }
        
        // Verifica se o email já existe
        $usuario = new Usuario($db);
        $usuario->email = $data['email'];
        
        if($usuario->verificarEmailExistente()) {
            throw new Exception("Email já cadastrado");
        }
        
        // Inicia a transação
        $db->beginTransaction();
        
        try {
            // Cadastro de Cliente
            if ($data['user'] === 'cliente') {
                // Cria o cliente
                $cliente = new Cliente($db);
                $cliente->nome = $data['nome'];
                $cliente->telefone = $data['tel'];
                $cliente->email = $data['email'];
                $cliente->dataNascimento = $data['dataNascimento'];
                
                // Monta o endereço como uma string única
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
                
                // Cria o usuário vinculado ao cliente
                $usuario->senha = password_hash($data['senha'], PASSWORD_DEFAULT);
                $usuario->tipoUsuario = 'Cliente';
                $usuario->clienteId = $cliente->clienteId;
                $usuario->funcionarioId = null;
                
                if (!$usuario->cadastrar()) {
                    throw new Exception("Erro ao criar usuário");
                }
                
                // Atualiza o ID do usuário no cliente
                $cliente->atualizarUsuarioId($usuario->usuarioId);
                
                $idCadastrado = $cliente->clienteId;
                $nomeCadastrado = $cliente->nome;
                
            } 
            // Cadastro de Empresa
            elseif ($data['user'] === 'empresa') {
                // Cria a empresa
                $empresa = new Empresa($db);
                $empresa->nomeEmpresa = $data['empresa'];
                $empresa->nomeDono = $data['nome'];
                $empresa->email = $data['email'];
                $empresa->telefone = $data['tel'];
                
                // Monta o endereço como uma string única
                $empresa->endereco = sprintf(
                    "%s, %s, %s-%s, %s, CEP: %s",
                    $data['rua'],
                    $data['numero'],
                    $data['cidade'],
                    $data['estado'],
                    $data['bairro'],
                    $data['cep']
                );
                
                if (!$empresa->criar()) {
                    throw new Exception("Erro ao criar empresa");
                }
                
                // Cria o usuário vinculado à empresa
                $usuario->senha = password_hash($data['senha'], PASSWORD_DEFAULT);
                $usuario->tipoUsuario = 'Empresa';
                $usuario->clienteId = null;
                $usuario->funcionarioId = null;
                
                if (!$usuario->cadastrar()) {
                    throw new Exception("Erro ao criar usuário");
                }
                
                $idCadastrado = $empresa->empresaId;
                $nomeCadastrado = $empresa->nomeEmpresa;
                
            } else {
                throw new Exception("Tipo de usuário inválido");
            }
            
            // Confirma a transação
            $db->commit();
            
            // Prepara a resposta
            $response = [
                'success' => true,
                'message' => 'Cadastro realizado com sucesso',
                'data' => [
                    'id' => $idCadastrado,
                    'email' => $data['email'],
                    'nome' => $nomeCadastrado,
                    'tipoUser' => $data['user']
                ]
            ];
            
            http_response_code(200);
            echo json_encode($response);
            
        } catch (Exception $e) {
            // Em caso de erro, desfaz a transação
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
}