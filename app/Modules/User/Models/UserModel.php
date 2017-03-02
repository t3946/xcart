<?php
namespace Modules\User\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;

class UserModel extends AutoMetaModel
{
    public $is_guest = false;

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
            ]
        ];
    }

    public function __toString()
    {
        return $this->firstname;
    }

    public function getIsGuest()
    {
        return $this->is_guest;
    }
}