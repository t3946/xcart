<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 11:53
 */

namespace Modules\Core\Behaviours;


use Xcart\App\Form\FormView\FormViewBehavior;

class ProductFormDisplayBehavior extends FrontendFormDisplayBehavior
{
    /**
     * Default template
     * @var string
     */
    protected $defaultTemplateType = 'product';

    /**
     * Additional templates
     * @var array
     */
    protected $templates = [
        'product' => 'forms/product/fields.tpl'
    ];

    /**
     * Default params for all fields
     * @var array
     */
    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/product/one_field.tpl',
        'errorsTemplate' => 'forms/field/default/product/errors.tpl',
        'hintTemplate' => 'forms/field/default/product/hint.tpl',
        'labelTemplate' => 'forms/field/default/product/label.tpl',
    ];

    /**
     *
     * @var array
     */
    protected $classFieldSettings = [
        'Xcart\App\Form\Fields\CharField' => [
            'inputTemplate' => 'forms/field/default/product/input.tpl',
        ],
        'Xcart\App\Form\Fields\NumberField' => [
            'inputTemplate' => 'forms/field/default/product/input.tpl',
        ]
    ];
}