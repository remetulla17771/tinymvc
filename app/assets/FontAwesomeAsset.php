<?php
namespace app\assets;


use app\helpers\AssetBundle;
use app\helpers\Html;

class FontAwesomeAsset extends AssetBundle
{
    public $basePath = '/assets';
    public $baseUrl = '/assets';

    public $css = [
        'fontawesome/index.css', // Example of using a CDN
        'fontawesome/brands.css', // Example of using a CDN
        'fontawesome/svg.css', // Example of using a CDN

    ];

    public $js = [
        'fontawesome/index.js',
        'fontawesome/brands.js',
        'fontawesome/fw.js',
    ];

    public static function icon($icon)
    {
        return Html::tag('i', '', ['class' => $icon]);
    }

}
