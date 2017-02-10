<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/07/16
 * Time: 14:49
 */

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class Dummy extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
            ],
            'address' => [
                'class' => CharField::class,
                'default' => 'example'
            ]
        ];
    }
}