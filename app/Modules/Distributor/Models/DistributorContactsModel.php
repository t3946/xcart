<?php

namespace Modules\Distributor\Models;


use Modules\Distributor\Helpers\DistributorHelper;
use Modules\User\Helpers\PhoneHelper;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
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
            'pq' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'contact_name' => [
                'class' => CharField::class,
                'default' => '',
            ],
            'email' => [
                'class' => CharField::class,
                'default' => '',
            ],
            'phone' => [
                'class' => CharField::class,
                'default' => '',
            ],
        ];
    }

    public function getPhoneNormalized(): string
    {
        if (strlen($phone_normalized = PhoneHelper::phoneNormalize($this->phone)) === 10){
            return PhoneHelper::getPhonePrefix($this->distributor->m_country) . $phone_normalized;
        }
        return $this->phone;
    }
}