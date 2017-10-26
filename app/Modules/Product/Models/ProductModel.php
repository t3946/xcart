<?php
namespace Modules\Product\Models;

use Mindy\QueryBuilder\Expression;
use Modules\Amazon\Models\AmazonFbaMissingSkuModel;
use Modules\Amazon\Models\AmazonProductsFieldsModel;
use Modules\Brand\Models\BrandModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Product;

/**
 * @property string forsale
 * @property string update_search_index
 * @property string productcode
 * @property mixed eta_date_mm_dd_yyyy
 * @property mixed eta_date_lock
 * @property mixed productid
 * @property mixed distributor
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
 * @property mixed product
 * @property mixed fulldescr
 * @property string controlled_by_feed
 * @property mixed brandid
 * @property integer source_sfid
 * @property integer manufacturerid
 * @property int add_date
 * @property int mod_date
 * @property mixed|string upc
 */
class ProductModel extends AutoMetaModel
{
    const ADMIN_PRODUCT_MODIFY_URL = '/admin/product_modify.php?productid=%d&sf=%d';

    use DataModelTrait;

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
            'descr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'fulldescr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'seo_fulldescr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
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
                'default' => 0
            ],
            'clone_parent_productid' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0
            ],
            'sites' => [
                'class' => ManyToManyField::className(),
                'modelClass' => SiteModel::className(),
                'through' => ProductStorefrontModel::className(),
                'link' => ['productid' => 'productid']
            ],
            'missing_products' => [
                'class' => HasManyField::className(),
                'modelClass' => AmazonFbaMissingSkuModel::className(),
                'link' => ['productid' => 'productid']
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
            'categories' => [
                'class' => ManyToManyField::className(),
                'modelClass' => CategoryModel::className(),
                'through' => ProductCategoriesModel::className(),
            ],
            'childs' => [
                'class' => HasManyField::className(),
                'modelClass' => ProductModel::className(),
                'link' => ['group_root' => 'productid'],
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
                'link' => ['id' => 'productid']
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

    public function getTitle()
    {
        if ($this->seo_product_name) {
            $title = $this->seo_product_name;
        } else {
            $title = $this->product;
        }

        return ($this->isGroupChild()) ?  $this->parent->group_mask . " ". $title : $title;
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
        if ($thumb = $this->thumbnail->limit(1)->get()) {
            return $thumb;
        }
        return null;
    }

    public function getAdminUrl()
    {
        return sprintf(self::ADMIN_PRODUCT_MODIFY_URL, $this->productid, $this->sites->limit(1)->get()->storefrontid);
    }

}