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

    protected $instance = null;

    public $nestedColumn = '(string)';
    public $sortingColumn = ['root', 'lft'];
    public $nestedExcluded = ['root', 'lft', 'rgt', 'level'];

    public $columnActiveTemplate = 'admin/list/columns/link.tpl';

    public function getInstance()
    {
        $model = $this->getModel();

        if ($this->parent_pk && !$this->instance) {
            $this->instance  = $model->getObjects()->get(['pk' => $this->parent_pk]);
        }

        return $this->instance ?: $model;
    }

    public function getQuerySet()
    {
        $model = $this->getInstance();
        $manager = new TreeManager($model, $model->getConnection());

        return ($this->parent_pk) ? $manager->children() : $manager->roots();
    }

    public function getBreadcrumbs()
    {
        $bread = parent::getBreadcrumbs();
        $model = $this->getInstance();

        if (!$model->getIsNewRecord())
        {
            $manager = new TreeManager($model, $model->getConnection());
            $models = $manager->parents(true)->all();

            $module = static::getModuleName();
            $admin = static ::classNameShort();

            /** @var \Xcart\App\Orm\TreeModel $model */
            foreach ($models as $model) {
                $bread[] = [(string)$model, Xcart::app()->router->url('admin:list_nested', [
                    'module' => $module,
                    'admin' => $admin,
                    'id' => $model->pk
                ])];
            }
        }

        return $bread;
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
            'config' => $config,
        ];
    }

    public function all($pk = null)
    {
        $this->parent_pk = $pk;
        parent::all();
    }

    public function create($pk = null)
    {
        $this->parent_pk = $pk;
        $this->update(null, $pk);
    }

    public function getParentAllUrl()
    {
        if ($this->parent_pk) {
            return Xcart::app()->router->url('admin:list_nested', [
                'module' => static::getModuleName(),
                'admin' => static::classNameShort(),
                'id' => $this->parent_pk
            ]);
        }

        return $this->getAllUrl();
    }

    public function getCreateUrl()
    {
        if ($this->parent_pk) {
            return Xcart::app()->router->url('admin:create_nested', [
                'module' => static::getModuleName(),
                'admin' => static::classNameShort(),
                'id' => $this->parent_pk
            ]);
        }

        return parent::getCreateUrl();
    }
}