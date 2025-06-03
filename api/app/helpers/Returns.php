<?php 

class ResponseReturn
{
    public static function ReturnRequest($status, $message)
    {
        $return = [
            'status' => $status,
            'message' => $message
        ];

        echo json_encode($return);
        exit;
    }

    public static function ReturnResult($total, $registros)
    {
        $return = [
            'total' => $total,
            'registros' => $registros
        ];

        echo json_encode($return);
        exit;
    }
}

?>