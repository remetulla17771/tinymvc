<?php

class ObjectManager
{
    public function __construct(array $config = []) {
        foreach ($config as $prop => $val) {
            // Проверяем, можно ли записать это свойство
            if (property_exists($this, $prop)) {
                $this->$prop = $val;
            } else {
                // Исправляем имя переменной и делаем текст ошибки логичным
                $className = static::class;
                throw new \Exception("Property '{$prop}' does not exist in component class '{$className}'.");
            }
        }
    }
}