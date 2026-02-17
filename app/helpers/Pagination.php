<?php

namespace app\helpers;

class Pagination
{
    public int $totalCount;
    public int $pageSize;
    public int $page; // 1-based
    public string $pageParam = 'page';

    public function __construct(int $totalCount, int $pageSize = 10, int $page = 1)
    {
        $this->totalCount = max(0, $totalCount);
        $this->pageSize = max(1, $pageSize);
        $this->page = max(1, $page);
    }

    public function getPageCount(): int
    {
        return max(1, (int)ceil($this->totalCount / $this->pageSize));
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->pageSize;
    }

    public function createUrl(int $page): string
    {
        $page = max(1, $page);

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        $q = $_GET;
        $q[$this->pageParam] = $page;

        $query = http_build_query($q);
        return $path . ($query ? ('?' . $query) : '');
    }
}
