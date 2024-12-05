<?php

namespace Api\database\mysql;

use Api\util\MensagensUtil;
use PDO;
use Throwable;

class MySqlPDO
{

// DADOS DO BANCO DE DADOS
    // LOCAL
    private static $banco = "SGME_HUBFLOW";
    private static $usuario = "root";
    private static $senha = "";

    // Instância singleton.
    public static $instance;

    public static function getInstance(): PDO
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new PDO("mysql:host=localhost;dbname=" . self::$banco, self::$usuario, self::$senha);
                self::$instance->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
            } catch (Throwable $throwable) {
                MensagensUtil::erroInterno($throwable->getMessage());
            }
        }

        return self::$instance;
    }

}