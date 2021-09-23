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
 * @date 04/03/14.03.2014 01:17
 */

namespace Xcart\App\Orm\Tests\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * Class Blogger
 * @package Xcart\App\Orm\Tests\Models
 * @property string name
 * @property \Xcart\App\Orm\ManyToManyManager subscribers
 * @property \Xcart\App\Orm\ManyToManyManager subscribes
 */
class Blogger extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class
            ],
            /**
             * This subscribers subscribed to blogger
             */
            'subscribers' => [
                'class' => ManyToManyField::class,
                'modelClass' => self::class,
                'link' => ['blogger_to_id', 'blogger_from_id']
            ],
            /**
             * Blogger has these subscriptions
             */
            'subscribes' => [
                'class' => ManyToManyField::class,
                'modelClass' => self::class,
                'link' => ['blogger_from_id', 'blogger_to_id']
            ],
        ];
    }
}
