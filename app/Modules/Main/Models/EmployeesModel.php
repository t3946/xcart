<?php
namespace Modules\Main\Models;

use Xcart\App\QueryBuilder\Expression;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
class EmployeesModel extends Model
{
    public static $sizes = [
        'thumb' => [
            50,50,
            'method' => 'adaptiveResize'
        ]
    ];

    public static function tableName()
    {
        return 's3_employees';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'isCeo' => BooleanField::class,
            'name' => CharField::class,
            'post' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'photo' => [
                'class' => ImageField::class,
                'sizes' => self::$sizes
            ],
            'photo2' => [
                'class' => ImageField::class,
                'sizes' => self::$sizes,
                'null' => true,
            ],
            'position' => [
                'class' => IntField::class,
                'default' => 9999,
                'null' => false,
            ],
        ];
    }

    public function __toString()
    {
        $str = $this->name;

        if ($this->isCeo) {
            $str .= " (CEO)";
        }

        return $str;
    }

    public function beforeSave($owner, $isNew)
    {
        /** @var self $owner */
        if( $owner->isCeo && $owner->getOldAttribute('isCeo') != $owner->isCeo) {
            self::objects()->update(['isCeo' => false]);
        }

        if ($this->position == 9999) {
            list($this->position) = static::objects()->limit(1)->valuesList(['position' => new Expression('Max(position) + 1')], true);
        }
    }
}