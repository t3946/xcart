<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class CorporateStorefrontsModel extends Model
{
    public static function getFields()
    {
        return [
            'storefront' => [
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefront_id' => 'storefrontid']
            ],
            'corporate' => [
                'class' => ForeignField::class,
                'modelClass' => CorporateModel::class,
                'link' => ['corporate_id' => 'id']
            ]
        ];
    }
}