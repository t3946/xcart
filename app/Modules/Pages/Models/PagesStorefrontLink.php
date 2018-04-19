<?php

namespace Modules\Pages\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class PagesStorefrontLink extends Model
{
    public static function getFields()
    {
        return [

            'page' => [
                'field' => 'page_id',
                'class' => ForeignField::className(),
                'modelClass' => Page::className(),
                'link' => ['page_id' => 'id'],
                'primary' => true,
                'null' => false,
            ],

            'sf' => [
                'field' => 'storefront_id',
                'class' => ForeignField::className(),
                'modelClass' => SiteModel::className(),
                'link' => ['storefront_id' => 'storefrontid'],
                'primary' => true,
                'null' => false,
            ]
        ];
    }
}