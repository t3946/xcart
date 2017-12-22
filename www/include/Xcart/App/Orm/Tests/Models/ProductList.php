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
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * Class ProductList
 * @package Xcart\App\Orm\Tests\Models
 * @property string name
 * @property \Xcart\App\Orm\ManyToManyManager products
 */
class ProductList extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class
            ],
            'products' => [
                'class' => ManyToManyField::class,
                'modelClass' => Product::class,
                'link' => ['product_list_id', 'product_id']
            ],
            'date_action' => [
                'class' => DateTimeField::class,
                'required' => false,
                'null' => true
            ]
        ];
    }
}
