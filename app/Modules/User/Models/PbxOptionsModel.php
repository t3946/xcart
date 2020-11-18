<?php


namespace Modules\User\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Model;

/**
 * Class PbxOptionsModel
 *
 * @property (string) $extension
 * @property (string) $anveo_account
 * @property (string) $anveo_password
 *
 * @property \Modules\User\Models\UserModel|null $user
 *
 * @package Modules\User\Models
 */
class PbxOptionsModel extends Model
{
    public static function tableName()
    {
        return 'xcart_pbx_options';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,

            'user' => [
                'class' => HasToOneField::class,
                'modelClass' => UserModel::class,
                'link' => ['extension' => 'pbx_extension'],
                'extra' => ['user__usertype__in' => ['A', 'P'], 'user__status' => 'Y']
            ],

            'extension' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],

            'anveo_account' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],

            'anveo_password' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ]

        ];
    }

    public function __toString()
    {
        return (string)$this->user;
    }


}