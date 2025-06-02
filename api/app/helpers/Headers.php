<?php

class Headers
{
    public static function AllHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Se for uma requisição OPTIONS, encerre aqui.
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204); // Sem conteúdo
            exit;
        }
    }
}

?>