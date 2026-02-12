<?php

namespace app;

class Query
{
    private string $modelClass;
    private array $where = [];

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function where(array $condition): self
    {
        $this->where = $condition;
        return $this;
    }

    public function one()
    {
        $results = $this->all();
        return $results[0] ?? null;
    }

    public function all(): array
    {
        $model = $this->modelClass;
        $table = $model::tableName();

        $sql = "SELECT * FROM $table";
        $params = [];

        if ($this->where) {
            $conditions = [];
            foreach ($this->where as $key => $value) {
                $conditions[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = Db::getInstance()->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll();


        return array_map(function ($row) use ($model) {
            $obj = new $model();
            foreach ($row as $k => $v) {
                $obj->$k = $v;
            }
            return $obj;
        }, $rows);


    }
}
