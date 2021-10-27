<?php


namespace Modules\Goods\Models\Videos;

use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ProductsVideosModel extends Model
{
    public static function tableName()
    {
        return 'xcart_products_videos';
    }

    public static function getFields()
    {
        return [
            'product_id' => [
                'class' => IntField::class,
            ],
            'video_id' => [
                'class' => IntField::class,
            ],
            'videos' => [
                'class' => ForeignField::class,
                'modelClass' => VideosModel::class,
                'link' => ['video_id' => 'video_id'],
            ],
        ];
    }
}