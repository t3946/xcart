<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 18.07.2018
 * Time: 16:54
 */

namespace Modules\Core\Behaviours;


use Xcart\App\Form\FormView\FormViewBehavior;

class FormClearInputBehavior extends FormViewBehavior
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
    private $_jsEvent = 'form.client.fields.clear';

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
        $prefix = $this->owner->getFormIdentifier();
        $this->_addFormsListToPage($prefix);
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
     * Render validation info script
     * @param $prefix
     * @return string
     */
    private function _createFormsList($prefix)
    {

        $jsScript = $this->_renderCreateFormsList();

        $jsScript .= "document.formClearFields.{$prefix} = {};";

        $jsScript .= $this->_renderFormCreatedEvent($prefix);

        return $this->_wrapInJsClosure($jsScript);
    }

    /**
     * @return string
     */
    private function _renderCreateFormsList()
    {
        return "if(typeof document.formClearFields === 'undefined'){document.formClearFields = {};}";
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
     * Echo js script for the form client validation
     * @param $prefix
     * @param $js
     */
    private function _addFormsListToPage($prefix)
    {
        $js = $this->_createFormsList($prefix);
        //var_dump($js);
        echo "<script>{$js}</script>";
    }

}