<?php

require_once 'config.php';

class DataBase
{
    private $pdo;

    public function __construct()
    {
        try {

            $dns = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->pdo = new PDO($dns, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);

        } catch (PDOException $e){
            die("Erro ao conectar no bando de dados: " . $e->getMessage());
        }
    }

    public function getConnetion()
    {
        return $this->pdo;
    }
}

?>