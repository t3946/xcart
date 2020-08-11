<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ShareHolderModel extends Model
{
    public static function getFields(): array
    {
        return [
            'id' => AutoField::class,
            'corporate' => [
                'class' => ForeignField::class,
                'modelClass' => CorporateModel::class,
                'link' => ['corporate_id' => 'id']
            ],
            'name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'shares' => [
                'class' => IntField::class,
                'default' => 0,
            ],
            'percent' => [
                'class' => DecimalField::class,
                'default' => 0,
            ]
        ];
    }
}