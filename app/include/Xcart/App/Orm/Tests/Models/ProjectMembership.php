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
 * @date 04/01/14.01.2014 21:19
 */

namespace Xcart\App\Orm\Tests\Models;


use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ProjectMembership extends Model
{
    public static function getFields()
    {
        return [
            'project' => [
                'class' => ForeignField::class,
                'modelClass' => Project::class
            ],
            'worker' => [
                'class' => ForeignField::class,
                'modelClass' => Worker::class
            ],
            'position' => [
                'class' => IntField::class
            ],
            'curator' => [
                'class' => ForeignField::class,
                'modelClass' => Worker::class
            ]
        ];
    }
}
