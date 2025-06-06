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

    // CREATE USER
    public function createUser($method)
    {
        try {
            if ($method !== "POST") {
                return ResponseReturn::ReturnRequest('error', 'Requisição inválida. Use método POST.');
            }

            $name = trim($_POST['name'] ?? '');
            $phone_number = trim($_POST['phone_number'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($name) || empty($email) || empty($password) || empty($phone_number)) {
                return ResponseReturn::ReturnRequest('error', 'Todos os campos são obrigatórios.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ResponseReturn::ReturnRequest('error', 'E-mail fornecido não é válido.');
            }

            //Verifica se o email já existe
            $params = ['email' => $email];
            $verification = $this->request->SelectWhere('users', $params);

            if ($verification['total'] > 0) {
                return ResponseReturn::ReturnRequest('error', 'Email já está cadastrado.');
            }

            // Insere novo usuário
            $senhaHash = password_hash($password, PASSWORD_DEFAULT);
            $dados = [
                'name' => $name,
                'phone_number' => $phone_number,
                'email' => $email,
                'password' => $senhaHash,
                'date_creation_account' => date('Y-m-d H:i:s'),
                'status' => '1'
            ];

            $insertResult = $this->request->Insert('users', $dados);

            if ($insertResult['status'] === 'error') {
                return $insertResult;
            }

            return ResponseReturn::ReturnRequest('success', 'Usuário criado com sucesso.');
        } catch (PDOException $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro no banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro inesperado: ' . $e->getMessage());
        }
    }

    // GET ALL USERS
    public function getAllUser($method)
    {
        try {
            if ($method !== "GET") {
                return ResponseReturn::ReturnRequest('error', 'Requisição inválida. Use método GET.');
            }

            return $this->request->SelectAll('users');
        } catch (PDOException $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro no banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro inesperado: ' . $e->getMessage());
        }
    }

    // GET ONE USER
    public function getOneUser($method, $id)
    {
        try {
            if ($method !== "POST") {
                return ResponseReturn::ReturnRequest('error', 'Requisição inválida. Use método POST.');
            }

            if (empty($id)) {
                return ResponseReturn::ReturnRequest('error', 'ID do usuário é obrigatório.');
            }

            $params = ['id' => $id];
            return $this->request->SelectWhere('users', $params);
        } catch (PDOException $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro no banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro inesperado: ' . $e->getMessage());
        }
    }

    // UPDATE USER
    public function updateUser($method)
    {
        try {
            if ($method !== 'POST') {
                return ResponseReturn::ReturnRequest('error', 'Método inválido');
            }

            // RECEBER OS CAMPOS DO FORM
            $id = $_POST['id'];
            $name = trim($_POST['name']) ?? null;
            $phone_number = trim($_POST['phone_number']) ?? null;
            $email = trim($_POST['email']) ?? null;
            $password = $_POST['password'] ?? null;

            // VALIDAÇÃO BÁSICA
            if (empty($name) || empty($email) || empty($phone_number)) {
                return ResponseReturn::ReturnRequest('error', 'Nome, e-mail e telefone são obrigatórios');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ResponseReturn::ReturnRequest('error', 'Email inválido');
            }

            // PREPARAR DADOS PARA UPDATE
            $dataToUpdate = [
                'name' => $name,
                'phone_number' => $phone_number,
                'email' => $email
            ];

            // SE A SENHA FOI INFORMADA, ADICIONA NO UPDATE
            if (!empty($password)) {
                $dataToUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            // DEFINIR CONDIÇÃO (id do usuário que queremos atualizar)
            $conditions = ['id' => $id];

            // CHAMAR O UPDATE
            $updateResult = $this->request->Update('users', $dataToUpdate, $conditions);

            return $updateResult;
        } catch (PDOException $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro no banco de dados: ' . $e->getMessage());
        }
    }


    // DELETE USER
    public function deleteUser($method, $id)
    {
        try {
            if ($method !== "DELETE") {
                return ResponseReturn::ReturnRequest('error', 'Requisição inválida. Use método POST.');
            }

            if (empty($id)) {
                return ResponseReturn::ReturnRequest('error', 'ID do usuário é obrigatório.');
            }

            $conditions = ['id' => $id];

            $deleteResult = $this->request->Delete('users', $conditions);

            if ($deleteResult['status'] === 'error') {
                return $deleteResult;
            }

            return ResponseReturn::ReturnRequest('success', 'Usuário deletado com sucesso.');
        } catch (PDOException $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro no banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            return ResponseReturn::ReturnRequest('error', 'Erro inesperado: ' . $e->getMessage());
        }
    }
}
