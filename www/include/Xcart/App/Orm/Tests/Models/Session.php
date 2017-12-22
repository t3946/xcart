<?php

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Fields\BlobField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * Class Session
 * @package Modules\User
 */
class Session extends Model
{
    public static function getFields()
    {
        return [
            'id' => [
                'class' => CharField::class,
                'length' => 32,
                'primary' => true,
                'null' => false,
            ],
            'expire' => [
                'class' => IntField::class,
                'null' => false,
            ],
            'data' => [
                'class' => BlobField::class,
                'null' => true,
            ]
        ];
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
