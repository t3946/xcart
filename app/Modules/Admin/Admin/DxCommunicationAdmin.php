<?php


namespace Modules\Admin\Admin;


use Modules\Forms\Admin\EmailAdmin;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class DxCommunicationAdmin extends EmailAdmin
{
    public $section;
    public $ownerField = 'manufacturerid';
    public $ownerPk;

    public function all($pk = null)
    {
        $this->ownerPk = $pk;
        $this->setBreadcrumbs();
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $qs = $this->getQuerySet()->filter(["dx_models__{$this->ownerField}" => $this->ownerPk]);
        $qs = $this->handleSearch($qs, $search);
        $qs = $this->applyOrder($qs);
        $qs = $this->fixSort($qs);

        $pagination = new Pagination($qs, [
            'pageSize' => $this->getConfig()->page_size ?: $this->pageSize,
            'pageSizes' => $this->pageSizes
        ], new QuerySetDataSource());

        if (Xcart::app()->request->get->has($pagination->getPageSizeKey())) {
            $this->getConfig()->page_size = Xcart::app()->request->get->get($pagination->getPageSizeKey());
            $this->getConfig()->save();
        }

        $this->renderInternal($this->allTemplate, [
            'objects' => $pagination->paginate(),
            'pagination' => $pagination,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
            'canSort' => $this->getCanSort($qs),
        ]);
    }

}