<?php

namespace Modules\Product\Stores;


use Mindy\QueryBuilder\Aggregation\Max;
use Mindy\QueryBuilder\Expression;
use Modules\Brand\Models\BrandModel;
use Modules\Product\Models\ProductCategoriesModel;
use Modules\Product\Models\ProductModel;
use Modules\Product\Models\ProductStorefrontModel;
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

    public function __construct($data = [], $model = null)
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
                'sf.sfid' => $this->data['sfid'],
                'group_root__isnull' => true
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

    public function getGroupNewPager()
    {
        $qs = ProductModel::objects()->getQuerySet();

        $qs->filter(
            [
                (new Expression("SUBSTRING_INDEX({$qs->getTableAlias()}.product, ' ', 1)"))->toSQL() => (new Expression($this->model->group_option))->toSQL(),
                'group_root__isnull' => true,
                'brandid' => $this->model->brandid
            ]
        );

        $pager = new Pagination($qs, ['pageSize' => $this->defaultPagerPageSize], new QuerySetDataSource());

        return $pager;
    }

    public function getGroupPager()
    {
        $qs = ProductModel::objects()->getQuerySet();

        $qs->select(['*', 'count' => new Expression('count(p2.productid)'), 'group_phrase' => 'group_option']);
        $qs->join('inner join', 'xcart_products',
            [
                'group_option' => new Expression("SUBSTRING_INDEX(p2.product, ' ', 1)"),
                'brandid' => 'p2.brandid',
            ], 'p2');
        $qs->filter(
            [
                'productid' => new Expression($qs->getTableAlias().".group_root"),
                'group_root__isnull' => false,
                'p2.group_root__isnull' => true
            ]
        );
        $qs->group(['productid']);
        $qs->order(['-count']);

        $pager = new Pagination($qs, ['pageSize' => $this->defaultPagerPageSize], new QuerySetDataSource());

        return $pager;
    }

    public function getBrandQuerySet()
    {
        $qs = BrandModel::objects()->getQuerySet();

        $phrase = new Expression("SUBSTRING_INDEX (p.product,' ', {$this->data['level']})");

        $qs->select(['*', 'count' => new Expression('count(p.productid)'), 'group_phrase' => $phrase]);

        $filter = [
            'p.forsale' => 'Y',
            'sf.sfid' => $this->data['sfid'],
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

    public function getGroupProducts()
    {
        return $this->prepareModels($this->getGroupPager()->paginate());
    }

    public function getGroupNewProducts()
    {
        return $this->prepareModels($this->getGroupNewPager()->paginate());
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
            'manufacturerid' => $this->data['manufacturerid'],
            'group_option' => $this->data['group_option']
        ];

        if (isset($this->data['truncate_checkbox'])) {
            $params['group_mask'] = trim($this->data['truncate_mask']);
        }

        return $params;
    }

    public function createGroupProduct()
    {
        $params = $this->groupParams();

        if (!$this->model) {
            $this->model = new ProductModel;
        }

        $this->model->setAttributes($params);

        $this->model->save();

        if ($this->model->getIsNewRecord()) {
            (new ProductStorefrontModel(
                [
                    'productid' => $this->model->productid,
                    'sfid' => $this->data['sfid']
                ])
            )->save();

            (new ProductCategoriesModel(
                [
                    'categoryid' => $this->data['category'],
                    'productid' => $this->model->productid,
                    'main' => 'Y'
                ]
            ))->save();
        }

        $this->model->group_root = $this->model->productid;
        $this->model->save();

        if ($_POST['group']['products']) {
            /** @var ProductModel[] $products */
            if ($products = ProductModel::objects()->filter(['productid__in' => array_keys($this->data['products'])])) {
                foreach ($products as $product) {
                    $product->group_root = $this->model->productid;
                    if (isset($this->data['truncate_checkbox'])) {
                        $product->product = trim(preg_replace("/^({$params['group_mask']})/", '', $product->product));
                    }

                    /** @var ProductCategoriesModel $p_cat */
                    if ($p_cat = $product->category_main->all()) {
                        foreach ($p_cat as $cat) {
                            ProductCategoriesModel::objects()->delete([
                                'categoryid' => $cat->categoryid,
                                'productid' => $cat->productid,
                                'main' => 'Y'
                            ]);

                            list($new_cat) = ProductCategoriesModel::objects()->getOrNew(
                                [
                                    'categoryid' =>  $this->data['category'],
                                    'productid' => $cat->productid,
                                    'main' => 'Y'
                                ]);
                            $new_cat->save();

                        }
                    }

                    $product->save();
                }
            }
        }
    }

    public function updateGroupProduct()
    {
        $this->createGroupProduct();
    }
}