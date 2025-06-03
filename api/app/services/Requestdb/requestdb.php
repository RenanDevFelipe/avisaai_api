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
        } catch (PDOException $error) {
            return [
                'status' => 'error',
                'message' => 'Erro no banco de dados: ' . $error->getMessage()
            ];
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
            $e = 'Erro no banco de dados' . $error->getMessage();
            return ResponseReturn::ReturnRequest('error', $e);
        }
    }

    public function Insert($table, $data)
    {
        if (empty($data)) {
            ResponseReturn::ReturnRequest('error', 'Dados do insert não pode ser vazio');
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
            return $this->core->lastInsertId();
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function Update($table, $data, $conditions)
    {
        if (empty($data)) {
            return ['error' => 'Dados para atualização não podem estar vazios.'];
        }
        if (empty($conditions)) {
            return ['error' => 'Nenhuma condição especificada para WHERE.'];
        }

        $set = [];
        $params = [];

        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            $params[] = $value;
        }

        $where = [];
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

        $set_str = implode(', ', $set);
        $where_str = implode(' AND ', $where);

        $sql = "UPDATE $table SET $set_str WHERE $where_str";

        try {
            $stmt = $this->core->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }


    public function Delete($table, $conditions)
    {
        if (empty($conditions)) {
            return ['error' => 'Nenhuma condição especificada para WHERE. DELETE perigoso foi bloqueado.'];
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

        $sql = "DELETE FROM $table WHERE $where_str";

        try {
            $stmt = $this->core->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
