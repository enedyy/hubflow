<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'SGME_HUBFLOW'; // Nome do seu banco
    private $username = 'root';         // Seu usuário do MySQL
    private $password = '';             // Sua senha do MySQL
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}