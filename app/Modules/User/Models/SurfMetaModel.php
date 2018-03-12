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

            if (!Xcart::app()->request->session->getIsActive()) {
                Xcart::app()->request->session->start();
            }

            if ($sessId = Xcart::app()->request->session->getId()) {
                self::$_instance = self::objects()->filter(["sessid" =>$sessId])->get();

                if (is_null(self::$_instance)) {
                    $md = new Mobile_Detect();
                    self::$_instance =  new self(
                        [
                            "sessid"         => $sessId,
                            "date"           => time(),
                            "is_mobile"      => ($md->isMobile() ? "Y" : "N"),
                            "goal_order"     => 'N',
                            "goal_checkout"  => 'N',
                            "goal_addtocart" => 'N',
                            "goal_search"    => 'N',
                            "points_visited" => '0',
                            "last_update"    => time(),
                            "storefrontid"   => Xcart::app()->getModule('Sites')->getSite()->storefrontid,
                        ]
                    );
                    self::$_instance->save();
                }
            }
        }

        return self::$_instance;
    }
}