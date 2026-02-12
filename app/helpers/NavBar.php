<?php

namespace app\helpers;

class NavBar
{
    public $brandLabel;
    public $brandUrl;
    public $items = [];

    private $optionsString;
    private $ulClass = "";

    public function __construct($config = [])
    {
        if (isset($config['brandLabel'])) {
            $this->brandLabel = $config['brandLabel'];
        }

        if (isset($config['brandUrl'])) {
            $this->brandUrl = $config['brandUrl'];
        }

        if (isset($config['items'])) {
            $this->items = $config['items'];
        }


        if (!isset($config['options']['class'])) {
            $config['options']['class'] = 'navbar navbar-expand-lg navbar-light bg-light';
        }

        if (!isset($config['ulClass'])) {
            $this->ulClass = 'navbar-nav';
        }
        else{
            $this->ulClass = $config['ulClass'];
        }


        $optionsString = "";
        foreach ($config['options'] as $key => $val){
            $optionsString .= " $key='$val'";
        }

        $this->optionsString = $optionsString;

        $this->render();
    }

    public function render()
    {
        echo '<nav id="w0" '.$this->optionsString.'>';
        echo '<div class="container" style="display:flex; padding: 9px 0;">';
        echo '<a class="navbar-brand" href="' . $this->brandUrl . '">' . $this->brandLabel . '</a>';
        echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">';
        echo '<span class="navbar-toggler-icon"></span>';
        echo '</button>';
        echo '<div class="collapse navbar-collapse" id="navbarNav">';
        echo '<ul class="'.$this->ulClass.'">';

        // Render top-level items
        $this->renderItems($this->items);

        echo '</ul>';
        echo '</div>';
        echo '</div>';
        echo '</nav>';
    }

    private function renderItems($items, $isDropdown = false)
    {
        foreach ($items as $item) {

            if (isset($item['items'])) {

                echo '<li class="nav-item dropdown" style="color: white;">';
                echo '<a style="color: white;" class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">';
                echo $item['label'];
                echo '</a>';

                echo '<div class="dropdown-menu">';
                $this->renderItems($item['items'], true);
                echo '</div>';

                echo '</li>';

            } else {

                if ($isDropdown) {
                    echo '<a class="dropdown-item" href="'.$item['url'].'">';
                    echo $item['label'];
                    echo '</a>';
                } else {
                    echo '<li class="nav-item" style="color: white;">';
                    echo '<a style="color: white;" class="nav-link" href="'.$item['url'].'">';
                    echo $item['label'];
                    echo '</a>';
                    echo '</li>';
                }

            }
        }
    }


//    private function renderItems($items)
//    {
//        foreach ($items as $item) {
//            if (isset($item['items'])) {
//                // Render dropdown menu item if it has child items
//                echo '<li class="nav-item dropdown" style="color: white;">';
//                echo '<a style="color: white;" class="nav-link dropdown-toggle" href="#" id="navbarDropdown' . $item['label'] . '" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
//                echo $item['label'];
//                echo '</a>';
//                echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown' . $item['label'] . '">';
//                $this->renderItems($item['items']); // Recursive call to render child items
//                echo '</div>';
//                echo '</li>';
//            } else {
//                // Render regular menu item
//                echo '<li class="nav-item" style="color: white;">';
//                echo '<a style="color: white;" class="nav-link" href="' . $item['url'] . '">' . $item['label'] . '</a>';
//                echo '</li>';
//            }
//        }
//    }
}
