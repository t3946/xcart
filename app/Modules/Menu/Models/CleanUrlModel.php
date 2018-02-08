<?php

namespace Modules\Menu\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * Class CleanUrlModel
 *
 * @package Modules\Menu\Models
 *
 * @property string clean_url
 * @property string resource_type
 * @property integer resource_id
 * @property integer mtime
 */
class CleanUrlModel extends Model
{
    public static function tableName()
    {
        return 'xcart_clean_urls';
    }

    public static function getFields()
    {
        return [
            'clean_url' => [
                'class' => CharField::className(),
                'null' => false,
                'unique' => true,
            ],
            'resource_type' => [
                'class' => CharField::className(),
                'null' => false,
                'primary' => true,
                'length' => 1,
                'choices' => [
                    'P' => 'Product',
                    'M' => 'Brand',
                    'C' => 'Category',
                    'S' => 'Static page'
                ],
            ],
            'resource_id' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0,
            ],
            'mtime' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => time(),
            ]
        ];
    }

    public function beforeSave($owner, $isNew)
    {
        $owner->mtime = time();

        parent::beforeSave($owner, $isNew);
    }

    public function getSlugPart():string
    {
        return end(explode('/', $this->clean_url));
    }

    public function urlFromCode($code = null, $absolute = false, SiteModel $site = null):string
    {
        $path = '';
        $site = $site ?: Xcart::app()->getModule('Sites')->getSite();

        if ($absolute && $site) {
            $path .= '//' . $site->domain;
        }

        if ($code) {
            $path .= Xcart::app()
                ->router
                ->url( $code, [
                    'id' => $this->resource_id,
                    'slug' => $this->getSlugPart(),
                ]);
        }
        else {
            $path .= '/' . $this->clean_url;
        }


        return $path;
    }
}