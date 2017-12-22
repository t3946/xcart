<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 16/09/16
 * Time: 15:32
 */

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Model;

class Member extends Model
{
    public static function getFields()
    {
        return [
            'profile' => [
                'class' => OneToOneField::class,
                'modelClass' => MemberProfile::class,
            ],
        ];
    }
}