<?php
namespace app;

use app\helpers\I18n;

class UrlManager
{
    public string $controller = 'site';
    public string $action = 'index';
    protected array $params = [];


    public function __construct()
    {
        $this->parse();
//        self::parseRequest($_SERVER['REQUEST_URI']);
    }

    protected function parse(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = trim($path, '/');

        if ($path === '') {
            return;
        }

        $segments = explode('/', $path);

        $this->controller = array_shift($segments);
        $this->action = $segments ? array_shift($segments) : 'index';
        $this->params = $segments;

    }

    public function getControllerClass(): string
    {
        return 'app\\controllers\\' . ucfirst($this->controller) . 'Controller';
    }

    public function getActionMethod(): string
    {
        return 'action' . ucfirst($this->action);
    }

    public function getParams(): array
    {
        return $this->params;
    }



    public static array $languages = ['ru', 'en', 'kz'];
    public static string $defaultLanguage = 'ru';

    public static function parseRequest(string $uri): array
    {
        $path = trim(parse_url($uri, PHP_URL_PATH), '/');
        $segments = $path === '' ? [] : explode('/', $path);

        $lang = self::$defaultLanguage;

        if (!empty($segments) && in_array($segments[0], self::$languages, true)) {
            $lang = array_shift($segments);
        }

        I18n::$language = $lang;



        return $segments;
    }


    public function pasteUrlLanguage($lang)
    {
        $urlString = $_SERVER['REQUEST_URI'];

        // Разбираем URL на путь и параметры
        $parts = parse_url($urlString);
        $query = [];
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        // Добавляем или обновляем lang из параметра функции
        if ($lang) {
            $query['lang'] = $lang;
        }

        // Собираем URL обратно
        $newQuery = http_build_query($query);
        $urlString = $parts['path'] . ($newQuery ? '?' . $newQuery : '');

        return $urlString;
    }







}
