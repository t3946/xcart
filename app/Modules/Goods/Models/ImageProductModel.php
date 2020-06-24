<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BlobField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;

class ImageProductModel extends ImageModel
{
    public static function getFields()
    {
        $fields =  array_merge_recursive(parent::getFields(), [
            'product' => [
                'field' => 'id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['id' => 'productid'],
            ]
        ]);
        unset($fields['id']);
        return $fields;
    }
}