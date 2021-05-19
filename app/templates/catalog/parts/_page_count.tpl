{*TODO:remove this file*}

<span class="page_count">
    <span class="count">
        {$pager->getPageSize() * ($pager->getPage() - 1) + $pager->paginate()|count}
    </span>
    /
    <span class="full">
        {$pager->getTotal()}
    </span> {t 'items shown'}
</span>