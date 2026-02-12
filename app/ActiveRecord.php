<?php

namespace app;

abstract class ActiveRecord
{
    protected array $attributes = [];

    abstract public static function tableName(): string;

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function load(array $data, ?string $formName = null)
    {
//        if ($formName === null) {
//            $formName = (new \ReflectionClass($this))->getShortName();
//        }
//
//        if (isset($data[$formName]) && is_array($data[$formName])) {
//            $data = $data[$formName];
//        }
//
//        $loaded = false;

        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return true;


    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }



    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public static function find()
    {
        return new Query(static::class);
    }

    public static function findOne(int $id)
    {
        return static::find()
            ->where(['id' => $id])
            ->one();
    }

    public function delete()
    {
        if (!isset($this->attributes['id'])) {
            throw new \Exception('Невозможно удалить: id не задан');
        }

        $db = Db::getInstance();
        $sql = "DELETE FROM " . static::tableName() . " WHERE id = :id";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            'id' => $this->attributes['id']
        ]);
    }


    public static function deleteAll(array $condition = [])
    {
        $sql = "DELETE FROM " . static::tableName();
        $params = [];

        if (!empty($condition)) {
            $parts = [];
            foreach ($condition as $k => $v) {
                $parts[] = "$k = :$k";
                $params[$k] = $v;
            }
            $sql .= " WHERE " . implode(' AND ', $parts);
        }

        $stmt = Db::pdo()->prepare($sql);
        return $stmt->execute($params);
    }



    public function save(): bool
    {
        $db = Db::getInstance();

        if (isset($this->attributes['id'])) {
            // update
            $fields = [];
            foreach ($this->attributes as $key => $value) {
                if ($key === 'id') continue;
                $fields[] = "$key = :$key";
            }

            $sql = "UPDATE " . static::tableName()
                . " SET " . implode(', ', $fields)
                . " WHERE id = :id";


        } else {
            // insert
            $keys = array_keys($this->attributes);
            $columns = implode(',', $keys);
            $params  = ':' . implode(',:', $keys);

            $sql = "INSERT INTO " . static::tableName()
                . " ($columns) VALUES ($params)";
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($this->attributes);
    }
}
