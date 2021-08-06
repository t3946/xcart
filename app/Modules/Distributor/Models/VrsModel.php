<?php

namespace Modules\Distributor\Models;

use Modules\Forms\Models\TemplateModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class VrsModel extends Model
{
    public const STATUS_CHOICES = [
        1 => 'Declined',
        2 => 'Waiting for a reply',
        3 => 'Panding',
        4 => 'Dont drop-ship',
        5 => 'Not interested',
        6 => 'Successfully added vendor to xcart',
    ];

    public static function tableName()
    {
        return 'xcart_vrs';
    }

    public static function getFields()
    {
        return [
            'vrs_id' => [
                'class' => AutoField::class
            ],
            'sf' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['site_id' => 'storefrontid'],
            ],
            'company' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'link_website' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true,
                'verboseName' => 'Link to website'
            ],
            'last_action' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Last Action'
            ],
            'status' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
                'choices' => self::STATUS_CHOICES,
                'verboseName' => 'Status'
            ],
            'date' => [
                'class' => DateField::class,
                'default' => null,
                'null' => true
            ],
            'email' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'telephone' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'login' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'password' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'comment' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'id'],
            ],
        ];
    }

    public function getWebSiteUrl()
    {
        return $this->link_website;
    }

}