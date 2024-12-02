<?php
require_once '../models/Cliente.php';
require_once '../models/Empresa.php';
require_once '../models/Usuario.php';
require_once '../api/headers.php';

class CadastroController {
    public function cadastrarCliente($nome, $endereco, $telefone, $email, $dataNascimento, $senha) {
        $usuario = new Usuario();
        
        if ($usuario->verificarEmail($email)) {
            throw new Exception('E-mail já cadastrado.');
        }
        
        $cliente = new Cliente();
        $clienteId = $cliente->cadastrar($nome, $endereco, $telefone, $email, $dataNascimento);
        
        $usuarioId = $usuario->cadastrar($email, $senha, 'Cliente', $clienteId);
        
        return ['clienteId' => $clienteId, 'usuarioId' => $usuarioId];
    }

    public function cadastrarEmpresa($nomeEmpresa, $nomeDono, $email, $telefone, $endereco, $descricao, $senha) {
        $usuario = new Usuario();
        
        if ($usuario->verificarEmail($email)) {
            throw new Exception('E-mail já cadastrado.');
        }
        
        $empresa = new Empresa();
        $empresaId = $empresa->cadastrar($nomeEmpresa, $nomeDono, $email, $telefone, $endereco, $descricao);
        
        $usuarioId = $usuario->cadastrar($email, $senha, 'Empresa');
        
        return ['empresaId' => $empresaId, 'usuarioId' => $usuarioId];
    }
}