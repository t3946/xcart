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
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function getTemplateFromType($type)
    {

        if ($this->hasBehavior('decor')) {
            $behavior = $this->getBehavior('decor');
            /** @var FormViewBehavior $behavior */
            return $behavior->getTemplateFromType($type);
        }

        return parent::getTemplateFromType($type);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function __toString()
    {

        if ($this->hasBehavior('decor')) {
            /** @var FormViewBehavior $behavior */
            $behavior = $this->getBehavior('decor');

            return $behavior->__toString();
        }

        return parent::__toString();
    }

    /**
     * @param null $template
     * @param array $fields
     * @param null $extra
     * @return string
     * @throws \Exception
     */
    public function render($template = null, array $fields = [], $extra = null)
    {
        if ($this->hasBehavior('decor')) {
            $behavior = $this->getBehavior('decor');
            /** @var FormViewBehavior $behavior */
            return $behavior->render($template, $fields, $extra);
        }

        return parent::render($template, $fields, $extra);
    }

    /**
     * Initialize fields
     */
    public function initFields()
    {
        if ($this->hasBehavior('decor')) {
            $behavior = $this->getBehavior('decor');
            /** @var FormViewBehavior $behavior */
            return $behavior->initFields();
        }
        return parent::initFields();
    }

    /**
     * @param $decorBehavior
     */
    public function setDecor($decorBehavior)
    {
        $this->attachBehavior('decor', $decorBehavior);
    }

    /**
     * Add initialized field
     * @param $name
     * @param $value
     */
    public function addInitField($name, $value)
    {
        $this->_fields[$name] = $value;
    }

}