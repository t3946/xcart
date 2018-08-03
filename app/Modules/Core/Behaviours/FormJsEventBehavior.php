<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 03.08.2018
 * Time: 10:25
 */

namespace Modules\Core\Behaviours;

use Xcart\App\Form\Fields\Field;
use Xcart\App\Form\FormView\FormViewBehavior;

abstract class FormJsEventBehavior extends FormViewBehavior
{
    /**
     * Head form template
     * @var string
     */
    public $formBeginTemplate = 'forms/frontend/begin_client_validation.tpl';

    /**
     * Name of Js Event
     * @var array
     */
    protected $jsEvent = '';

    /**
     * Result js string
     * @var string
     */
    protected $js = '';

    /**
     * Name of Js object
     * @var string
     */
    protected $jsObjName = '';

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
        $this->js = $this->createScript($prefix, $this->createJsFieldsConditions($fields));
    }

    /**
     * Collect conditions for fields
     * @param $fields
     * @return string
     */
    abstract protected function createJsFieldsConditions(&$fields): string;

    /**
     * Execute before form end render
     * (new settings must override old)
     * @param $prefix
     * @param $template
     */
    public function onBeforeRenderEnd(&$prefix, &$template): void
    {
        echo $this->js;
    }

    /**
     * @return string
     */
    protected function renderCreateJsObj(): string
    {
        return "if(typeof document.{$this->jsObjName} === 'undefined'){document.{$this->jsObjName} = {};}";
    }

    /**
     * Render event script
     * @param $prefix
     * @return string
     */
    protected function renderFormCreatedEvent($prefix): string
    {
        return "document.dispatchEvent(new CustomEvent('{$this->jsEvent}', { detail: '{$prefix}' }));";
    }

    protected function wrapInJsClosure($js): string
    {
        return "(() => {{$js}})();";
    }

    /**
     * Render validation info script
     * @param $prefix
     * @param $js
     * @return string
     */
    protected function wrapClientJsFieldsList($prefix, $js = ''): string
    {

        $jsScript = $this->renderCreateJsObj();

        $jsScript .= "document.{$this->jsObjName}.{$prefix} = {{$js}};";

        $jsScript .= $this->renderFormCreatedEvent($prefix);

        return $this->wrapInJsClosure($jsScript);
    }

    /**
     * Create js script for the form client validation
     * @param $prefix
     * @param $js
     * @return string
     */
    protected function createScript($prefix, $js = ''): string
    {
        $js = $this->wrapClientJsFieldsList($prefix, $js);
        return "<script>{$js}</script>";
    }
}