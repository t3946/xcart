<?php


namespace Modules\Distributor\Models;


use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class DistributorContactUtilityModel extends Model
{
    public static function tableName()
    {
        return 'xcart_distributor_contact_utility';
    }

    public static function getFields()
    {
        return [
            'utility' => [
                'field' => 'utility_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorUtilityModel::class,
                'link' => ['utility_id' => 'utility_id']
            ],
            'contact' => [
                'field' => 'contact_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorContactsModel::class,
                'link' => ['contact_id' => 'id']
            ],
        ];
    }
}