<?php
namespace Modules\Product\Models;

use Modules\Brand\Models\BrandModel;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
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
            'categories' => [
                'class' => ManyToManyField::className(),
                'modelClass' => CategoryModel::className(),
                'through' => ProductCategoriesModel::className(),
            ],

            'categories_link' => [
                'class' => HasManyField::className(),
                'modelClass' => ProductCategoriesModel::className(),
                'link' => ['productid' => 'productid']
            ],

            'prices' => [
                'class' => HasManyField::className(),
                'modelClass' => PricingModel::className(),
                'link' => ['productid' => 'productid']
            ],

            'quick_prices' => [
                'class' => ManyToManyField::className(),
                'modelClass' => PricingModel::className(),
                'through' => QuickPricingModel::className(),
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
            ],
            'filter_values' => [
                'class' => ManyToManyField::className(),
                'modelClass' => FilterValueModel::className(),
                'through' => FilterProductModel::className(),
            ],
            'images' => [
                'class' => HasManyField::className(),
                'modelClass' => ImagePModel::className(),
                'link' => ['id' => 'productid'],
//                'extra' => ['avail' => 'Y']
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


    public function getParamList()
    {
        $values = $this->filter_values->filter(['fv_active' => 'Y'])->order(['f_id','fv_order_by'])->all();
        if ($values) {

            $filters = FilterModel::objects()->filter(['f_id__in' => array_map(function($value){ return $value->f_id; }, $values)])->order(['f_order_by'])->all();

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

    public function isSaleSticker()
    {
        $fp = $this->getFrontendPrice();

        return ($this->list_price > ($fp + $fp * .3));
    }


    public function isOutOfStock()
    {
        return $this->isProductOutOfStock();
    }

    public function getAbsoluteUrl()
    {
        return Xcart::app()->router->url('product:view', ['sku' => $this->productcode]);
    }
}