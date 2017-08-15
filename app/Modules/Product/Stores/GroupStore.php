<?php

namespace Modules\Product\Stores;


use Mindy\QueryBuilder\Expression;
use Modules\Brand\Models\BrandModel;
use Modules\Product\Models\ProductModel;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\App\Store\BaseStore;

class GroupStore extends BaseStore
{
    public $defaultPagerPageSize = 50;
    private $data = null;

    public function populate(array $data)
    {
        $this->data = $data;
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = ProductModel::objects()->getQuerySet();
        }
        return $this->qs;
    }

    public function getBrandQuerySet()
    {
        $qs = BrandModel::objects()->getQuerySet();

        $qs->select(['*', 'count' => new Expression('count(p.productid)')]);

        $filter['p.forsale'] = 'Y';
        $filter['sf.sfid'] = 0;
        $qs->filter($filter);

        $qs->join('inner join', 'xcart_products', ['brandid' => 'p.brandid'], 'p');
        $qs->join('inner join', 'xcart_products_sf', ['p.productid' => 'sf.productid'], 'sf');

        $qs->group(['brandid', (new Expression("SUBSTRING_INDEX (p.product,' ',1)"))->toSql()]);
        $qs->order(['-count']);


        return $qs;
    }

    public function getModels()
    {
        return $this->prepareModels($this->getPager()->paginate());
    }

    public function getBrands()
    {
        return $this->prepareModels(new Pagination($this->getBrandQuerySet(), ['pageSize' => $this->defaultPagerPageSize], new QuerySetDataSource()));
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
}