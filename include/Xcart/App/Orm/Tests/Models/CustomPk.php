<?php
/**
 * 
 *
 * All rights reserved.
 * 
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 17/05/14.05.2014 16:50
 */

namespace Xcart\App\Orm\Tests\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class CustomPk extends Model
{
    public static function getFields()
    {
        return [
            'id' => [
                'class' => CharField::class,
                'primary' => true
            ]
        ];
    }
}
