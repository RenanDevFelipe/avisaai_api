<?php 

class ResponseHelper
{
    public static function jsonResponse($data)
    {
        echo json_encode($data);
        exit;
    }
}

?>