<?php 

class ResponseReturn
{
    public static function ReturnRequest($status, $message)
    {
        $return = [
            'status' => $status,
            'message' => $message
        ];

        return $return;
    }
}

?>