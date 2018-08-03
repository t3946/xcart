<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 10.07.2018
 * Time: 14:12
 */

namespace Xcart\App\Form\FormView;


use Exception;
use Xcart\App\Behaviours\BaseBehavior;
use Xcart\App\Form\BaseForm;
use Xcart\App\Helpers\Creator;

abstract class FormViewBehavior extends BaseBehavior
{
    /**
     * @var array
     */
    protected $templates = [];

    /**
     * @var BaseForm
     */
    public $owner;

    /**
     * @var string
     */
    protected $defaultTemplateType = 'default';

    /**
     * @var array
     */
    protected $fieldsSettings = [];

    /**
     * @var bool
     */
    public $enabled = false;


    /**
     * @return mixed|void
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        if(!($this->owner instanceof BaseForm)){
            throw new Exception("Owner of behavior is incorrect");
        }
    }

    /**
     * Execute before create field
     * @param $name
     * @param $config
     * @return mixed
     */
    public function onBeforeCreateField(&$name, &$config)
    {
        //$config = array_merge($this->fieldsSettings, $config);
    }

    /**
     * Execute after field is created
     * @param $field
     */
    public function onAfterCreateField(&$field)
    {
        //$field->createClientValidationConfig();
    }

    /**
     * Execute before choose template
     * @param $templates
     * @param $defaultTemplateType
     */
    public function onBeforeGetTemplate(&$templates, &$defaultTemplateType)
    {
        //$defaultTemplateType = $this->defaultTemplateType;
        //$templates = array_merge($templates, $this->templates);
    }

    /**
     * Execute before render form
     * @param $fields
     */
    public function onBeforeRender(&$fields):void
    {
    }

    /**
     * Execute before form end render
     * @param $prefix
     * @param $template
     */
    public function onBeforeRenderEnd(&$prefix, &$template):void
    {
    }





}