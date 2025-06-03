<?php 

require_once __DIR__ . '/../../../config/core.php';
require_once __DIR__ . '/../../helpers/Headers.php';
require_once __DIR__ . '/../Requestdb/requestdb.php';
require_once __DIR__ . '/../../helpers/Returns.php';
Headers::AllHeaders();


class userService
{

    private $request;

    public function __construct()
    {   
        $this->request = new RequestDataBase;
    }
    
    public function createUser($method)
    {
        try{

            if($method !== "POST")
            {
                return [
                    'status' => 'error',
                    'message' => 'Requisição inválida'
                ];
            }
            
            // DECLARAR VARIAVEIS PARA RECEBER COM FORM DATA
            $name = trim($_POST['name']) ?? null;
            $email = trim($_POST['email']) ?? null;
            $password = $_POST['password'] ?? null;

            // VERIFICAMOS QUE OS CAMPOS RECEBIDOS PELO FORM DATA ESTÁ EM BRANCO
            if(empty($name) || empty($email) || empty($password))
            {
                return[
                    'status' => 'error',
                    'message' => 'Todos os campos são obrigatórios'
                ];
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                return[
                    'status' => 'error',
                    'message' => 'E-mail fornecido não é válido'
                ];
            }


            // CRIPTOGRAFAR A SENHA
            $senhaHash = password_hash($password, PASSWORD_DEFAULT);


            $verification = '';



        } catch(PDOException $e){
            return [
                'status' => 'error',
                'message' => 'Erro no banco de dados: ' . $e->getMessage()
            ];
        }
    }

    public function getAllUser($method)
    {
        if ($method !== "GET")
        {
            return ResponseReturn::ReturnRequest('error', 'Requisição inválida');
        }

        $getAll = $this->request->SelectAll('users');

        return $getAll;
    }
}

?>