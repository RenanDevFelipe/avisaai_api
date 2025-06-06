<?php

class FilesContents
{
    public function phpContent()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    public static function ErrorJson($data)
    {
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["erro" => "Erro ao processar JSON: " . json_last_error_msg()]);
            exit;
        }
    }
}
