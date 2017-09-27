<?php

namespace Modules\Menu\Models;

use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\SlugifyTrait;

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
    use SlugifyTrait;

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
                'chosen' => [
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

    public function urlFromCode($code = null, $absolute = false, $site = null)
    {
        $path = '';

        if (!$site) {
            /** @var \Modules\Sites\SitesModule $module */
            $module = Xcart::app()->getModule('Sites');

            $site = $module->getSite(true);
        }

        if ($absolute) {
            if ($site) {
                $path .= $site->domain . '/';
            }
        }

        if ($code) {
            $ta = explode('/', $this->clean_url);
            $last = end($ta);

            $path = Xcart::app()->router->url(
                $code,
                [
                    'id' => $this->resource_id,
                    'slug' => $this->createSlug($last)
                ]
            );
        }
        else {
            $path .= '/' . $this->clean_url;
        }


        if ($absolute) {
            $path = '//' . $path;
        }

        return $path;
    }
}