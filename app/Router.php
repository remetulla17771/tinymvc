<?php
namespace app;

class Router
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function toStudly(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        $name = ucwords($name);
        return str_replace(' ', '', $name);
    }

    public function resolve()
    {
        $segments = $this->request->getSegments();

        // язык
        $lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'ru';
        $_SESSION['lang'] = $lang;

        // стабильные дефолты (НЕ через new UrlManager, он парсит текущий URI)
        $defaultController = 'site';
        $defaultAction = 'index';

        $moduleId = $segments[0] ?? null;
        $isModule = false;

        if ($moduleId && preg_match('/^[A-Za-z0-9_]+$/', $moduleId)) {
            $modulesDir = __DIR__ . '/../modules/' . $moduleId;
            if (is_dir($modulesDir)) {
                $isModule = true;
            }
        }

        if ($isModule) {
            // /admin/site/index
            $controllerName = $segments[1] ?? $defaultController;
            $actionName     = $segments[2] ?? $defaultAction;

            $controllerClass =
                'modules\\' . $moduleId . '\\controllers\\' . $this->toStudly($controllerName) . 'Controller';
        } else {
            // /site/index
            $controllerName = $segments[0] ?? $defaultController;
            $actionName     = $segments[1] ?? $defaultAction;

            $controllerClass =
                'app\\controllers\\' . $this->toStudly($controllerName) . 'Controller';
        }

        $actionMethod = 'action' . $this->toStudly($actionName);

        if (!class_exists($controllerClass)) {
            throw new \Exception('Controller not found: ' . $controllerClass, 404);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionMethod)) {
            throw new \Exception('Action not found: ' . $actionMethod, 404);
        }

        // Reflection + параметры из $_GET
        $reflection = new \ReflectionMethod($controller, $actionMethod);
        $args = [];
        $missing = [];

        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();

            if (isset($_GET[$name])) {
                $args[] = $_GET[$name];
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new \Exception('Отсутствуют обязательные параметры: ' . implode(', ', $missing), 400);
        }

        $result = $reflection->invokeArgs($controller, $args);

        if ($result instanceof \app\Response) {
            $result->send();
        }

        return $result;
    }
}