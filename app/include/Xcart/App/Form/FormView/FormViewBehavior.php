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

class FormViewBehavior extends BaseBehavior
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
     * @param $type
     * @return mixed
     * @throws Exception
     */
    public function getTemplateFromType($type)
    {
        $templates = array_merge($this->owner->templates, $this->templates);

        if (array_key_exists($type, $templates)) {
            return $templates[$type];
        }

        throw new Exception("Template type {$type} not found");

    }

    /**
     * @return string
     * @throws Exception
     */
    public function __toString()
    {
        $template = $this->getTemplateFromType($this->defaultTemplateType);
        try {
            return (string)$this->render($template);
        } catch (Exception $e) {
            return (string)$e;
        }
    }

    /**
     * @param null $template
     * @param array $fields
     * @param null $extra
     * @return string
     * @throws Exception
     */
    public function render($template = null, array $fields = [], $extra = null)
    {
        if (!$template) {
            $template = $this->getTemplateFromType($this->defaultTemplateType);
        }

        return $this->owner->setRenderFields($fields)->renderInternal($template, [
            'form' => $this->owner,
            'fields' => $fields ?: $this->owner->getRenderFields(),
            'inlines' => $this->owner->renderInlines($extra)
        ]);
    }

    /**
     * Initialize fields
     */
    public function initFields()
    {
        $prefix = $this->owner->getPrefix();
        $fields = $this->owner->getFields();
        foreach ($fields as $name => $config) {
            if (\in_array($name, $this->owner->getExclude(), true)) {
                continue;
            }

            if (!\is_array($config)) {
                $config = ['class' => $config];
            }

            $newField = Creator::createObject(array_merge([
                'name' => $name,
                'form' => $this->owner,
                'prefix' => $prefix,
            ], $this->fieldsSettings, $config));

            $this->owner->addInitField($name, $newField);
        }
    }



}