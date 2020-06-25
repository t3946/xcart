<?php
namespace Modules\Goods\Models;

use Xcart\App\Main\Xcart;
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

    public function getURL($width = null)
    {
        $filename = basename($this->image_path);
        if ($width) {
            return Xcart::app()->router->url('api:image_resize', ['image_id' => $this->imageid, 'width' => $width, 'filename' => $filename]);
        }
        return Xcart::app()->router->url('api:image', ['image_id' => $this->imageid, 'filename' => $filename]);
    }
}