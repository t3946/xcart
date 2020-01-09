<?php


namespace Modules\Distributor\Models;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class DistributorSiteModel extends Model
{
    public static function tableName()
    {
        return 'xcart_manufacturers_site';
    }

    public static function getFields()
    {
        return [
            'distributor' => [
                'field' => 'manufacturer_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturer_id' => 'manufacturerid'],
                'primary' => true,
            ],
            'site' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['site_id' => 'storefrontid'],
                'primary' => true,
            ]
        ];
    }
}