<?php 

require_once __DIR__ . '/../../services/user/userService.php';
require_once __DIR__ . '/../../helpers/ResponseHelper.php';


class userControl{
    private $service;

    public function __construct()
    {
        $this->service = new userService;
    }

    public function getAll($method)
    {
        $data = $this->service->getAllUser($method);
        ResponseHelper::jsonResponse($data);
    }
}
?>