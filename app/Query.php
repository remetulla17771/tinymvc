<?php

namespace app;

class Query
{
    private string $modelClass;
    private array $where = [];


    private ?int $limit = null;
    private int $offset = 0;
    private array $orderBy = []; // ['id' => 'DESC']

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function where(array $condition): self
    {
        $this->where = $condition;
        return $this;
    }


    public function limit(int $limit): self
    {
        $this->limit = max(1, $limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = max(0, $offset);
        return $this;
    }

    public function orderBy(array $columns): self
    {
        // пример: ['id' => 'DESC', 'created_at' => 'ASC']
        $this->orderBy = $columns;
        return $this;
    }



    public function one()
    {
        $results = $this->all();
        return $results[0] ?? null;
    }



    public function count(): int
    {
        $model = $this->modelClass;
        $table = $model::tableName();

        $sql = "SELECT COUNT(*) AS cnt FROM `$table`";
        $params = [];

        if ($this->where) {
            $conditions = [];
            foreach ($this->where as $key => $value) {
                $this->assertColumn($key);
                $conditions[] = "`$key` = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = Db::getInstance()->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();
        return (int)($row['cnt'] ?? 0);
    }


    public function all(): array
    {
        $model = $this->modelClass;
        $table = $model::tableName();

        $sql = "SELECT * FROM `$table`";
        $params = [];

        if ($this->where) {
            $conditions = [];
            foreach ($this->where as $key => $value) {
                $this->assertColumn($key);
                $conditions[] = "`$key` = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        if ($this->orderBy) {
            $parts = [];
            foreach ($this->orderBy as $col => $dir) {
                $this->assertColumn($col);
                $dir = strtoupper((string)$dir);
                $dir = ($dir === 'DESC') ? 'DESC' : 'ASC';
                $parts[] = "`$col` $dir";
            }
            $sql .= " ORDER BY " . implode(', ', $parts);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . (int)$this->limit . " OFFSET " . (int)$this->offset;
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

    private function assertColumn(string $name): void
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new \RuntimeException("Bad column name: $name");
        }
    }
}
