<?php

namespace Modules\User\Models;

use Xcart\App\Cli\Cli;
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
 * @property integer $start
 * @property integer $expiry
 * @property integer $cart_number
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
                'class' => CharField::className(),
                'length' => 32,
//                'primary' => true,
            ],
            'start' => [
                'class' => IntField::className(),
                'unsigned' => true,
                'null' => false,
                'default' => time(),
            ],
            'expiry' => [
                'class' => IntField::className(),
                'unsigned' => true,
                'null' => false,
                'default' => 0,
            ],
            'cart_number' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0,
            ],
            'data' => [
                'class' => SerializeField::className(),
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
        if ($isNew) {
            /** @var \Modules\User\UserModule $module */
            if ($module = Xcart::app()->getModule('User')) {
                $owner->expiry = time() + $module->sessionTime;
            }
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