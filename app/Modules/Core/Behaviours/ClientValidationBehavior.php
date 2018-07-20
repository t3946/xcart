<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 13.07.2018
 * Time: 11:08
 */

namespace Modules\Core\Behaviours;


use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\Field;
use Xcart\App\Form\FormView\FormViewBehavior;

class ClientValidationBehavior extends FormViewBehavior
{
    /**
     * Head form template
     * @var string
     */
    public $formBeginTemplate = 'forms/frontend/begin_client_validation.tpl';

    /**
     * Client validation info
     * @var array
     */
    private $_clientValidation = [];

    /**
     * @var array
     */
    private $_jsEvent = 'form.client.validation';

    /**
     * @var string
     */
    private $_js = '';

    /**
     * Execute before form begin render
     * @param $prefix
     * @param $template
     */
    public function onBeforeRenderBegin(&$prefix, &$template): void
    {
        if (empty($template)) {
            $template = $this->formBeginTemplate;
        }
    }

    /**
     * Execute before form render
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

        $prefix = $this->owner->getFormIdentifier();
        $this->_js = $this->_createClienValidationScript($prefix, $js);
    }

    /**
     * Execute before form end render
     * (new settings must override old)
     * @param $prefix
     * @param $template
     */
    public function onBeforeRenderEnd(&$prefix, &$template): void
    {
        echo $this->_js;
    }

    /**
     * Execute after form field is created
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
     *
     * @param $info
     * @return string
     */
    private function _createClientValidationField($info)
    {
        return '"' . $info['name'] . '":' . $info['json'] . ',';
    }

    /**
     * Render validation info script
     * @param $prefix
     * @param $js
     * @return string
     */
    private function _wrapClientValidationConditions($prefix, $js)
    {
        $jsScript = $this->_renderCreateConstraints();

        $jsScript .= "document.formConstraints.{$prefix} = {{$js}};";

        $jsScript .= $this->_renderFormCreatedEvent($prefix);

         return $this->_wrapInJsClosure($jsScript);
    }

    /**
     * @return string
     */
    private function _renderCreateConstraints()
    {
        return "if(typeof document.formConstraints === 'undefined'){document.formConstraints = {};}";
    }

    /**
     * Render event script
     * @param $prefix
     * @return string
     */
    private function _renderFormCreatedEvent($prefix)
    {
        return "document.dispatchEvent(new CustomEvent('{$this->_jsEvent}', { detail: '{$prefix}' }));";
    }

    private function _wrapInJsClosure($js)
    {
        return  "(() => {{$js}})();";
    }

    /**
     * Create js script for the form client validation
     * @param $prefix
     * @param $js
     * @return string
     */
    private function _createClienValidationScript($prefix, $js)
    {
        $js = $this->_wrapClientValidationConditions($prefix, $js);
        return "<script>{$js}</script>";
    }

}