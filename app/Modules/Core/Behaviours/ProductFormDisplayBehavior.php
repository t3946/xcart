<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 11:53
 */

namespace Modules\Core\Behaviours;


use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\RadioField;
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
        'product' => 'forms/frontend/wrapped_fields.tpl'
    ];

    /**
     * Default params for all fields
     * @var array
     */
    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/product/one_field.tpl',
        'errorsTemplate' => 'forms/field/default/product/errors.tpl',
        'labelTemplate' => 'forms/field/default/product/label.tpl',
    ];

    /**
     *
     * @var array
     */
    protected $classFieldSettings = [
        RadioField::class => [
            'templateWithChoices' => 'forms/field/default/product/radio.tpl',
            'templateWithoutChoices' => 'forms/field/default/product/radio.tpl',
        ],
        DropDownField::class => [
            'inputTemplate' => 'forms/field/dropdown/product/input.tpl'
        ]
    ];
}