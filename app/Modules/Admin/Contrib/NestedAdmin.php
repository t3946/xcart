<?php

namespace Modules\Admin\Contrib;


use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Admin\Models\AdminConfig;
use Xcart\App\Exceptions\HttpException;
use Xcart\App\Form\ModelForm;
use Xcart\App\Helpers\ClassNames;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Helpers\Text;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Orm\TreeManager;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\App\Template\Renderer;
use Xcart\App\Traits\SmartyRenderTrait;

abstract class NestedAdmin extends Admin
{

    public $nestedColumn = '(string)';
    public $sortingColumn = ['root', 'lft'];
    public $nestedExcluded = ['root', 'lft', 'rgt', 'level'];

    public $columnActiveTemplate = 'admin/list/columns/link.tpl';

    public function getQuerySet($pk = null)
    {
        $model = $this->getModel();
        $connection = $model->getConnection();

        if ($pk) {
            $qs = (new TreeManager(
                $this->getModel()->getObjects()->get(['pk' => $pk]),
                $connection
            ))->children();
        }
        else {
            $qs = (new TreeManager($model, $connection))->roots();
        }

        return $qs;
    }

    public function getExcludedColumns()
    {
        return $this->nestedExcluded;
    }

    public function getCommonData()
    {
        return array_merge(parent::getCommonData(), [
            'isNested' => true,
        ]);
    }

    public function buildListColumns()
    {
        $enabled = [];
        $config = [];

        extract(parent::buildListColumns(), EXTR_OVERWRITE);

        if (isset($config[$this->nestedColumn])) {
            $config[$this->nestedColumn]['template'] = $this->columnActiveTemplate;
        }

        return [
            'enabled' => $enabled,
            'config' => $config
        ];
    }

    public function all($id = null)
    {
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $qs = $this->getQuerySet($id);
        $qs = $this->handleSearch($qs, $search);
        $qs = $this->applyOrder($qs);
        $qs = $this->fixSort($qs);

        $pagination = new Pagination($qs, [
            'defaultPageSize' => $this->pageSize,
            'pageSizes' => $this->pageSizes
        ], new QuerySetDataSource());

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