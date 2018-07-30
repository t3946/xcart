<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 15:40
 */

namespace Xcart\App\Behaviours;


//use Xcart\App\Form\Interfaces\IObjectBehavior;

use Xcart\App\Behaviours\Interfaces\IBehavior;

class BaseBehavior implements IBehavior
{
    public $owner;

    public $enabled;

    public $name;


    /**
     * @return mixed
     */
    public function init()
    {
        // Implement init() method.
    }

    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name);
    }

    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}