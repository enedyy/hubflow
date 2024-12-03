<?php
require_once '../../config/Cors.php';
require_once '../../config/database.php';


Cors::handleCors();

// Recebe os dados JSON
$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? $data['email'] : '';
$senha = isset($data['senha']) ? $data['senha'] : '';

try {
    // Verifica se os campos estão vazios
    if (empty($email) || empty($senha)) {
        throw new Exception("Email e senha são obrigatórios");
    }

    // Primeiro tenta buscar na tabela de clientes
    $sqlCliente = "SELECT id, nome, email, tipo FROM clientes WHERE email = ? AND senha = ? LIMIT 1";
    $stmtCliente = $conn->prepare($sqlCliente);
    $stmtCliente->execute([$email, $senha]);
    $usuario = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    // Se não encontrou como cliente, busca como empresa
    if (!$usuario) {
        $sqlEmpresa = "SELECT id, nomeEmpresa as nome, email, tipo FROM empresas WHERE email = ? AND senha = ? LIMIT 1";
        $stmtEmpresa = $conn->prepare($sqlEmpresa);
        $stmtEmpresa->execute([$email, $senha]);
        $usuario = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);
    }

    // Se encontrou usuário
    if ($usuario) {
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'tipoUser' => $usuario['tipo']
            ]
        ]);
    } else {
        throw new Exception("Email ou senha inválidos");
    }

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>