<?php

namespace app\helpers;

class I18n
{
    public static string $language = 'ru';
    public static string $sourceLanguage = 'en';

    protected static array $messages = [];

    public function __construct()
    {

    }

    public function language()
    {
        self::$language = $_SESSION['lang'];
        return self::$language;
    }


    public static function t(string $category, string $message, array $params = []): string
    {
        $translation = self::getMessage($category, $message);

        if ($params) {
            foreach ($params as $key => $value) {
                $translation = str_replace("{{$key}}", $value, $translation);
            }
        }

        return $translation;
    }

    protected static function getMessage(string $category, string $message): string
    {
        $lang = self::$language;

        if (!isset(self::$messages[$lang][$category])) {
            self::loadMessages($lang, $category);
        }

        return self::$messages[$lang][$category][$message]
            ?? $message; // fallback
    }

    protected static function loadMessages(string $lang, string $category): void
    {
        $file = dirname(__DIR__) . "/messages/$lang/$category.php";

        if (is_file($file)) {
            self::$messages[$lang][$category] = require $file;
        } else {
            self::$messages[$lang][$category] = [];
        }
    }
}
