<?php

namespace app;

class Controller extends App
{
    public string $layout = 'main';

    protected function createUrl(array $route): string
    {
        $path = trim($route[0], '/');

        unset($route[0]);

        if (!empty($route)) {
            $path .= '?' . http_build_query($route);
        }

        return '/' . $path;
    }

    public function redirect($url, int $status = 302)
    {
        if (is_array($url)) {
            $url = $this->createUrl($url);
        }

        header('Location: ' . $url, true, $status);
        exit;
    }

    public function render(string $view, array $params = []): string
    {
        $content = $this->renderView($view, $params);

        return $this->renderLayout($content);
    }

    protected function renderView(string $view, array $params): string
    {
        $controller = strtolower(
            str_replace('Controller', '', (new \ReflectionClass($this))->getShortName())
        );


        $viewFile = __DIR__ . "/../views/{$controller}/{$view}.php";

        if (!file_exists($viewFile)) {
            throw new \Exception(
                'Не найден вид: ' . $viewFile,
                500
            );
        }

        extract($params, EXTR_SKIP);

        ob_start();
        require $viewFile;
        return ob_get_clean();
    }

    public function renderPartial(string $view, array $params = [])
    {
        return $this->renderView($view, $params);
    }

    protected function renderLayout(string $content): string
    {
        $layoutFile = __DIR__ . "/../views/layouts/$this->layout.php";

        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout not found: {$layoutFile}");
        }

        ob_start();
        require $layoutFile;
        return ob_get_clean();
    }


}
