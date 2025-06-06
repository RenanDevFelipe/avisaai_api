<?php 

class ResponseReturn
{
    public static function ReturnRequest($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message
        ];
    }

    public static function ReturnResult($total, $registros)
    {
        return [
            'total' => $total,
            'registros' => $registros
        ];
    }
}


?>