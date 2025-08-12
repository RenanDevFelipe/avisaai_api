<?php 

require_once __DIR__ . '/../../services/account/login.php';
require_once __DIR__ . '/../../helpers/ResponseHelper.php';

class accountControl
{
    private $service;

    public function __construct()
    {
        $this->service = new AccountService();
    }

    public function Login($method)
    {
        $data = $this->service->ServiceLogin($method);
        ResponseHelper::jsonResponse($data);
    }
}

?>