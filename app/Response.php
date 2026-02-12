<?php

namespace app;

class Response
{
    protected int $statusCode = 200;
    protected array $headers = [];
    protected string $content = '';

    /* =====================
       FACTORIES
    ===================== */

    public static function html(string $content, int $status = 200): self
    {
        $res = new self();
        $res->statusCode = $status;
        $res->content = $content;
        $res->setHeader('Content-Type', 'text/html; charset=utf-8');
        return $res;
    }

    public static function json($data, int $status = 200): self
    {
        $res = new self();
        $res->statusCode = $status;

        if (is_array($data)) {
            $data = array_map(function ($item) {
                return $item instanceof \app\ActiveRecord
                    ? $item->toArray()
                    : $item;
            }, $data);
        } elseif ($data instanceof \app\ActiveRecord) {
            $data = $data->toArray();
        }

        $res->content = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res->setHeader('Content-Type', 'application/json');

        return $res;
    }


    public static function redirect($url, int $status = 302): self
    {
        $res = new self();
        $res->statusCode = $status;

        if (is_array($url)) {
            $url = self::buildUrl($url);
        }

        $res->setHeader('Location', $url);
        return $res;
    }

    public static function error(int $code, string $message = ''): self
    {
        return self::html($message, $code);
    }

    /* =====================
       CORE
    ===================== */

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
        exit;
    }

    /* =====================
       HELPERS
    ===================== */

    protected static function buildUrl(array $route): string
    {
        $path = trim($route[0], '/');
        unset($route[0]);

        if (!empty($route)) {
            $path .= '?' . http_build_query($route);
        }

        return '/' . $path;
    }
}
