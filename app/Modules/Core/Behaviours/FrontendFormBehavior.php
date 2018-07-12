<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 13:04
 */

namespace Modules\Core\Behaviours;


use Exception;
use Xcart\App\Behaviours\BaseBehavior;
use Xcart\App\Form\Fields\Field;
use Xcart\App\Form\FormView\FormViewBehavior;
use Xcart\App\Helpers\Creator;

class FrontendFormBehavior extends FormViewBehavior
{

    /**
     * Default template
     * @var string
     */
    protected $defaultTemplateType = 'frontend';

    /**
     * Additional templates
     * @var array
     */
    protected $templates = [
        'frontend' => 'forms/frontend.tpl'
    ];

    /**
     * Default params for all fields
     * @var array
     */
    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/field_custom.tpl'
    ];

    /**
     * Default params for compound fields
     * @var array
     */
    protected $fieldsCompoundSettings = [
        'fieldTemplate' => 'forms/field/default/custom/field_compound.tpl'
    ];

    private $_clientValidation = [];

    /**
     * Execute before create field
     * @param $name
     * @param $config
     * @return mixed
     */
    public function onBeforeCreateField(&$name, &$config)
    {
        if(empty($config['extend'])) {
            // Render default field
            $config = array_merge($this->fieldsSettings, $config);
        } else {
            // Render compound field
            $config = array_merge($this->fieldsSettings, $this->fieldsCompoundSettings, $config);
        }
    }

    /**
     * Execute after field is created
     * @param $field Field
     */
    public function onAfterCreateField(&$field)
    {
        $this->_clientValidation[$field->name] = $field->createClientValidationConfig();

//        AssetsLibrary::addAsset([
//            'type' => 'js',
//            'position' => 'end',
//            'key' => 'ace_theme'.$this->theme
//        ], $json);

    }

    /**
     * Execute before render form
     * @param $fields
     */
    public function onBeforeRender(&$fields):void
    {
        $js = "";
        foreach ($fields as $fieldName) {
            $json = $this->_clientValidation[$fieldName];
            //var_dump($json);
        }
    }



}