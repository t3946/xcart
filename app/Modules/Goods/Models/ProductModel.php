<?php
namespace Modules\Goods\Models;

use DateInterval;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Modules\Goods\Helpers\ProductHelper;
use Modules\User\Models\UserModel;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;
use Modules\Amazon\Models\AmazonFbaMissingSkuModel;
use Modules\Amazon\Models\AmazonOfferCompetitorsModel;
use Modules\Amazon\Models\AmazonOfferModel;
use Modules\Amazon\Models\AmazonProductsFieldsModel;
use Modules\Amp\Models\AmpProductModel;
use Modules\Brand\Models\BrandModel;
use Modules\Cart\Interfaces\ICartItem;
use Modules\Distributor\Models\DistributorModel;
use Modules\Main\Helpers\CurrencyHelper;
use Modules\Marketplace\Models\ExternalMarketplaceDisabledModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
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
 * @property null|\Xcart\App\Orm\Manager|\Modules\Goods\Models\CategoryModel[] categories
 * @property null|\Xcart\App\Orm\Manager|ProductModel[] childs
 * @property mixed cost_to_us
 * @property mixed amazon_fba
 * @property string provider
 * @property string original_provider
 * @property mixed ASIN
 * @property mixed mult_order_quantity
 * @property mixed min_amount
 * @property mixed brand
 * @property mixed r_avail
 * @property mixed list_price
 * @property mixed verification_statusid
 * @property VerificationStatusModel verification_status
 * @property int last_verify_date
 * @property string hash_product
 * @property ProductModel parent
 * @property string group_mask
 * @property int group_root
 * @property ProductImageModel[]|\Xcart\App\Orm\Manager detail_images
 * @property UserModel last_modify_user
 *
 * @method bool isForSale
 * @method static Manager showed($instance = null)
 * @method static Manager forsale($instance = null)
 * @method static Manager without_group($instance = null)
 */
class ProductModel extends Model implements ICartItem
{
    private $front_name;

    public const ADMIN_PRODUCT_MODIFY_URL = '/admin/product_modify.php?productid=%d&sf=%d';

    public const NO_ASIN_FOUND = 'No ASIN found';

    private $priceArray;

    public const PRODUCT_STATUS_NOT_VERIFY = 0;
    public const PRODUCT_STATUS_PROBLEM_NOT_FIXED = 1;
    public const PRODUCT_STATUS_PROBLEM_FIXED = 2;
    public const PRODUCT_STATUS_VERIFY = 3;

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
            'forsale' => [
                'class' => CharField::class,
            ],

            'amazon_offer_model' => [
                'class' => HasToOneField::class,
                'modelClass' => AmazonOfferModel::class,
                'link' => ['ASIN' => 'ASIN'],
                'sqlType' => Types::STRING,
            ],
			'dim_x' => [
				'class' => CharField::class,
				'verboseName' => 'Product dimension x'
			],
			'dim_y' => [
				'class' => CharField::class,
				'verboseName' => 'Product dimension y'
			],
			'dim_z' => [
				'class' => CharField::class,
				'verboseName' => 'Product dimension z'
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
                'verboseName' => 'Product attributes'
            ],
            'detail_images' => [
                'class' => ManyToManyField::class,
                'modelClass' => ProductImageModel::class,
                'through' => ProductImagesModel::class,
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
            'files' => [
                'class' => HasManyField::class,
                'modelClass' => ProductFileModel::class,
                'link' => ['productid' => 'productid'],
            ],
            'hash_product' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
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
            'ASIN' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'product' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'productcode' => [
                'class' => CharField::class,
                'null' => false,
                'unique' => true,
                'verboseName' => 'SKU'
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
            'discount_table' => [
                'class' => CharField::class,
                'default' => '2,3,4,6,8,12',
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
            'last_verify_date' => [
                'class' => UnixTimestampField::class,
                'verboseName' => 'Last verif date'
            ],
            'eta_date_mm_dd_yyyy' => [
                'class' => UnixTimestampField::class,
				'verboseName' => 'ETA date (mm/dd/yyyy)',
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
            'preview' => [
                'class' => HasManyField::class,
                'modelClass' => ImagePModel::class,
                'link' => ['productid' => 'id']
            ],
            'pricing' => [
                'class' => HasManyField::class,
                'modelClass' => PricingModel::class,
                'link' => ['productid' => 'productid']
            ],
            'featured' => [
                'class' => HasManyField::class,
                'modelClass' => FeaturedProductsModel::class,
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
                'modelClass' => ProductOptionModel::class,
                'link' => ['productid' => 'product_id']
            ],
            'product_options' => [
                'class' => HasManyField::class,
                'modelClass' => ProductOptionModel::class,
                'link' => ['productid' => 'product_id'],
				'verboseName' => 'Product options',
            ],
            'cost_to_us' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
            ],
            'shipping_freight' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0.01,
				'verboseName' => 'Shipping freight (US$)',
            ],
			'free_ship_zone' => [
				'class' => IntField::class,
				'verboseName' => 'Free shipping for destination',
			],
			'free_ship_text' => [
				'class' => CharField::class,
				'verboseName' => 'Free shipping text',
                'null' => false,
                'default' => ''
			],
            'weight' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0,
				'verboseName' => 'Product weight (lbs)',
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
				'verboseName' => 'Shipping weight (lbs)'
            ],
			'shipping_dim_x' => [
				'class' => CharField::class,
				'verboseName' => 'Shipping dimension x'
			],
			'shipping_dim_y' => [
				'class' => CharField::class,
				'verboseName' => 'Shipping dimension y'
			],
			'shipping_dim_z' => [
				'class' => CharField::class,
				'verboseName' => 'Shipping dimension z'
			],
            'brand_normalized' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'shipping_calc_disabled' => [
                'class' => BooleanField::class,
                'null' => false,
            ],
            'r_avail' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'weight_lock' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
                'verboseName' => 'Locked by Product Manager'
            ],
            'shipping_weight_lock' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
            ],
            'dim_lock' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
            ],
            'shipping_dim_lock' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
            ],
            'eta_date_lock' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
            ],
            'prevent_search_indexing_this_product_page' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
            ],
            'mult_order_quantity' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
            ],
            'verification_status' => [
                'field' => 'verification_statusid',
                'class' => ForeignField::class,
                'modelClass' => VerificationStatusModel::class,
                'link' => ['verification_statusid' => 'statusid'],
                'verboseName' => 'Verified',
                'default' => 0
            ],
            'markets_disabled' => [
                'class' => HasManyField::class,
                'modelClass' => ExternalMarketplaceDisabledModel::class,
                'link' => ['productid' => 'resource_id'],
                'extra' => ['resource_type' => 'P']
            ],
            'last_modify_user' => [
                'field' => 'last_modify_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['last_modify_id' => 'id'],
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

    public function isNewProduct(): bool
    {
        $sInDay = (60 * 60 * 24);

        return ($this->add_date + $sInDay * 30)  >= time();
    }

    /**
     * @return ProductImageModel[] array
     */
    public function getImages() : array
    {
        return $this->detail_images
            ->filter(['xcart_products_images_1.is_active' => true])
            ->order(['xcart_products_images_1.order_by', 'xcart_products_images_1.image_id'])
            ->all();
    }

    public function getMainImage():? ProductImageModel
    {
        return $this->getImages()[0];
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
        if ($this->eta_date_mm_dd_yyyy && time() < $this->eta_date_mm_dd_yyyy) {
            return true;
        }
        return $this->isOutOfStockFrontend();
    }

    public function isOutOfStockFrontend()
    {
        if ($this->group_root == $this->productid) {
            return false;
        }

        if (!$this->isForSale()) {
            return true;
        }

        if ($this->cost_to_us <= 0) {
            return true;
        }

        if ($this->avail <= 0) {
            return true;
        }

        if ($this->avail < $this->min_amount) {
            return true;
        }

        if (($this->list_price > 0) && ($this->getPrice() / $this->list_price < 0.1)) {
            return true;
        }

        if ($this->cost_to_us > $this->getPrice()) {
            return true;
        }

        if ((float)$this->shipping_freight === (float) 0 && strpos($this->productcode, 'ART-') === false) {
            return true;
        }

        return false;
    }

    public function getAbsoluteUrl($full = false)
    {
        $url = '';
        if ($this->productid) {
            $url = Xcart::app()->router->url(
                'catalog:product:view',
                [
                    'id' => $this->pk,
                    'slug' => $this->getSlugPart() ?: $this->pk
                ]
            );

            if ($full) {
                $url = '//' . $this->getDomain() . $url;
            }
        }
        return $url;
    }

    public function getBaseDomain()
    {
        if ($site = $this->sites->limit(1)->get()) {
            $res = $site->getBaseDomain();
        }
        return $res ?? '';
    }

    public function getDomain()
    {
        if ($site = $this->sites->limit(1)->get()) {
            $res = $site->domain;
        }
        return $res ?? '';
    }

    public function getDistributorUrl()
    {
        return str_replace(['{{mpn}}', '{{supplier_internal_id}}'], [$this->getMPN(), $this->supplier_internal_id],
            $this->distributor->d_website_search_for_sku_url ?: '{{supplier_internal_id}}'
        );
    }

    public function getAmpAbsoluteUrl($full = false)
    {
        $model = new AmpProductModel($this->getAttributes());
        return $model->getAbsoluteUrl($full, true);
    }

    public function getMainCategory(int $site_id = null):?CategoryModel
    {
        if (!$this->pk) {
            return null;
        }
        $params  = [
            'products__through__main' => 'Y',
            'products__through__productid' => $this->pk,
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

        if ($this->isGroupChild() && $parent = $this->parent) {
            $bread->add($parent->getFrontendName(), 'https:'.$parent->getAbsoluteUrl(true));
        }

        $bread->add($this->getFrontendName(), 'https:'.$this->getAbsoluteUrl(true));

        return $bread;
    }

    public function getPrice($quantity = 1)
    {
        $fPrice = 0;
        $prices = $this->getPrices();
        if ($prices) {
            foreach ($prices as $key => $price) {
                if ($quantity >= (float) $key) {
                    $fPrice = (float) $price;
                } else {
                    break;
                }
            }
        }

        $fMapPrice = (float) $this->new_map_price;
        $fPrice = max($fPrice, $fMapPrice);
        return $fPrice;
    }

    public function recalculate($quantity, $type, $data)
    {
        return $quantity * $this->getFrontendPrice($quantity);
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
        if ($this->front_name === null) {
            $brand_name = '';

            $name = $this->seo_product_name ?: $this->product;

            if ($brand = $this->brand) {
                $brand_name = ($this->brand_normalized && !$this->isGroupRoot()) ? $brand->getProductFrontendName() . ' ' : '';
            }

            $this->front_name = ($this->isGroupChild()) ? $this->group_mask . ' ' . $name : $brand_name . $name;
        }

        return $this->front_name;
    }

    public function getFrontendDescription()
    {
        return $this->seo_fulldescr ?: $this->fulldescr ?: $this->descr;
    }

    public function getCatalogDescription($length = 0)
    {
        $frontend_description = $this->getFrontendDescription();
        $no_tags = strip_tags($frontend_description);

        if ( $length !== 0 && strlen($no_tags) > $length) {
            $shorted = substr_replace($no_tags, '...', $length);
        }

        //to one line
        return preg_replace("/(\r\n|\n|\r)/", " ", $shorted);
    }

    public function getPrices(): array
    {
        if ($this->priceArray === null) {
            $this->priceArray = [];
            $curr = $this->distributor->currency;
            foreach ($this->pricing as $price) {
                $price_value = CurrencyHelper::convert($curr, max($price->price, $this->new_map_price));
                if (!in_array($price_value, $this->priceArray, true)) {
                    $this->priceArray[$price->quantity] = $price_value;
                }
            }
        }
        return $this->priceArray ?? [];
    }

    public function getActualQuantity($quantity)
    {
        $tq = $quantity;
        $min = $this->min_amount;

        if ($this->mult_order_quantity && $min > 1) {
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

    public static function forsaleManager($instance = null): Manager
    {
        return static::objects($instance)->filter(['forsale' => 'Y']);
    }

    public static function without_groupManager($instance = null): Manager
    {
        return static::objects($instance)->filter([new QOr(['productid__isnt' => new Expression('group_root'), 'group_root__isnull' => true])]);
    }

    public static function showedManager($instance = null) : Manager
    {
        return static::forsale($instance)->filter([
            'sites__through__sfid' => Xcart::app()->getModule('Sites')->getSite(),
            new QOr([
                ['group_root__isnull' => true],
                ['productid' => new Expression('group_root')],
            ])
        ]);
    }

    public function isFreeShipping()
    {
        return false;
    }

    public function isFlatRate()
    {
        $site = Xcart::app()->getModule('Sites')->getSite();
        return $site->flat_shipping_enabled;
    }

    public function getExtraMarginValue(int $forQuantity = 1) :? float
    {
        $fExtraMarginValue = null;
        if (($distributor = $this->distributor)
            && $distributor->reduce_extra_margin
            && (float)$distributor->price_coef_z !== (float) 0
            && (($cost_to_us = $this->getProductCostToUs()) > 0)) {

            if ($distributor->max_extra_margin > (float) 0) {
                $fExpectedMargin = $cost_to_us * $distributor->max_extra_margin;
            } else {
                $fExpectedMargin = round((
                        $cost_to_us
                        * (float)$distributor->price_coef_x
                        + (float)$distributor->price_coef_y)
                    / (float)$distributor->price_coef_z, 2);
            }

            $fExtraMarginValue = ($this->getPrice($forQuantity) - $fExpectedMargin) * $forQuantity;

        }
        return $fExtraMarginValue;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options->filter(['active' => true])->order(['position'])->all();
    }

    public function getAmazonArbitragePrice($qty = 1):? array
    {
        $result = null;

        if ($this->ASIN && $offer = AmazonOfferCompetitorsModel::objects()->get(['id' => new Expression("f_amazonGetMinArbitrageOffer('{$this->ASIN}', {$qty})")])) {
            $result = [$offer->ListingPrice, $offer->Shipping];
        }

        return $result;
    }

    public function getVolume():? float
    {
        if ($this->shipping_dim_x || $this->shipping_dim_y || $this->shipping_dim_z) {
            return round($this->shipping_dim_x * $this->shipping_dim_y * $this->shipping_dim_z, 2);
        }
        return null;
    }

    public function getShippingWeight(): float
    {
        return max((float)$this->shipping_weight ?: (float)$this->weight, 0.01);
    }

    public function getFrontendPrice($forQuantity = 1)
    {
        $fPrice = $this->getPrice($forQuantity);

        /*if ($fPrice > $this->cost_to_us && $this->isOutOfStock() && $this->isSupplierFeedsEnabled()) {
            $fPrice = round($this->cost_to_us + ($fPrice - $this->cost_to_us) / 3,2);
            $fPrice = max((float) $this->new_map_price, $fPrice);
        }*/

        //$fPrice = CurrencyHelper::convert($this->distributor->currency, $fPrice);

        return $fPrice;
    }

    public function isMarketPlaceEnabled($marketpalce_id): bool
    {
        $b = null;
        $c = $this->markets_disabled->filter(['marketplace_id' => $marketpalce_id])->count();
        if (!$c) {
            if ($this->brand) {
                $b = $this->brand->markets_disabled->filter(['id' => $marketpalce_id])->count();
            }
            if (!$b) {
                $d = $this->distributor->markets_disabled->filter(['marketplace_id' => $marketpalce_id])->count();
            }
        }
        return !(($c+$b+$d) > 0);
    }

    public function getPriceValidUntil(): DateTime
    {
        return (new DateTime())->add(new DateInterval('P7D'));
    }

    public function getETADate():? DateTime
    {
        if ($this->eta_date_mm_dd_yyyy && $this->eta_date_mm_dd_yyyy > time()) {
            $date =  new DateTime();
            $date->setTimestamp($this->eta_date_mm_dd_yyyy);
            return $date;
        }
        return null;
    }

    public function setAttribute($name, $value)
    {

        if (($name === 'upc') && $upc = ProductHelper::calculateUPC($value)) {
            $value = $upc;
        }

        if ($name === 'eta_date_mm_dd_yyyy' ) {
            if ($this->eta_date_lock) {
                $value = $this->eta_date_mm_dd_yyyy;
            }
        }
        if ($name === 'weight' && $this->weight_lock) {
            $value = $this->weight;
        }
        if ($name === 'shipping_weight' && $this->shipping_weight_lock) {
            $value = $this->shipping_weight;
        }
        if ($name === 'dim_x' && $this->dim_lock) {
            $value = $this->dim_x;
        }
        if ($name === 'dim_y' && $this->dim_lock) {
            $value = $this->dim_y;
        }
        if ($name === 'dim_z' && $this->dim_lock) {
            $value = $this->dim_z;
        }
        if ($name === 'shipping_dim_x' && $this->shipping_dim_lock) {
            $value = $this->shipping_dim_x;
        }
        if ($name === 'shipping_dim_y' && $this->shipping_dim_lock) {
            $value = $this->shipping_dim_y;
        }
        if ($name === 'shipping_dim_y' && $this->shipping_dim_lock) {
            $value = $this->shipping_dim_y;
        }



        parent::setAttribute($name, $value);
    }

    public function getSlugPart(): string
    {
        return $this->createSlug($this->product);
    }

}