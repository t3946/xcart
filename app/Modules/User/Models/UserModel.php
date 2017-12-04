<?php
namespace Modules\User\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Customer;

class UserModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass()
    {
        return Customer::className();
    }

    public static function tableName()
    {
        return 'xcart_customers';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],

            'pbx_extension' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],

            'login' => [
                'class' => CharField::className(),
                'null' => false,
                'unique' => true,
            ],
            'show_events' => [
                'class' => IntField::className(),
                'length' => 1,
                'default' => 0,
                'choices' => [
                    0 => 'Disable',
                    1 => 'Enable'
                ]
            ],
            'show_events_min_date' => [
                'class' => DateTimeField::className(),
                'null' => true
            ],
            'usertype' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => 'C',
                'choices' => [
                    'A' => 'Admin',
                    'B' => 'B (Partners ?)',
                    'C' => 'Customer',
                    'P' => 'Operator',
                    'V' => 'Verificator',
                ]
            ],
        ];
    }

    public function __toString()
    {
        return $this->firstname;
    }

    public function getIsGuest()
    {
        return $this->isNewRecord || empty($this->login);
    }

    public function getIsStaff()
    {
        if (!$this->getIsGuest()) {
            return !in_array($this->usertype, ['C', 'B']) || !empty($this->membershipid);
        }

        return false;
    }

    public function getIsSuperuser()
    {
        if (!$this->getIsGuest()) {
            return (in_array($this->usertype, ['A'])) && empty($this->membershipid);
        }

        return false;
    }

    public function getAdminUrl()
    {
        return "/admin/user_modify.php?user={$this->login}&usertype={$this->usertype}";
    }
}