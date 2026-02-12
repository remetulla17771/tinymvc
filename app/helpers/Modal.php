<?php
namespace app\helpers;

class Modal
{
    private static array $stack = [];

    public static function begin(array $config = []): void
    {
        if (empty($config['id'])) {
            throw new \Exception('Modal id is required');
        }

        self::$stack[] = $config;

        $id    = $config['id'];
        $title = $config['title'] ?? '';
        $ajax  = $config['ajax'] ?? null;

        echo "<div class='modal' id='{$id}' style='display:none'>";
        echo "<div class='modal-overlay' modal-close='{$id}'></div>";
        echo "<div class='modal-window'>";
        echo "<div class='modal-header'>";

        if(isset($title)){
            echo "<h3>{$title}</h3>";
        }


        echo "<button modal-close='{$id}'>×</button>";
        echo "</div>";
        echo "<div class='modal-body' data-ajax='{$ajax}'>";
    }

    public static function end(): void
    {
        if (empty(self::$stack)) {
            throw new \Exception('Modal::end() without begin()');
        }

        array_pop(self::$stack);

        echo "</div></div></div>";
    }
}
