<?php
namespace app\helpers;

class AssetBundle
{
    public $basePath = '';
    public $baseUrl = '';
    public $css = [];
    public $js = [];
    public $depends = [];

    public function registerCss()
    {
        // Register dependent asset bundles
        foreach ($this->depends as $depend) {
            $dependAsset = new $depend();
            $dependAsset->registerCss();
        }

        // Register CSS files
        foreach ($this->css as $cssFile) {
            if(preg_match('/http/', $cssFile)){
                echo "<link rel='stylesheet' type='text/css' href='". $cssFile . "'>\n";
            }
            else{
                echo "<link rel='stylesheet' type='text/css' href='". $this->baseUrl . '/' . $cssFile . "'>\n";
            }

        }
    }

    public function registerJs(){

        // Register dependent asset bundles
        foreach ($this->depends as $depend) {
            $dependAsset = new $depend();
            $dependAsset->registerJs();
        }

        // Register JS files
        foreach ($this->js as $jsFile) {
            if(preg_match('/http/', $jsFile)){
//                echo '<script src="'. $jsFile . '">' . "\n";
                echo '<script src="'.$jsFile . '"></script>' . "\n";
            }
            else{
                echo '<script src="' . $this->baseUrl . '/' . $jsFile . '"></script>' . "\n";
//                echo '<script src="' . $this->baseUrl . '/' . $jsFile . '">' . "\n";
            }
        }
    }
}
