<?php

namespace Modules\Meta\Models;

use Doctrine\DBAL\Types\Types;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class Meta extends Model
{
    public static function getFields()
    {
        $fields = [
            'is_custom' => [
                'class' => BooleanField::class,
                'verboseName' => 'Is custom',
                'helpText' => 'If "Set manually" field was not set, data will be generated automatically'
            ],
            'title' => [
                'class' => CharField::class,
                'length' => 200,
                'verboseName' => 'Title'
            ],
            'keywords' => [
                'class' => CharField::class,
                'length' => 200,
                'verboseName' => 'Keywords',
                'null' => true
            ],
            'description' => [
                'class' => CharField::class,
                'length' => 200,
                'verboseName' => 'Description',
                'null' => true
            ],
            'url' => [
                'class' => CharField::class,
                'verboseName' => 'Url',
                'null' => true
            ],
        ];

        $onSite = Xcart::app()->getModule('Meta')->onSite;
        if ($onSite) {
            $fields['site'] = [
                'field' => 'site_code',
                'class' => ForeignField::class,
                'sqlType' => Types::STRING,
                'modelClass' => Xcart::app()->getModule('Sites')->modelClass,
                'verboseName' => 'Site',
                'link' => ['site_code' => 'code'],
                'required' => false,
                'null' => true
            ];
        }

        return $fields;
    }

    public function __toString()
    {
        return (string)$this->title;
    }

    public function getAbsoluteUrl()
    {
        return $this->url;
    }

    public function beforeSave($owner, $isNew)
    {
        $onSite = Xcart::app()->getModule('Meta')->onSite;
        if ($onSite) {
            $sitesModule = Xcart::app()->getModule('Sites');
            if (($isNew || empty($owner->site)) && $sitesModule) {
                $owner->site = $sitesModule->getSite()->code;
            }
        }
    }

    public static function objectsManager($instance = null)
    {
        $className = get_called_class();
        /** @var Model $instance */
        $instance = ($instance ?: new $className);
        return new MetaManager($instance, $instance->getConnection());
    }
}
