<?php

namespace app\helpers;

class LinkPager
{
    public static function widget(array $config): string
    {
        $p = $config['pagination'] ?? null;
        if (!$p instanceof Pagination) {
            throw new \InvalidArgumentException('LinkPager: pagination required');
        }

        $pageCount = $p->getPageCount();
        $page = min($p->page, $pageCount);
        if ($pageCount <= 1) return '';

        $maxButtons = max(5, (int)($config['maxButtons'] ?? 7));
        $half = (int)floor($maxButtons / 2);

        $start = max(1, $page - $half);
        $end = min($pageCount, $start + $maxButtons - 1);
        $start = max(1, $end - $maxButtons + 1);

        $html = '<nav><ul class="pagination">';
        $html .= self::li($page > 1, $p->createUrl($page - 1), '&laquo;');

        if ($start > 1) {
            $html .= self::li(true, $p->createUrl(1), '1', $page === 1);
            if ($start > 2) $html .= self::dots();
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= self::li(true, $p->createUrl($i), (string)$i, $i === $page);
        }

        if ($end < $pageCount) {
            if ($end < $pageCount - 1) $html .= self::dots();
            $html .= self::li(true, $p->createUrl($pageCount), (string)$pageCount, $page === $pageCount);
        }

        $html .= self::li($page < $pageCount, $p->createUrl($page + 1), '&raquo;');
        $html .= '</ul></nav>';

        return $html;
    }

    private static function li(bool $enabled, string $url, string $label, bool $active = false): string
    {
        $cls = 'page-item';
        if (!$enabled) $cls .= ' disabled';
        if ($active) $cls .= ' active';

        $href = $enabled ? $url : '#';
        return '<li class="' . $cls . '"><a class="page-link" href="' .
            htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . $label . '</a></li>';
    }

    private static function dots(): string
    {
        return '<li class="page-item disabled"><span class="page-link">…</span></li>';
    }
}
