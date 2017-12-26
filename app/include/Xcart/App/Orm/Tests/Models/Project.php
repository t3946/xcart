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
 * @date 04/03/14.03.2014 01:15
 */

namespace Xcart\App\Orm\Tests\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * Class Project
 * @package Xcart\App\Orm\Tests\Models
 * @property string name
 * @property \Xcart\App\Orm\ManyToManyManager workers
 */
class Project extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class
            ],
            'workers' => [
                'class' => ManyToManyField::class,
                'modelClass' => Worker::class,
                'through' => ProjectMembership::class,
                'link' => ['project_id', 'worker_id'],
            ]
        ];
    }
}
