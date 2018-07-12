<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 13:04
 */

namespace Modules\Core\Behaviours;


use Exception;
use Modules\Core\TemplateLibraries\AssetsLibrary;
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
        'fieldTemplate' => 'forms/field/default/custom/field_custom.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl'
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
     * Execute before choose template
     * @param $templates
     * @param $defaultTemplateType
     */
    public function onBeforeGetTemplate(&$templates, &$defaultTemplateType)
    {
        $defaultTemplateType = $this->defaultTemplateType;
        $templates = array_merge($templates, $this->templates);
    }

    /**
     * Execute before create field
     * @param $name
     * @param $config
     * @return mixed
     */
    public function onBeforeCreateField(&$name, &$config)
    {
        if (empty($config['extend'])) {
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
        $params = $field->createClientValidationConfig();

        if (!empty($params)) {
            $this->_clientValidation[$field->name] = [
                'name' => $field->getHtmlName(),
                'json' => $params,
            ];
        }
    }

    /**
     * Execute before render form
     * @param $fields
     */
    public function onBeforeRender(&$fields): void
    {
        $js = '';
        foreach ($fields as $fieldName) {
            $info = $this->_clientValidation[$fieldName];
            if (empty($info)) {
                continue;
            }
            $js .= $this->_createClientValidationField($info);
        }

        $prefix = $this->owner->classNameShort();
        $js = $this->_wrapClientValidationConditions($prefix, $js);
        $this->_addClientValidationToPage($prefix, $js);
    }

    private function _createClientValidationField($info)
    {
        return '"' . $info['name'] . '":' . $info['json'] . ',';
    }

    private function _wrapClientValidationConditions($prefix, $js)
    {
        return 'var constraints' . $prefix . ' = {' . $js . '};';
    }

    private function _addClientValidationToPage($prefix, $js)
    {
        var_dump($js);
//        AssetsLibrary::addAsset([
//            'type' => 'js',
//            'position' => 'head',
//            'key' => 'form_validation_rule' . $prefix
//        ], $js);
        echo "<script>{$js}</script>";


    }


}