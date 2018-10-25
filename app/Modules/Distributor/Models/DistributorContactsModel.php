<?php

namespace Modules\Distributor\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class DistributorContactsModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_distributor_contacts';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class
            ]
        ];
    }
}