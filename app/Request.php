<?php
namespace app;
class Request extends ObjectManager {
//    public function getSegments(): array {
//        $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
//        return $path === '' ? [] : explode('/', $path);
//    }

    public function getSegments()
    {
        return UrlManager::parseRequest($_SERVER['REQUEST_URI']);
    }

    public function isPost()
    {

        if (isset($_POST) && $_POST) {
            return true;
        } else {
            return false;
        }

    }

    public function rawData()
    {
        $rawInput = file_get_contents('php://input');
        return json_decode($rawInput, true);
    }

    public function get($key = null)
    {
        if(isset($key)){
            return $_GET[$key] ?? null;
        }
        return $_GET;
    }

    public function post($key = null)
    {
//        unset($_POST['submit']);
        if (isset($key)) {
            return $_POST[$key];
        } else {

            return $_POST;
        }
    }

}
