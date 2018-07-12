<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 10.07.2018
 * Time: 12:04
 */

namespace Xcart\App\Form\Traits;

use Xcart\App\Form\FormView\FormViewBehavior;

trait FormBehaviourRenderTrait
{

    /**
     * Execute before create field
     * @param $name
     * @param $config
     * @return mixed
     */
    public function onBeforeCreateField(&$name, &$config):void
    {
        $this->_applyEvent('onBeforeCreateField', $array = [&$name, &$config]);
    }

    /**
     * Execute after field is created
     * @param $field
     */
    public function onAfterCreateField(&$field):void
    {
        $this->_applyEvent('onAfterCreateField', $array = [&$field]);
    }

    /**
     * Execute before choose template
     * @param $templates
     * @param $defaultTemplateType
     */
    public function onBeforeGetTemplate(&$templates, &$defaultTemplateType):void
    {
        $this->_applyEvent('onBeforeGetTemplate', $array = [&$templates, &$defaultTemplateType]);
    }

    /**
     * Execute before render form
     * @param $fields
     */
    public function onBeforeRender(&$fields):void
    {
        $this->_applyEvent('onBeforeRender', $array = [&$fields]);
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