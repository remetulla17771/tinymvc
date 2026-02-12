<?php
namespace app\helpers;
class MetaTagManager
{
    private static $metaTags = [];

    public static function register($metaTag)
    {
        self::$metaTags[] = $metaTag;
    }

    public static function render()
    {
        foreach (self::$metaTags as $metaTag) {
            $attributes = '';
            foreach ($metaTag as $key => $value) {
                $attributes .= " $key='$value'";
            }
            echo "<meta$attributes>\n";
        }
        return "";
    }
}