<?php
class AuthController {
    private $usuario;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function registrar($dados) {
        // Validações
        // Processamento de registro
        $this->usuario->email = $dados['email'];
        $this->usuario->senha = $dados['senha'];
        $this->usuario->nome = $dados['nome'];

        return $this->usuario->criar();
    }

    public function login($email, $senha) {
        // Lógica de login
    }
}