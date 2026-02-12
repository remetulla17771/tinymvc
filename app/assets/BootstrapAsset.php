<?php
namespace app\assets;


use app\helpers\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $basePath = '/assets';
    public $baseUrl = '/assets';

    public $css = [
        'bootstrap/bootstrap.css', // Example of using a CDN
    ];

    public $js = [
        'bootstrap/jquery.js', // Example of using a CDN
        'bootstrap/popper.js', // Example of using a CDN
        'bootstrap/bootstrap.js', // Example of using a CDN
    ];
}
