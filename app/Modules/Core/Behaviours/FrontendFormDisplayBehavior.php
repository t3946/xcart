<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 13:04
 */

namespace Modules\Core\Behaviours;

use Xcart\App\Form\FormView\FormViewBehavior;

class FrontendFormDisplayBehavior extends FormViewBehavior
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
        'frontend' => 'forms/frontend/fields.tpl'
    ];

    /**
     * @var string
     */
    public $formBeginTemplate = 'forms/frontend/begin.tpl';

    /**
     * Default params for all fields
     * @var array
     */
    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/field.tpl',
        'fieldTemplate' => 'forms/field/default/custom/field.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl'
    ];

    /**
     * Default params for compound fields
     * @var array
     */
    protected $fieldsCompoundSettings = [
        'fieldTemplate' => 'forms/field/default/custom/field_compound.tpl'
    ];

    /**
     * Execute before choose form template
     * Override custom form template from behavior
     * (new settings must override old)
     * @param $templates
     * @param $defaultTemplateType
     */
    public function onBeforeGetTemplate(&$templates, &$defaultTemplateType)
    {
        $defaultTemplateType = $this->defaultTemplateType;
        $templates = array_merge($templates, $this->templates);
    }

    /**
     * Execute before form field creation
     * Override default fields creation settings
     * (new settings MUST override old)
     * @param $name
     * @param $config
     * @return mixed
     */
    public function onBeforeCreateField(&$name, &$config)
    {
        $tmpConfig = array_merge([], $config);
        if (empty($config['extend'])) {
            // Render default field
            $tmpConfig = array_merge($this->fieldsSettings, $tmpConfig);
        } else {
            // Render compound field
            $tmpConfig = array_merge($this->fieldsSettings, $this->fieldsCompoundSettings, $tmpConfig);
        }
        $config = array_merge($config, $tmpConfig);
    }

    /**
     * Execute before form begin render
     * Override form head creation settings
     * (new settings must override old)
     * @param $prefix
     * @param $template
     */
    public function onBeforeRenderBegin(&$prefix, &$template):void
    {
        if(empty($template)){
            $template = $this->formBeginTemplate;
        }
    }


}