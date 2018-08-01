<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 11:50
 */

namespace Modules\Core\Forms;


use Modules\Core\Behaviours\ClientValidationBehavior;
use Modules\Core\Behaviours\ProductFormDisplayBehavior;

abstract class ProductForm extends FrontendForm
{
    /**
     * Default Behaviour
     * The higher the position, the higher the priority
     * @return array
     */
    protected function behaviours()
    {
        return [
            'validation' => [
                'class' => ClientValidationBehavior::class,
                'enabled' => true
            ],
            'decor' => [
                'class' => ProductFormDisplayBehavior::class,
                'enabled' => true
            ],
        ];
    }
}