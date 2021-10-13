<?php

namespace Modules\User\Models;

use Modules\User\UserModule;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

/**
 * Class SessionDataModel
 *
 * @package Modules\User\Models
 *
 * @property string(32) $sessid
 * @property int $start
 * @property int $expiry
 * @property int $cart_number
 * @property array|string $data
 */
class SessionDataModel extends Model
{
    public static function tableName()
    {
        return 'xcart_sessions_data';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
                'primary' => true,
            ],
            'sessid' => [
                'class' => CharField::class,
                'length' => 32,
//                'null' => true,
//                'primary' => true,
            ],
            'start' => [
                'class' => IntField::class,
                'unsigned' => true,
                'null' => false,
                'default' => time(),
            ],
            'expiry' => [
                'class' => IntField::class,
                'unsigned' => true,
                'null' => false,
                'default' => 0,
            ],
            'cart_number' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'data' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => '',
            ],

            'surf_meta' => [
                'field' => 'sessid',
                'class' => HasManyField::class,
                'modelClass' => SurfMetaModel::class,
                'link' => ['sessid' => 'sessid']
            ],
        ];
    }

    public function beforeSave($owner, $isNew)
    {
        /** @var UserModule $module */
        if ($isNew && $module = Xcart::app()->getModule('User')) {
            $owner->expiry = time() + $module->sessionTime;
        }
    }

    public function afterInsertInternal()
    {
        if ($arr = $this->getObjects()->filter(['id' => $this->id])->valuesList(['sessid'], true)) {
            $this->sessid = $arr[0];
        }

        parent::afterInsertInternal();
    }
}