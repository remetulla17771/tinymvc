<?php

namespace app\models;

use app\ActiveRecord;
use app\Auth;

class Shezhire extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'shezhire';
    }

    public function toArray(): array
    {
        return $this->attributes;
    }


}
