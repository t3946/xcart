<?php

namespace Modules\Dashboard\Pagination;



class Pagination extends \Xcart\App\Pagination\Pagination
{
    private $sorting_filter;
    /**
     * @var int
     */
    private $sort;

    public function __construct($source, array $config = [], $dataSource)
    {

        parent::__construct($source, $config, $dataSource);

        if (!empty($config['sorting_filter'])) {
            $this->sorting_filter = $config['sorting_filter'];
        }

        if (!empty($config['sort'])) {
            $this->sort = $config['sort'];
        }
    }

    public function render($view = null)
    {
        if (!$view) {
            $view = $this->view;
        }

        return $this->renderTemplate($view, [
            'this' => $this,
            'pager' => $this,
            'view' => $this->createView(),
            'sorting_filter' => $this->sorting_filter,
        ]);
    }

    public function createView()
    {
        return new PaginationView([
            'total' => $this->getTotal(),
            'page' => $this->getPage(),
            'sort' => $this->getSort(),
            'page_sizes' => $this->getPageSizes(),
            'page_size' => $this->getPageSize(),
            'page_count' => $this->getPagesCount(),
            'page_key' => $this->getPageKey(),
            'page_size_key' => $this->getPageSizeKey(),
            'page_sort_key' => $this->getPageSortKey()
        ], $this->handler);
    }

    public function getPageSortKey()
    {
        return empty($this->pageSortKey) ? 'PageSort' : $this->pageSortKey;
    }

    public function getSort()
    {
        return $this->sort;
    }


}
