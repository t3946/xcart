<?php

namespace Modules\Distributor\Models;


use Modules\Distributor\Helpers\DistributorHelper;
use Modules\User\Helpers\PhoneHelper;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
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
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid']
            ]
        ];
    }

    public function getPhoneNormalized(): string
    {
        if (strlen($phone_normalized = PhoneHelper::phoneNormalize($this->phone)) === 10){
            return $this->distributor->getPhonePrefix() . $phone_normalized;
        }
        return $this->phone;
    }
}