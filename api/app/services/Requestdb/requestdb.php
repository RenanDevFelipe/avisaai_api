<?php 

require_once __DIR__ . "/../../../config/core.php";

class RequestDataBase
{

    private $core;

    public function __construct()
    {
        $db = new DataBase;
        $this->core = $db->getConnetion();
    }

    function db()
    {
        return $this->core;
    }

    public function SelectAll()
    {

    }

    public function SelectWhere()
    {

    }

    public function Insert()
    {
        
    }

    public function Update()
    {

    }

    public function Delete()
    {

    }
}

?>