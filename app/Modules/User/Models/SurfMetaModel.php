<?php

namespace Modules\User\Models;

use Mobile_Detect;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class SurfMetaModel extends Model
{
    use AutoMetaTrait;

    /** @var null|SurfMetaModel  */
    private static $_instance = null;

    public static function tableName()
    {
        return 'xcart_cidev_surf_meta';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className()
            ],

            'session_data' => [
                'field' => 'sessid',
                'class' => ForeignField::class,
                'modelClass' => SessionDataModel::class,
                'link' => ['sessid' => 'sessid'],
            ],

            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'id'],
            ],

            'surf_path' => [
                'field' => 'id',
                'class' => HasManyField::class,
                'modelClass' => SurfPathModel::class,
                'link' => ['id' => 'id']
            ]
        ];
    }

    static public function getInstance()
    {
        if (is_null(self::$_instance)) {

            Xcart::app()->request->session->open();

            if ($sessId = Xcart::app()->request->session->getId()) {
                [self::$_instance, $is_new] = self::objects()->getOrCreate(["sessid" =>$sessId]);

                if ($is_new)
                {
                    self::$_instance->setAttributes([
                        "date"           => time(),
                        "is_mobile"      => ((new Mobile_Detect())->isMobile() ? "Y" : "N"),
                        "last_update"    => time(),
                        "storefrontid"   => Xcart::app()->getModule('Sites')->getSite()->storefrontid,
                    ]);
                    self::$_instance->save();
                }

                if (!self::$_instance->user_id) {
                    if ($user = Xcart::app()->getUser()) {
                        self::$_instance->user_id = $user->pk;
                        self::$_instance->save();
                    }
                }
            }
        }

        return self::$_instance;
    }
}