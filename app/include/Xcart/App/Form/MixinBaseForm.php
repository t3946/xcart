<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 30.07.2018
 * Time: 18:31
 */

namespace Xcart\App\Form;


use Xcart\App\Behaviours\Traits\BehaviorTrait;

class MixinBaseForm extends BaseForm
{
    use BehaviorTrait;

    public function ensureBehaviors()
    {
        if ($this->_behaviors === null) {
            $this->applyDefaultBehaviors();
        }
    }

    public function __get($name)
    {
        if ($this->hasField($name)) {
            return $this->_fields[$name];
        }

        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canGetProperty($name)) {
                return $behavior->$name;
            }
        }

        return $this->__getInternal($name);
    }

    public function __set($name, $value)
    {
        if ($this->hasField($name)) {
            $this->_fields[$name]->setValue($value);
        }

        $this->ensureBehaviors();

        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canSetProperty($name)) {
                $behavior->$name = $value;
                return;
            }
        }

        $this->__setInternal($name, $value);
    }

    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }
        // behavior property
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canGetProperty($name)) {
                return $behavior->$name !== null;
            }
        }
        return false;
    }

    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
            return;
        }
        // behavior property
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->canSetProperty($name)) {
                $behavior->$name = null;
                return;
            }
        }
        //throw new InvalidCallException('Unsetting an unknown or read-only property: ' . get_class($this) . '::' . $name);
    }

    public function __call($method, $parameters)
    {
        $this->ensureBehaviors();
        foreach ($this->_behaviors as $name => $behaviour) {
            if (method_exists($behaviour, $method)) {
                return call_user_func_array([$behaviour, $method], $parameters);
            }
        }
    }
}