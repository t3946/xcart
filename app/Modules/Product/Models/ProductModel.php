<?php
namespace Modules\Product\Models;

use Mindy\QueryBuilder\Expression;
use Modules\Amazon\Models\AmazonFbaMissingSkuModel;
use Modules\Amazon\Models\AmazonProductsFieldsModel;
use Modules\Amp\Models\AmpProductModel;
use Modules\Brand\Models\BrandModel;
use Modules\Cart\Interfaces\ICartItem;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Sites\Models\SiteModel;
use Modules\Menu\Models\CleanUrlModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
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
 * @property null|\Xcart\App\Orm\Manager|\Modules\Product\Models\CategoryModel[] categories
 * @property null|\Xcart\App\Orm\Manager|ProductModel[] childs
 *
 * @method bool isForSale
 */
class ProductModel extends Model implements ICartItem
{
    const ADMIN_PRODUCT_MODIFY_URL = '/admin/product_modify.php?productid=%d&sf=%d';

    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass()
    {
        return Product::className();
    }

    public static function tableName()
    {
        return 'xcart_products';
    }

    public static function getFields()
    {
        return [
            'categories' => [
                'class' => ManyToManyField::className(),
                'modelClass' => CategoryModel::className(),
                'through' => ProductCategoriesModel::className(),
            ],

            'prices' => [
                'class' => HasManyField::className(),
                'modelClass' => PricingModel::className(),
                'link' => ['productid' => 'productid'],
            ],

            'sites' => [
                'class' => ManyToManyField::className(),
                'modelClass' => SiteModel::className(),
                'through' => ProductStorefrontModel::className(),
            ],

            'quick_prices' => [
                'class' => ManyToManyField::className(),
                'modelClass' => PricingModel::className(),
                'through' => QuickPricingModel::className(),
            ],

            'clean_url' => [
                'class' => HasToOneField::className(),
                'modelClass' => CleanUrlModel::className(),
                'link' => ['productid' => 'resource_id'],
                'extra' => ['resource_type' => 'P'],
            ],

            'order_details' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderDetailModel::className(),
                'link' => ['productid' => 'productid'],
            ],



            'productid' => [
                'class' => AutoField::className(),
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],
            'brand' => [
                'field' => 'brandid',
                'class' => ForeignField::className(),
                'modelClass' => BrandModel::className(),
                'link' => ['brandid' => 'brandid'],
                'null' => false,
            ],
            'filter_values' => [
                'class' => ManyToManyField::className(),
                'modelClass' => FilterValueModel::className(),
                'through' => FilterProductModel::className(),
            ],
            'images' => [
                'class' => HasManyField::className(),
                'modelClass' => ImageDModel::className(),
                'link' => ['productid' => 'id'],
//                'extra' => ['avail' => 'Y']
            ],

            'descr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'fulldescr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'seo_fulldescr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'seo_product_name' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'product' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'group_mask' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],
            'group_option' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],
            'source_sfid' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0,
            ],
            'clone_parent_productid' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0,
            ],
            'missing_products' => [
                'class' => HasManyField::className(),
                'modelClass' => AmazonFbaMissingSkuModel::className(),
                'link' => ['productid' => 'productid'],
            ],
            'add_date' => [
                'class' => UnixTimestampField::className(),
                'autoNowAdd' => true,
            ],
            'mod_date' => [
                'class' => UnixTimestampField::className(),
                'autoNow' => true,
                'autoNowAdd' => true,
            ],
            'category_main' => [
                'class' => HasManyField::className(),
                'modelClass' => ProductCategoriesModel::className(),
                'link' => ['productid' => 'productid'],
                'extra' => ['main' => 'Y']
            ],
            'childs' => [
                'class' => HasManyField::className(),
                'modelClass' => ProductModel::className(),
                'link' => ['productid' => 'group_root'],
                'extra' => ['productid__isnt' => new Expression('group_root')]
            ],
            'parent' => [
                'field' => 'group_root',
                'class' => ForeignField::className(),
                'modelClass' => ProductModel::className(),
                'link' => ['group_root' => 'productid'],
            ],
            'thumbnail' => [
                'class' => HasManyField::className(),
                'modelClass' => ImageTModel::className(),
                'link' => ['productid' => 'id']
            ],
            'pricing' => [
                'class' => HasManyField::className(),
                'modelClass' => PricingModel::className(),
                'link' => ['productid' => 'productid']
            ],
            'amazon_fields' => [
                'class' => HasManyField::className(),
                'modelClass' => AmazonProductsFieldsModel::className(),
                'link' => ['productid' => 'productid']
            ],
            'retail_trust_enabled' => [
                'class' => BooleanCharField::className(),
            ],
            'options' => [
                'class' => HasManyField::className(),
                'modelClass' => OptionModel::className(),
                'link' => ['productid' => 'productid']
            ],
        ];
    }

    public function getMPN()
    {
        $sMPN = null;
        $model = $this->distributor;
        if (strpos($this->productcode, $model->code) == 0) {
            $sMPN = preg_replace("/^(" . $model->code . "-)/i", "", $this->productcode);
        }
        return $sMPN;
    }

    public function isGroupRoot()
    {
        return ($this->productid == $this->group_root);
    }

    public function isGroupChild()
    {
        return (!is_null($this->group_root) && ($this->productid != $this->group_root));
    }

    public function isAmazonFBAEnabled()
    {
        return $this->amazon_fba == 'Y';
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

    public function checkSite($id) {
        return (bool)($this->sites->filter(['storefrontid' => $id])->count());
    }

    public function isOutOfStock()
    {
        return $this->isProductOutOfStock();
    }

    public function getAbsoluteUrl($full = false)
    {
        if ($this->productid) {
            $url = Xcart::app()->router->url('catalog:product:view', ['id' => $this->pk, 'slug' => $this->clean_url->getSlugPart()]);

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

    public function getMainCategory()
    {
        return CategoryModel::objects()->filter([
            'products__through__main' => 'Y',
            'products_link__productid' => $this->productid
        ])->limit(1)->get();
    }

    public function getBreadcrumbs()
    {
        /** @var CategoryModel $category */
        if ($category = $this->getMainCategory()) {
            $bread = $category->getBreadcrumbs();
        }
        else {
            $bread = new Breadcrumbs();
        }

        $bread->add($this->product, $this->getAbsoluteUrl());

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
        $name = $this->seo_product_name ?: $this->product;

        return ($this->isGroupChild()) ?  $this->group_mask . " ". $name : $name;
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

    /**
     * @TODO: Remove this method
     * @deprecated
     * @return string
     */
    public function getTitle() {
        return $this->getFrontendName();
    }

    public function getFrontendChilds()
    {
        return $this->childs->filter(['forsale' => 'Y'])->order(['group_order']);
    }

    /**
     * @return ImageTModel|null
     */
    public function getThumbnail()
    {
        return $this->thumbnail->limit(1)->get();
    }

    public function getAdminUrl()
    {
        return sprintf(self::ADMIN_PRODUCT_MODIFY_URL, $this->productid, $this->sites->limit(1)->get()->storefrontid);
    }
}