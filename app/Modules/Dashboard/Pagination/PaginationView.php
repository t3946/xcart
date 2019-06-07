<?php
namespace Modules\Dashboard\Pagination;


class PaginationView extends \Xcart\App\Pagination\PaginationView
{
    public function urlPageSort($sort)
    {
        return $this->handler->getUrlForQueryParam($this->data['page_sort_key'], $sort);
    }

    public function getSort()
    {
        return $this->data['sort'];
    }
}