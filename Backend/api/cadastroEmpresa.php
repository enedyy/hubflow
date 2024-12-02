// cadastroEmpresa.php
<?php
require_once '../controllers/CadastroController.php';
require_once './headers.php';

$data = json_decode(file_get_contents('php://input'), true);

$nomeEmpresa = $data['nomeEmpresa'];
$nomeDono = $data['nomeDono'];
$email = $data['email'];
$telefone = $data['telefone'];
$endereco = $data['endereco'];
$descricao = $data['descricao'];
$senha = $data['senha'];

$cadastroController = new CadastroController();

try {
    $result = $cadastroController->cadastrarEmpresa($nomeEmpresa, $nomeDono, $email, $telefone, $endereco, $descricao, $senha);
    http_response_code(200);
    echo json_encode(['success' => true, 'data' => $result]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}