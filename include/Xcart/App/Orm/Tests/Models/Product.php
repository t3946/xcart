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
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @package Xcart\App\Orm\Tests\Models
 * @property string name
 * @property string price
 * @property string description
 * @property \Xcart\App\Orm\Tests\Models\Category category
 * @property \Xcart\App\Orm\Manager lists
 */
class Product extends Model
{
    public $type = 'SIMPLE';

    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'default' => 'Product',
                'validators' => [
                    new Assert\Length(['min' => 3])
                ]
            ],
            'price' => [
                'class' => CharField::class,
                'default' => 0
            ],
            'description' => [
                'class' => TextField::class,
                'null' => true
            ],
            'category' => [
                'class' => ForeignField::class,
                'modelClass' => Category::class,
                'null' => true,
            ],
            'lists' => [
                'class' => ManyToManyField::class,
                'modelClass' => ProductList::class,
                'link' => ['product_id', 'product_list_id']
            ]
        ];
    }
}
