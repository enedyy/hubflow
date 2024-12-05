<?php

use Api\controller\Rest;
use Api\util\MensagensUtil;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5174");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

// Autoload para as classes
require_once "./vendor/autoload.php";

date_default_timezone_set("America/Sao_Paulo");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}

// Teste de conexão
if (empty($_REQUEST)) {
    MensagensUtil::sucesso();
}

if (isset($_REQUEST) && !empty($_REQUEST)) {
    $rest = new Rest($_REQUEST);
    echo $rest->iniciar();
}
