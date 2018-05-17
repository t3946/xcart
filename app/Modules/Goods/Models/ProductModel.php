<?php
namespace Modules\Goods\Models;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Amazon\Models\AmazonFbaMissingSkuModel;
use Modules\Amazon\Models\AmazonProductsFieldsModel;
use Modules\Amp\Models\AmpProductModel;
use Modules\Brand\Models\BrandModel;
use Modules\Cart\Interfaces\ICartItem;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Sites\Models\SiteModel;
use Modules\Menu\Models\CleanUrlModel;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\App\Traits\SlugifyTrait;
use Xcart\Product;

/**
 * @property string forsale
 * @property string update_search_index
 * @property string productcode
 * @property mixed eta_date_mm_dd_yyyy
 * @property mixed eta_date_lock
 * @property mixed productid
 * @property null|DistributorModel distributor
 * @property mixed|null dim_x
 * @property mixed|null dim_y
 * @property mixed|null dim_z
 * @property mixed shipping_weight_lock
 * @property mixed|null shipping_weight
 * @property mixed|null weight
 * @property mixed weight_lock
 * @property mixed dim_lock
 * @property mixed shipping_dim_lock
 * @property mixed|null shipping_dim_x
 * @property mixed|null shipping_dim_y
 * @property mixed|null shipping_dim_z
 * @property string product
 * @property string fulldescr
 * @property string controlled_by_feed
 * @property mixed brandid
 * @property integer source_sfid
 * @property integer manufacturerid
 * @property int add_date
 * @property int mod_date
 * @property mixed|string upc
 * @property null|\Xcart\App\Orm\Manager|\Modules\Sites\Models\SiteModel sites
 * @property null|CleanUrlModel clean_url
 * @property null|\Xcart\App\Orm\Manager|\Modules\Goods\Models\CategoryModel[] categories
 * @property null|\Xcart\App\Orm\Manager|ProductModel[] childs
 * @property mixed cost_to_us
 * @property mixed amazon_fba
 *
 * @method bool isForSale
 * @method static Manager showed($instance = null)
 */
class ProductModel extends Model implements ICartItem
{
    const ADMIN_PRODUCT_MODIFY_URL = '/admin/product_modify.php?productid=%d&sf=%d';

    use DataModelTrait, AutoMetaTrait, SlugifyTrait;

    public static function getDataModelClass(): string
    {
        return Product::class;
    }

    public static function tableName()
    {
        return 'xcart_products';
    }

    public static function getFields()
    {
        return [
            'categories' => [
                'class' => ManyToManyField::class,
                'modelClass' => CategoryModel::class,
                'through' => ProductCategoriesModel::class,
            ],

            'product_categories' => [
                'class' => HasManyField::class,
                'modelClass' => ProductCategoriesModel::class,
                'link' => ['productid' => 'productid'],
            ],

            'prices' => [
                'class' => HasManyField::class,
                'modelClass' => PricingModel::class,
                'link' => ['productid' => 'productid'],
            ],

            'sites' => [
                'class' => ManyToManyField::class,
                'modelClass' => SiteModel::class,
                'through' => ProductStorefrontModel::class,
            ],

            'quick_prices' => [
                'class' => ManyToManyField::class,
                'modelClass' => PricingModel::class,
                'through' => QuickPricingModel::class,
            ],

            'clean_url' => [
                'class' => HasToOneField::class,
                'modelClass' => CleanUrlModel::class,
                'link' => ['productid' => 'resource_id'],
                'extra' => ['resource_type' => 'P'],
            ],

            'order_details' => [
                'class' => HasManyField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['productid' => 'productid'],
            ],

            'surf_path' => [
                'class' => HasManyField::class,
                'modelClass' => SurfPathModel::class,
                'link' => ['productid' => 'resource_id'],
                'extra' => ['resource_type' => 'P'],
            ],
            'sf_moves' => [
                'class' => HasManyField::class,
                'modelClass' => ProductsSfMovesModel::class,
                'link' => ['productid' => 'productid'],
            ],



            'productid' => [
                'class' => AutoField::class,
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],
            'brand' => [
                'field' => 'brandid',
                'class' => ForeignField::class,
                'modelClass' => BrandModel::class,
                'link' => ['brandid' => 'brandid'],
                'null' => true,
            ],
            'filter_values' => [
                'class' => ManyToManyField::class,
                'modelClass' => FilterValueModel::class,
                'through' => FilterProductModel::class,
            ],
            'images' => [
                'class' => HasManyField::class,
                'modelClass' => ImageDModel::class,
                'link' => ['productid' => 'id'],
//                'extra' => ['avail' => 'Y']
            ],
            'videos' => [
                'class' => HasManyField::class,
                'modelClass' => ProductVideosModel::class,
                'link' => ['productid' => 'product_id'],
            ],

            'descr' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'fulldescr' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'seo_fulldescr' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'seo_product_name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'product' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'group_mask' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'group_option' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'source_sfid' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'clone_parent_productid' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'missing_products' => [
                'class' => HasManyField::class,
                'modelClass' => AmazonFbaMissingSkuModel::class,
                'link' => ['productid' => 'productid'],
            ],
            'add_date' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],
            'mod_date' => [
                'class' => UnixTimestampField::class,
                'autoNow' => true,
                'autoNowAdd' => true,
            ],
            'category_main' => [
                'class' => HasManyField::class,
                'modelClass' => ProductCategoriesModel::class,
                'link' => ['productid' => 'productid'],
                'extra' => ['main' => 'Y']
            ],
            'childs' => [
                'class' => HasManyField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'group_root'],
                'extra' => ['productid__isnt' => new Expression('group_root')]
            ],
            'parent' => [
                'field' => 'group_root',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['group_root' => 'productid'],
                'null' => true,
            ],
            'thumbnail' => [
                'class' => HasManyField::class,
                'modelClass' => ImageTModel::class,
                'link' => ['productid' => 'id']
            ],
            'pricing' => [
                'class' => HasManyField::class,
                'modelClass' => PricingModel::class,
                'link' => ['productid' => 'productid']
            ],
            'amazon_fields' => [
                'class' => HasManyField::class,
                'modelClass' => AmazonProductsFieldsModel::class,
                'link' => ['productid' => 'productid']
            ],
            'retail_trust_enabled' => [
                'class' => BooleanCharField::class,
            ],
            'options' => [
                'class' => HasManyField::class,
                'modelClass' => OptionModel::class,
                'link' => ['productid' => 'productid']
            ],
            'cost_to_us' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
            ],
            'weight' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
            ],
            'list_price' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
            ],
            'map_price' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
            ],
            'new_map_price' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
            ],
            'shipping_weight' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
            ],
            'brand_normalized' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'r_avail' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
        ];
    }

    public function getMPN()
    {
        $model = $this->distributor;

        if (strpos($this->productcode, $model->code) == 0) {
            return substr($this->productcode, \strlen($model->code)+1 );
        }

        return null;
    }

    public function isGroupRoot()
    {
        return ($this->productid == $this->group_root);
    }

    public function isGroupChild()
    {
        return (null !== $this->group_root && ($this->productid != $this->group_root));
    }

    public function isAmazonFBAEnabled()
    {
        return $this->amazon_fba === 'Y';
    }

    public function getParamList()
    {
        if ($values = $this->filter_values->filter(['fv_active' => 'Y'])->order(['f_id','fv_order_by'])->cache(30)->all()) {

            $filters = FilterModel::objects()->filter(['f_id__in' => array_map(function($value){ return $value->f_id; }, $values)])->order(['f_order_by'])->cache(30)->all();

            $list = [];
            foreach ($filters as $filter)
            {
                $list[$filter->f_id] = ['name' =>$filter->f_name, 'values' => []];
            }
            
            foreach ($values as $value)
            {
                if ($list[$value->f_id]) {
                    $list[$value->f_id]['values'][] = $value->fv_name;
                }
            }

            return $list;
        }

        return false;
    }

    public function isNewProduct()
    {
        $sInDay = (60 * 60 * 24);

        return ($this->add_date + $sInDay * 30)  >= time();
    }

    /**
     * @return ImagePModel[]
     */
    public function getImages()
    {
        /** @var ImagePModel[] $images */
        $images = $this->images->filter(['avail' => 'Y'])->order(['orderby'])->all();
        return $images ?: [];
    }

    public function isSaleSticker()
    {
        if ($this->isOutOfStock()) {
            return false;
        }

        $fp = $this->getFrontendPrice();

        return ($this->list_price > ($fp + $fp * .3));
    }

    public function checkSite($id):bool
    {
        return (bool)($this->sites->filter(['storefrontid' => $id])->count());
    }

    public function isOutOfStock()
    {
        return $this->isProductOutOfStock();
    }

    public function getAbsoluteUrl($full = false)
    {
        if ($this->productid) {
            $url = Xcart::app()->router->url('catalog:product:view', ['id' => $this->pk, 'slug' => $this->clean_url ? $this->clean_url->getSlugPart(): '']);
//            $url = Xcart::app()->router->url('catalog:product:view', ['id' => $this->pk, 'slug' => $this->createSlug($this->product)]);

            if ($full) {
                $site = $this->sites->limit(1)->get();

                $url = '//' . $site->domain . $url;
            }

            return $url;
        }

        return false;
    }

    public function getAmpAbsoluteUrl($full = false)
    {
        $model = new AmpProductModel($this->getAttributes());
        return $model->getAbsoluteUrl($full, true);
    }

    public function getMainCategory(int $site_id = null):?CategoryModel
    {
        $params  = [
            'products__through__main' => 'Y',
            'products__through__productid' => $this->productid,
        ];

        if ($site_id) {
            $params['storefrontid']  = $site_id;
        }

        return CategoryModel::objects()
            ->limit(1)->get($params);
    }

    public function setMainCategory(CategoryModel $model, int $site_id = null):void
    {
        $params = [
            'productid' => $this->productid,
            'main' => 'Y',
            'categoryid__isnt' => $model->categoryid
        ];

        if ($site_id) {
            $params['category__storefrontid'] = $site_id;
        }

        ProductCategoriesModel::objects()->delete($params);

        ProductCategoriesModel::objects()->getOrCreate(
            [
                'categoryid' => $model->categoryid,
                'productid' => $this->productid,
                'main' => 'Y',
            ]
        );
    }

    public function getBreadcrumbs():Breadcrumbs
    {
        /** @var CategoryModel $category */
        if ($category = $this->getMainCategory()) {
            $bread = $category->getBreadcrumbs();
        }
        else {
            $bread = new Breadcrumbs();
        }

        if ($this->isGroupChild()) {
            /** @var static $parent */
            $parent = $this->parent;
            $bread->add($parent->getFrontendName(), $parent->getAbsoluteUrl());
        }

        $bread->add($this->getFrontendName(), $this->getAbsoluteUrl());

        return $bread;
    }

    public function getPrice($quantity = 1)
    {
        return $this->getDataModel()->getPrice($quantity);
    }

    public function recalculate($quantity, $type, $data)
    {
        return $quantity * $this->getPrice($quantity);
    }

    public function getUniqueId($data = [])
    {
        return $this->productid;
    }

    public function __toString()
    {
//        return "[{$this->productid}] {$this->product} ({$this->productcode})";
        return $this->getFrontendName();
    }

    public function getFrontendName()
    {
        $brand_name = '';

        $name = $this->seo_product_name ?: $this->product;

        if ($brand = $this->brand) {
            $brand_name = ($this->brand_normalized && !$this->isGroupRoot()) ? $brand->getProductFrontendName() . ' ' : '';
        }

        return ($this->isGroupChild()) ?  $this->group_mask . " ". $name : $brand_name . $name;
    }

    public function getFrontendDescription()
    {
        return $this->descr ?: $this->seo_fulldescr ?: $this->fulldescr;
    }

    public function getPrices()
    {
        $t = [];
        /** @var \Xcart\Pricing $price */
        foreach ($this->getPricing() as $price) {
            $t[$price->getQuantity()] = $this->getFrontendPrice($price->getQuantity());
        }

        return $t;
    }

    public function getActualQuantity($quantity)
    {
        $tq = $quantity;
        $min = $this->min_amount;

        if ($this->mult_order_quantity == 'Y' && $min > 1) {
            $tq = ceil($tq / $min) * $min;
        }
        else if ($tq < $min) {
            $tq = $min;
        }

        if ($tq > $this->avail) {
            $tq = $this->avail;
        }

        return $tq;
    }

    public function getFrontendChilds()
    {
        return $this->childs->filter(['forsale' => 'Y'])->order(['group_order', 'product']);
    }

    /**
     * @return ImageTModel|null
     */
    public function getThumbnail():? ImageTModel
    {
        return $this->thumbnail->limit(1)->get();
    }

    public function getAdminUrl()
    {
        return sprintf(self::ADMIN_PRODUCT_MODIFY_URL, $this->productid, $this->sites->limit(1)->get()->storefrontid);
    }

    public function isCategorized()
    {
        return ($this->pc_classify_status === 'ACC' || $this->pc_classify_status === 'MC');
    }

    public static function showedManager($instance = null) : Manager
    {
        return static::objects($instance)->filter([
            'forsale' => 'Y',
            'sites__storefrontid' => Xcart::app()->getModule('Sites')->getSite(),
            new QOr([
                ['group_root__isnull' => true],
                ['group_root__productid' => 'productid']
            ])
        ]);
    }
}