<?php

namespace Modules\Product\Stores;


use Mindy\QueryBuilder\Aggregation\Max;
use Mindy\QueryBuilder\Expression;
use Modules\Brand\Models\BrandModel;
use Modules\Product\Models\ProductModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\App\Store\BaseStore;

class GroupStore extends BaseStore
{
    public $defaultPagerPageSize = 50;
    public $data = null;
    private $pager = null;
    private $model = null;
    private $qs = null;

    public function __construct($data = null, BrandModel $model = null)
    {
        if ($model) {
            $this->model = $model;
        }

        $this->populate($data);
    }

    public function populate(array $data)
    {
        $this->data = $data;

        if (!isset($data['level'])) {
            $this->data['level'] = 1;
        }
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = ProductModel::objects()->getQuerySet();

            $qs = ProductModel::objects()->getQuerySet()
                ->select(['max_code' => (new Expression("COALESCE(MAX(CAST(SUBSTRING_INDEX(productcode, '-', -1) AS UNSIGNED))+1, 1)"))->toSQL()])
                ->filter([new Expression("productcode LIKE CONCAT(prefix , '-GROUP%')")]);

            $this->qs->select(
                [
                    '*',
                    'prefix' => $this->qs->getTableAlias() . '__distributor__code',
                    'g_max' => new Expression($qs->getSql())
                ]);

            $this->qs->join('inner join', 'xcart_products_sf', ['productid' => 'sf.productid'], 'sf');

            $filter = [
                'forsale' => 'Y',
                'sf.sfid' => 0
            ];

            if ($this->model) {
                $filter['brandid'] = $this->model->brandid;
            }

            if ($this->data['level'] > 1) {
                $lmo = $this->data['level'] - 1;
                $filter[(new Expression("SUBSTRING_INDEX({$this->qs->getTableAlias()}.product, ' ', {$lmo})"))->toSQL()] = $this->data['group_phrase'];
            }

            $this->qs->filter($filter);
        }
        return $this->qs;
    }

    public function getBrandQuerySet()
    {
        $qs = BrandModel::objects()->getQuerySet();

        $phrase = new Expression("SUBSTRING_INDEX (p.product,' ', {$this->data['level']})");

        $qs->select(['*', 'count' => new Expression('count(p.productid)'), 'group_phrase' => $phrase]);

        $filter = [
            'p.forsale' => 'Y',
            'sf.sfid' => 0,
            'p.group_root__isnull' => true
        ];

        if ($this->model) {
            $filter['brandid'] = $this->model->brandid;
        }

        if ($this->data['level'] > 1) {
            $lmo = $this->data['level'] - 1;
            $filter[(new Expression("SUBSTRING_INDEX(p.product, ' ', {$lmo})"))->toSQL()] = $this->data['group_phrase'];
        }

        $qs->filter($filter);

        $qs->join('inner join', 'xcart_products', ['brandid' => 'p.brandid'], 'p');
        $qs->join('inner join', 'xcart_products_sf', ['p.productid' => 'sf.productid'], 'sf');

        $qs->group(['brandid', $phrase->toSql()]);
        $qs->having(['count__gt' => 1]);
        $qs->order(['-count']);


        return $qs;
    }

    public function getModels()
    {
        return $this->prepareModels($this->getPager()->paginate());
    }

    public function getLevels()
    {
        return $this->prepareModels($this->getBrandQuerySet()->all());
    }

    public function getBrands()
    {
        return $this->prepareModels($this->getBrandPager()->paginate());
    }

    public function prepareModels($models)
    {
        if (!$models) {
            return [];
        }
        return $models;
    }

    public function getPager()
    {
        if (!$this->pager) {
            $this->pager = new Pagination($this->getQuerySet(), ['pageSize' => $this->defaultPagerPageSize], new QuerySetDataSource());
        }

        return $this->pager;
    }

    public function getBrandPager()
    {
        $pager = new Pagination($this->getBrandQuerySet(), ['pageSize' => $this->defaultPagerPageSize], new QuerySetDataSource());

        return $pager;
    }

    public function groupParams()
    {
        $params = [
            'productcode' => trim($this->data['sku']),
            'product' => trim($this->data['title']),
            'fulldescr' => $this->data['description'],
            'original_provider' => Xcart::app()->user->login,
            'forsale' => 'Y',
            'brandid' => $this->data['brandid'],
            'manufacturerid' => $this->data['manufactuerid'],
            'group_option' => $this->data['group_option']
        ];

        if (isset($this->data['truncate_checkbox'])) {
            $params['group_mask'] = trim($this->data['truncate_mask']);
        }

        return $params;
    }
}