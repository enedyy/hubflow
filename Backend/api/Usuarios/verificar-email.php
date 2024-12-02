<?php
require_once("../headers.php");
require_once '../../config/database.php';

// Recebe os dados JSON
$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? $data['email'] : '';

try {
    // Verifica se email está vazio
    if (empty($email)) {
        throw new Exception("Email não fornecido");
    }

    // Prepara a consulta para verificar tanto na tabela de clientes quanto na de empresas
    $sqlCliente = "SELECT email FROM clientes WHERE email = ?";
    $sqlEmpresa = "SELECT email FROM empresas WHERE email = ?";
    
    // Verifica na tabela de clientes
    $stmtCliente = $conn->prepare($sqlCliente);
    $stmtCliente->execute([$email]);
    
    // Verifica na tabela de empresas
    $stmtEmpresa = $conn->prepare($sqlEmpresa);
    $stmtEmpresa->execute([$email]);

    // Se encontrar o email em qualquer uma das tabelas
    if ($stmtCliente->rowCount() > 0 || $stmtEmpresa->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'existe' => true,
            'message' => 'Email já cadastrado'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'existe' => false,
            'message' => 'Email disponível'
        ]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>