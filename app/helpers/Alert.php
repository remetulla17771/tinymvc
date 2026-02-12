<?php

namespace app\helpers;

class Alert
{
    public static function add($type, $message)
    {


        if (!isset($_SESSION['alert'])) {
            $_SESSION['alert'] = [];
        }

        $_SESSION['alert'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public static function get()
    {


        return $_SESSION['alert'] ?? [];
    }

    public static function getAll()
    {


        $view = '';

        if (!empty($_SESSION['alert'])) {
            foreach ($_SESSION['alert'] as $alert) {
                $view .= "<div role='alert' class='alert alert-{$alert['type']} d-flex align-items-center justify-content-between'>
    <div>{$alert['message']}</div>
    <div class='btn btn-outline-{$alert['type']}' >&times;</div>
</div>";
            }

            unset($_SESSION['alert']);
        }

        return $view;
    }
}
