<?php

require_once __DIR__ . "/../../../config/core.php";
require_once __DIR__ . "/../../helpers/Returns.php";

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

    public function SelectAll($table)
    {
        $sql = "SELECT * FROM $table";

        try {
            $stmt = $this->core->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = $stmt->rowCount();

            return ResponseReturn::ReturnResult($total, $result);
        } catch (PDOException $error) {
            $e = 'Erro no banco de dados: ' . $error->getMessage();
            return ResponseReturn::ReturnRequest('error', $e);
        }
    }

    public function SelectWhere($table, $conditions)
    {
        if (empty($conditions)) {
            return ResponseReturn::ReturnRequest('error', 'Nenhuma condição especificada');
        }

        $where = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $placeholders = implode(', ', array_fill(0, count($value), '?'));
                $where[] = "$column IN ($placeholders)";
                $params = array_merge($params, $value);
            } else {
                $where[] = "$column = ?";
                $params[] = $value;
            }
        }

        $where_str = implode(' AND ', $where);

        $sql = "SELECT * FROM $table WHERE $where_str";

        try {
            $stmt = $this->core->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = $stmt->rowCount();

            return ResponseReturn::ReturnResult($total, $result);
        } catch (PDOException $error) {
            $e = 'Erro no banco de dados: ' . $error->getMessage();
            return ResponseReturn::ReturnRequest('error', $e);
        }
    }

    public function Insert($table, $data)

    {
        if (empty($data)) 
        {
            return ResponseReturn::ReturnRequest('error', 'Dados do insert não podem ser vazios');
        }

        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        $values = array_values($data);

        $columns_str = implode(', ', $columns);
        $placeholders_str = implode(', ', $placeholders);

        $sql = "INSERT INTO $table ($columns_str) VALUES ($placeholders_str)";

        try {
            $stmt = $this->core->prepare($sql);
            $stmt->execute($values);
            $lastId = $this->core->lastInsertId();

            return ResponseReturn::ReturnRequest('success', 'Registro inserido com sucesso', ['id' => $lastId]);
        } catch (PDOException $e) {
            $error = 'Erro no banco de dados: ' . $e->getMessage();
            return ResponseReturn::ReturnRequest('error', $error);
        }
    }

    public function Update($table, $data, $conditions)
    {
        if (empty($data) || empty($conditions)) {
            return ResponseReturn::ReturnRequest('error', 'Dados ou condições não podem ser vazios');
        }

        $set = [];
        $params = [];

        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            $params[] = $value;
        }

        $where = [];

        foreach ($conditions as $column => $value) {
            $where[] = "$column = ?";
            $params[] = $value;
        }

        $set_str = implode(', ', $set);
        $where_str = implode(' AND ', $where);

        $sql = "UPDATE $table SET $set_str WHERE $where_str";

        try {
            $stmt = $this->core->prepare($sql);
            $stmt->execute($params);
            $rowCount = $stmt->rowCount();

            return ResponseReturn::ReturnRequest('success', "Registro atualizado com sucesso ($rowCount registro(s) afetado(s))");
        } catch (PDOException $e) {
            $error = 'Erro no banco de dados: ' . $e->getMessage();
            return ResponseReturn::ReturnRequest('error', $error);
        }
    }

    public function Delete($table, $conditions)
    {
        if (empty($conditions)) {
            return ResponseReturn::ReturnRequest('error', 'Condições para exclusão não podem ser vazias');
        }

        $where = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $where[] = "$column = ?";
            $params[] = $value;
        }

        $where_str = implode(' AND ', $where);

        $sql = "DELETE FROM $table WHERE $where_str";

        try {
            $stmt = $this->core->prepare($sql);
            $stmt->execute($params);
            $rowCount = $stmt->rowCount();

            return ResponseReturn::ReturnRequest('success', "Registro deletado com sucesso ($rowCount registro(s) removido(s))");
        } catch (PDOException $e) {
            $error = 'Erro no banco de dados: ' . $e->getMessage();
            return ResponseReturn::ReturnRequest('error', $error);
        }
    }
}
