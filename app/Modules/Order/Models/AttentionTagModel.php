<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;

/**
 * @method static Manager active($instance = null)
 * @method static Manager ordered($instance = null)
 * @property string $status
 */
class AttentionTagModel extends Model
{
    public const RESUME_ORDER_TAG = 63;

    public static function tableName()
    {
        return 'xcart_attention_tags_values';
    }

    public static function getFields()
    {
        return [
            'status_id' => AutoField::class,
            'status' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'active' => [
                'class' => CharField::class,
                'null' => false,
                'choices' => [
                    'Y' => 'Enabled',
                    'N' => 'Disabled'
                ],
                'default' => 'Y'
            ],
            'events' => [
                'class' => IntField::class,
                'length' => 1,
                'null' => false,
                'choices' => [
                    0 => 'None',
                    1 => 'Trigger'
                ],
                'default' => 0
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
            'color' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '#F4CCCC',
            ],
            'description' => [
                'class' => TextField::class,
                'null' => false,
                'default' => ''
            ],
            'users' => [
                'class' => HasManyField::class,
                'modelClass' => AttentionTagUserModel::class,
                'link' => ['status_id' => 'status_id']
            ]
        ];
    }

    public function __toString()
    {
        return (string)$this->status;
    }

    public static function activeManager($instance = null): Manager
    {
        return static::objects($instance)->filter(['active' => 'Y']);
    }

    public static function orderedManager($instance = null): Manager
    {
        return static::active($instance)->order(['orderby']);
    }
}