<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 10.07.2018
 * Time: 12:04
 */

namespace Xcart\App\Form\Traits;

use Xcart\App\Form\FormView\FormViewBehavior;

trait FormEventsRenderTrait
{

    /**
     * Execute before form field creation
     * @param $name
     * @param $config
     * @return mixed
     */
    public function onBeforeCreateField(&$name, &$config):void
    {
        $this->_applyEvent('onBeforeCreateField', $array = [&$name, &$config]);
    }

    /**
     * Execute after form field is created
     * @param $field
     */
    public function onAfterCreateField(&$field):void
    {
        $this->_applyEvent('onAfterCreateField', $array = [&$field]);
    }

    /**
     * Execute before choose form template
     * @param $templates
     * @param $defaultTemplateType
     */
    public function onBeforeGetTemplate(&$templates, &$defaultTemplateType):void
    {
        $this->_applyEvent('onBeforeGetTemplate', $array = [&$templates, &$defaultTemplateType]);
    }

    /**
     * Execute before form render
     * @param $fields
     */
    public function onBeforeRender(&$fields):void
    {
        $this->_applyEvent('onBeforeRender', $array = [&$fields]);
    }

    /**
     * Execute before form begin render
     * @param $prefix
     * @param $template
     */
    public function onBeforeRenderBegin(&$prefix, &$template):void
    {
        $this->_applyEvent('onBeforeRenderBegin', $array = [&$prefix, &$template]);
    }

    /**
     * Execute before form end render
     * @param $prefix
     * @param $template
     */
    public function onBeforeRenderEnd(&$prefix, &$template):void
    {
        $this->_applyEvent('onBeforeRenderEnd', $array = [&$prefix, &$template]);
    }

    /**
     * @param $name
     * @param $params
     */
    private function _applyEvent($eventName, &$params):void
    {
        parent::$eventName(... $params);

        if ($this->hasAnyBehavior()) {
            foreach ($this->getAllBehaviors() as $behaviorName => $behavior){
                /** @var FormViewBehavior $behavior */
                if($behavior->enabled) {
                    $behavior->$eventName(... $params);
                }
            }
        }
    }

}