<?php

namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property int $image_id
 * @property ProductImageModel $image
 */
class ProductImageLinkModel extends Model
{
    public static function tableName()
    {
        return 'xcart_product_image_links';
    }

    public static function getFields()
    {
        return [
            'image_link_id' => AutoField::class,
            'hash' => CharField::class,
            'image' => [
                'field' => 'image_id',
                'class' => ForeignField::class,
                'modelClass' => ProductImageModel::class,
                'link' => ['image_id' => 'image_id']
            ],
            'url' => CharField::class,
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ]
        ];
    }
}