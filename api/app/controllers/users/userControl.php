<?php 

require_once __DIR__ . '/../../services/user/userService.php';
require_once __DIR__ . '/../../helpers/ResponseHelper.php';


class userControl{
    private $service;

    public function __construct()
    {
        $this->service = new userService;
    }

    public function createUser($method)
    {
        $data = $this->service->createUser($method);
        ResponseHelper::jsonResponse($data);
    }

    public function getAll($method)
    {
        $data = $this->service->getAllUser($method);
        ResponseHelper::jsonResponse($data);
    }

    public function getOneUser($method, $id)
    {
        $data = $this->service->getOneUser($method, $id);
        ResponseHelper::jsonResponse($data);
    }
}
?>