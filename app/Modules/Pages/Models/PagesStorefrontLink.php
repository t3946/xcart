<?php

namespace Modules\Pages\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class PagesStorefrontLink extends Model
{
    public static function getFields()
    {
        return [

            'id' => [
                'class' => AutoField::className(),
            ],

            'storefront' => [
                'class' => ForeignField::className(),
                'modelClass' => SiteModel::className(),
                'link' => ['storefront_id' => 'storefrontid']
            ],
        ];
    }
}