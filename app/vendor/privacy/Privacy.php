<?php

namespace app\vendor\privacy;

class Privacy
{

    public static function md5($data)
    {
        return md5($data);
    }

    public static function password_hash($password, $algo, $options = [])
    {
        return password_hash($password, $algo, $options);
    }

    public static function password_verify($password, $password_hash)
    {
        return password_verify($password, $password_hash);
    }

}