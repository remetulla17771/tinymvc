<?php


namespace app\helpers;

class Session
{
    public function __construct()
    {
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                $this->$key = $value;
            }
        }

    }

    public function get($key = null)
    {

        if(is_array($key)){

            $result = [];

            foreach ($key as $k) {
                $result[$k] = $_SESSION[$k];
            }

            return $result;

        }
        elseif ($key){
            return $_SESSION[$key] ?: null;
        }
        else{
            return $_SESSION;
        }


    }

    public function setArray($array)
    {
        foreach ($array as $key => $value){
            $_SESSION[$key] = $value;
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
}

