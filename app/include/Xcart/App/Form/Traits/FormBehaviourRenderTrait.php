<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 10.07.2018
 * Time: 12:04
 */

namespace Xcart\App\Form\Traits;

use Xcart\App\Form\FormView\FormViewBehavior;
use Xcart\App\Helpers\Creator;

trait FormBehaviourRenderTrait
{

    public function getTemplateFromType($type)
    {

        if ($this->hasBehavior('decor')) {
            $behavior = $this->getBehavior('decor');
            /** @var FormViewBehavior $behavior */
            return $behavior->getTemplateFromType($type);
        }

        return parent::getTemplateFromType($type);

//        if (array_key_exists($type, $this->templates)) {
//            $template = $this->templates[$type];
//        } else {
//            throw new Exception("Template type {$type} not found");
//        }
//        return $template;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {

        if ($this->hasBehavior('decor')) {
            /** @var FormViewBehavior $behavior */
            $behavior = $this->getBehavior('decor');

            return $behavior->__toString();
        }

        return parent::__toString();

//        $template = $this->getTemplateFromType($this->defaultTemplateType);
//        try {
//            return (string)$this->render($template);
//        } catch (Exception $e) {
//            return (string)$e;
//        }
    }

    /**
     * @param $template
     * @param array $fields
     * @param null|int $extra count of the extra inline forms for render
     * @return string
     */
    public function render($template = null, array $fields = [], $extra = null)
    {
        if ($this->hasBehavior('decor')) {
            $behavior = $this->getBehavior('decor');
            /** @var FormViewBehavior $behavior */
            return $behavior->render($template, $fields, $extra);
        }

        return parent::render($template, $fields, $extra);

//        if (!$template) {
//            $template = $this->getTemplateFromType($this->defaultTemplateType);
//        }
//
//        return $this->setRenderFields($fields)->renderInternal($template, [
//            'form' => $this,
//            'fields' => $fields ?: $this->getRenderFields(),
//            'inlines' => $this->renderInlines($extra)
//        ]);
    }

    /**
     * Initialize fields
     */
//    public function initFields()
//    {
//        if ($this->hasBehavior('decor')) {
//            $behavior = $this->getBehavior('decor');
//            /** @var FormViewBehavior $behavior */
//            return $behavior->initFields();
//        }
//
//        dd(parent::initFields());
//
//    }


    /**
     * Initialize fields
     */
    public function initFields()
    {

        $addConfig = [];
        if ($this->hasBehavior('decor')) {
            $behavior = $this->getBehavior('decor');
            /** @var FormViewBehavior $behavior */
            $addConfig = $behavior->fieldsSettings;
        }


        $prefix = $this->getPrefix();
        $fields = $this->getFields();
        foreach ($fields as $name => $config) {
            if (\in_array($name, $this->getExclude(), true)) {
                continue;
            }

            if (!\is_array($config)) {
                $config = ['class' => $config];
            }

            $this->_fields[$name] = Creator::createObject(array_merge([
                'name' => $name,
                'form' => $this,
                'prefix' => $prefix,
            ], $config, $addConfig));
        }
    }

    public function setDecor($decorBehavior)
    {
        $this->attachBehavior('decor', $decorBehavior);
    }

//    public function addInitField($name, $value)
//    {
//        $this->_fields[$name] = $value;
//    }

}