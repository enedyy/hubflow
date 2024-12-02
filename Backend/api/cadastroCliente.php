// cadastroCliente.php
<?php
require_once '../controllers/CadastroController.php';
require_once './headers.php';

$data = json_decode(file_get_contents('php://input'), true);

$nome = $data['nome'];
$endereco = $data['endereco'];
$telefone = $data['telefone'];
$email = $data['email'];
$dataNascimento = $data['dataNascimento'];
$senha = $data['senha'];

$cadastroController = new CadastroController();

try {
    $result = $cadastroController->cadastrarCliente($nome, $endereco, $telefone, $email, $dataNascimento, $senha);
    http_response_code(200);
    echo json_encode(['success' => true, 'data' => $result]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}