<?php 

$method = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace(['/avisaai_api/api/public', '/avisaai_api/api'], '', $uri);
$uri = trim(parse_url($uri, PHP_URL_PATH), "/");


if ($uri == "Account/Login")
{
    echo json_encode([
        'status' => 'success',
        'message' => 'Api Funcionando'
    ]);
}

?>