<?php

namespace Modules\Distributor\Models;


use Modules\Distributor\Helpers\DistributorHelper;
use Modules\User\Helpers\PhoneHelper;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * @property string contact_name
 * @property int id
 * @property string email
 */
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
                'verboseName' => 'Contact name'
            ],
            'distributor_field_name' => [
                'class' => CharField::class,
                'default' => '',
                'verboseName' => 'Position'
            ],
            'email' => [
                'class' => CharField::class,
                'default' => '',
            ],
            'phone' => [
                'class' => CharField::class,
                'default' => '',
            ],
            'ext' => [
                'class' => CharField::class,
                'default' => '',
            ],
            'fax' => [
                'class' => CharField::class,
                'default' => '',
            ],
            'utility' => [
                'field' => 'utility_id',
                'class' => ManyToManyField::class,
                'modelClass' => DistributorUtilityModel::class,
                'through' => DistributorContactUtilityModel::class,
            ]
        ];
    }

    public function getPhoneNormalized(): string
    {
        if (strlen($phone_normalized = PhoneHelper::phoneNormalize($this->phone)) === 10){
            return PhoneHelper::getPhonePrefix($this->distributor->m_country) . $phone_normalized;
        }
        return $this->phone;
    }

    public function __toString()
    {
        return $this->id ? $this->contact_name ?? '' : 'Contact';
    }

    public function getEmail(): string
    {
        $email = ($this->contact_name ? "<" : '') . $this->email . ($this->contact_name ? ">" : '');
        return htmlentities("{$this->contact_name} {$email}");
    }
}