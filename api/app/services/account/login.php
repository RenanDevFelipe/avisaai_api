<?php 
require_once __DIR__ . '/../../../config/core.php';
require_once __DIR__ . '/../../helpers/Headers.php';
require_once __DIR__ . '/../Requestdb/requestdb.php';
require_once __DIR__ . '/../../helpers/Returns.php';
require_once __DIR__ . '/../../../config/TokenGerator.php';
Headers::AllHeaders();


class AccountService
{
    private $request;
    private $token;

    public function __construct()
    {
        $this->request = new RequestDataBase;
        $this->token = new Token;
    }

    public function ServiceLogin($method)
    {
        $erros_email = [
            "Usuário não encontrado na base de dados!",
            "E-mail não corresponde a nenhum usuário!",
            "E-mail não cadastrado!"
        ];

        $rand_erros = array_rand($erros_email);

        if($method !== "POST"){
            return ResponseReturn::ReturnRequest('error', 'Requisição inválida');
        }

        try {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $params = [
                'email' => $email
            ];

            $user = $this->request->SelectWhere('users', $params);

            if(!$user) {
                return ResponseReturn::ReturnRequest('error', $rand_erros);
            }

            if ($user && password_verify($password, $user['registros'][0]['password'])){
                $data = [
                    "id" => $user['registros'][0]["id"],
                    "name" => $user['registros'][0]["name"]
                ];

                $acesstoken = $this->token->gerarToken($data);

                return ([
                    "acess_token" => $acesstoken,
                    'name' => $user['registros'][0]['name'],
                    'email' => $user['registros'][0]['email'],
                    'staus' => $user['registros'][0]['status']
                ]);
            } else {
                return ResponseReturn::ReturnRequest('error', 'Credenciais inválidas!');
                // return $user['registros'][0]['password'];
            }

        } catch (PDOException $e){
            return ResponseReturn::ReturnRequest('error', 'Erro no banco de dados: ' . $e->getMessage());
        }
    }
}

?>