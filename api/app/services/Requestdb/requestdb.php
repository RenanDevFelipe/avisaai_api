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

    public function SelectAll($table)
    {
        try {

            $select = "SELECT * FROM $table";
            $stmt = $this->core->prepare($select);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'total' => $stmt->rowCount(),
                'registros' => $result
            ];

        } catch(PDOException $error){
            return [
                'status' => 'error',
                'message' => 'Erro no banco de dados: ' . $error->getMessage()
            ];
        }
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