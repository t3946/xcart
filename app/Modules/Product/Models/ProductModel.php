<?php
namespace Modules\Product\Models;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Product;

class ProductModel extends AutoMetaModel
{
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
}