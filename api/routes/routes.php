<?php

require_once __DIR__ . '/../app/controllers/users/userControl.php';

// DECLARAR VARIAVEIS PARA OS CONTROLLS
$userControl = new userControl;


$method = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace(['/avisaai_api/api/public', '/avisaai_api/api'], '', $uri);
$uri = trim(parse_url($uri, PHP_URL_PATH), "/");


if ($uri == "Account/Login")
{
   
}

elseif ($uri == "User/Create")
{
    $userControl->createUser($method);
}

elseif ($uri == "User/getAll")
{
    $userControl->getAll($method);
}

?>