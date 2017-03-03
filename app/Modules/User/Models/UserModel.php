<?php
namespace Modules\User\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;

class UserModel extends AutoMetaModel
{
//    public $is_guest = false;
//    public $is_staff = false;
//    public $is_superuser = false;

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
            'login' => [
                'class' => CharField::className(),
                'null' => false,
                'unique' => true,
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
            return !in_array($this->usertype, ['C', 'B']);
        }

        return false;
    }

    public function getIsSuperuser()
    {
        if (!$this->getIsGuest()) {
            return $this->usertype == 'A';
        }

        return false;
    }
}