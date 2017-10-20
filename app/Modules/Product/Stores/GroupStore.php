<?php

namespace Modules\Product\Stores;


use Mindy\QueryBuilder\Aggregation\Max;
use Mindy\QueryBuilder\Expression;
use Modules\Brand\Models\BrandModel;
use Modules\Product\Helpers\ProductHelper;
use Modules\Product\Models\PricingModel;
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
    public $level = null;
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
        $this->level = $this->data['level'];
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = ProductModel::objects()->getQuerySet();

            $qs = ProductModel::objects()->getQuerySet()
                ->select(['max_code' => (new Expression("COALESCE(MAX(CAST(SUBSTRING_INDEX(productcode, '-', -1) AS UNSIGNED))+1, 1)"))->toSQL()])
                ->filter([
                    'group_root__isnull' => false,
                    'group_root' => new Expression('productid')
                    ]);

            $this->qs->select(
                [
                    '*',
                    'prefix' => $this->qs->getTableAlias() . '__distributor__code',
                    'g_max' => new Expression($qs->getSql())
                ]);

            $filter = [
                'forsale' => 'Y',
                'sites__storefrontid' => $this->data['sfid'],
                'group_root__isnull' => true
            ];

            if ($this->model) {
                $filter['brandid'] = $this->model->brandid;
            }

            if ($this->level > 1) {
                $lmo = $this->level - 1;
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
                (new Expression("SUBSTRING_INDEX({$qs->getTableAlias()}.product, ' ', {$this->level})"))->toSQL() => (new Expression($this->model->group_option))->toSQL(),
                'group_root__isnull' => true,
                'sites__storefrontid' => $this->data['sfid'],
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
                'brandid' => 'p2.brandid',
            ], 'p2');
        $qs->filter(
            [
                'productid' => new Expression($qs->getTableAlias().".group_root"),
                'group_root__isnull' => false,
                'sites__storefrontid' => $this->data['sfid'],
                'p2.group_root__isnull' => true,
                'p2.product__raw' => "LIKE CONCAT({$qs->getTableAlias()}.group_option, '%')"
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

        $phrase = new Expression("SUBSTRING_INDEX (p.product,' ', {$this->level})");

        $qs->select(['*', 'count' => new Expression('count(p.productid)'), 'group_phrase' => $phrase]);

        $filter = [
            'p.forsale' => 'Y',
            'sf.sfid' => $this->data['sfid'],
            'p.group_root__isnull' => true
        ];

        if ($this->model) {
            $filter['brandid'] = $this->model->brandid;
        }

        if ($this->level > 1) {
            $lmo = $this->level - 1;
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

        $new_product = $this->model->getIsNewRecord();

        $this->model->save();

        if ($new_product) {
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

            (new PricingModel(
                [
                    'productid' => $this->model->productid,
                    'quantity' => 1,
                    'price' => 0
                ]
            ))->save();

            func_build_quick_prices($this->model->productid);

            $clean_url = func_clean_url_autogenerate('P', $this->model->productid, array('product' => $this->model->product, 'productcode' => $this->model->productcode));
            func_clean_url_add($clean_url, 'P', $this->model->productid);

        }

        $this->model->group_root = $this->model->productid;
        $this->model->pc_classify_status = 'ACC';
        $this->model->save();

        if ($this->data['products'] && is_array($this->data['products'])) {
            /** @var ProductModel[] $products */
            if ($products = ProductModel::objects()->filter(['productid__in' => array_keys($this->data['products'])])) {
                foreach ($products as $product) {

                    $product->group_root = $this->model->productid;

                    if (isset($this->data['truncate_checkbox'])) {
                        $mask = preg_quote($params['group_mask'], '/');
                        $product->product = trim(preg_replace("/^({$mask})/", '', $product->product));
                    }

                    /** @var ProductCategoriesModel $p_cat */
                    if ($p_cat = $product->category_main->all()) {
                        foreach ($p_cat as $cat) {
                            ProductCategoriesModel::objects()->delete([
                                'categoryid' => $cat->categoryid,
                                'productid' => $cat->productid,
                                'main' => 'Y'
                            ]);

                            if ($this->data['category'] && $cat->productid) {
                                list($new_cat) = ProductCategoriesModel::objects()->getOrNew(
                                    [
                                        'categoryid' => $this->data['category'],
                                        'productid' => $cat->productid,
                                        'main' => 'Y'
                                    ]);
                                $new_cat->save();
                            }
                        }
                    }

                    if ($this->data['group_image'] && in_array($product->productid, $this->data['group_image'])) {
                        $product->group_order = (array_search($product->productid, $this->data['group_image']) + 1) * 10;
                    }

                    if (isset($params['group_mask'])) {
                        $product->group_mask = $params['group_mask'];
                    }

                    $product->save();
                }
            }
        }

        if ($this->data['group_image'] && is_array($this->data['group_image'])) {
            foreach ($this->data['group_image'] as $key => $i_product) {
                if ($p = ProductModel::objects()->get(['productid' => $i_product])) {
                    $p->group_order = ($key + 1) * 10;
                    $p->save();
                }
            }
        }
        return $this->model;
    }

    public function updateGroupProduct()
    {
        $this->createGroupProduct();
    }
}