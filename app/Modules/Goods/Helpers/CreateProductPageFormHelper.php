<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 15:48
 */

namespace Modules\Goods\Helpers;


use Modules\Goods\Forms\FieldColorForm;
use Modules\Goods\Forms\FieldRadioForm;
use Modules\Goods\Forms\FieldSelectForm;

class CreateProductPageFormHelper
{
    /**
     * @var array
     */
    private $_formFields = [
        'color' => FieldColorForm::class,
        'radio' => FieldRadioForm::class,
        'select' => FieldSelectForm::class,
    ];

    private $_form = null;
    private $_prefix = 'ProductFieldsGroupForm';


    /**
     * CreateProductPageFormHelper constructor.
     * @param $options
     */
    public function __construct($options)
    {
        if (empty($options)) {
            return;
        }

        $resultObject = null;

        foreach ($options as $option) {

            $class = $this->_formFields[$option->option->type];
            $title = $option->option->title;
            $variants = [];

            foreach ($option->variants as $oneVariant) {
                if(empty($oneVariant->variant->value)){
                    continue;
                }

                $key = $oneVariant->product_option_id . '-' . $oneVariant->variant_id;
                if($option->option->type == 'color') {
                    $key = $key . '_' . trim($oneVariant->variant->value);
                }

                $text = !empty($oneVariant->variant->name) ? $oneVariant->variant->name : $oneVariant->variant->value;
                $variants[$key] = $text;
            }

            $params = [
                'title' => $title,
                'variants' => $variants,
            ];

            $resultObject = $this->create($class, $params, $resultObject);
        }

        $this->_form = $resultObject;
        $this->_form->setPrefix($this->_prefix);

    }

    public function getForm()
    {
        return $this->_form;
    }

    /**
     * @param $class
     * @param $params
     * @param null $subClass
     * @return mixed
     */
    private function create($class, $params, $subClass = null)
    {
        if(empty($class)) {
            return $subClass;
        }
        return new $class($params, $subClass);
    }
}