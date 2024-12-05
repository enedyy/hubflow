<?php

    namespace Api\database\mysql\model;

    class User{

        public $id;
        public $email; 
        public $senha; 
        public $tipoUsuario; 

        public $clienteId;
        public $empresaId;
        public $funcionarioId;

        public function getHashSenha(){
            // md5
            // hash_hmac
            return password_hash($this->senha, PASSWORD_DEFAULT);
        }
    
    }   