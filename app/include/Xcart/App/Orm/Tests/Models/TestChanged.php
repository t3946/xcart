<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 16/09/16
 * Time: 19:28
 */

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class TestChanged extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'length' => 50,
                'verboseName' => "Name"
            ]
        ];
    }

    public static function tableName()
    {
        return "tests_test_changed";
    }
}