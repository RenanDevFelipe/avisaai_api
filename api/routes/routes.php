<?php

require_once __DIR__ . '/../app/controllers/users/userControl.php';
require_once __DIR__ . '/../app/controllers/users/accountControl.php';
require_once __DIR__ . '/../app/helpers/FilesContents.php';

// DECLARAR VARIAVEIS PARA OS CONTROLLS
$userControl = new userControl;
$accountControl = new accountControl;
$filesContent = new FilesContents;

$method = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace(['/avisaai_api/api/public', '/avisaai_api/api'], '', $uri);
$uri = trim(parse_url($uri, PHP_URL_PATH), "/");



/// *************************************************************** ACCOUNT ROUTE ****************************************************************** \\\

if ($uri == "Account/Login")
{
    $accountControl->Login($method);
}

/// *************************************************************** ACCOUNT ROUTE ****************************************************************** \\\




/// *************************************************************** USER ROUTE *************************************************************** \\\

elseif ($uri == "User/Create")
{
    $userControl->createUser($method);
}

elseif ($uri == "User/Update")
{
    $userControl->updateUser($method);
}

elseif ($uri == "User/Delete")
{
    $data = $filesContent->phpContent();
    FilesContents::ErrorJson($data);
    $userControl->deleteUser($method, $data['id']);
}

elseif ($uri == "User/getAll")
{
    $userControl->getAll($method);
}

elseif ($uri == "User/getOne")
{   
    $data = $filesContent->phpContent();
    FilesContents::ErrorJson($data);
    $userControl->getOneUser($method, $data['id']);
}

/// *************************************************************** USER ROUTE *************************************************************** \\\





else{
    echo json_encode(ResponseReturn::ReturnRequest('error', 'Rota inexistente'));
}
?>