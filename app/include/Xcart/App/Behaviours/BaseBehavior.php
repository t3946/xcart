<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 15:40
 */

namespace Xcart\App\Behaviours;


//use Xcart\App\Form\Interfaces\IObjectBehavior;

class BaseBehavior
{
    protected $_owner;

    protected $_enabled;

    protected $_name;

//    public function __construct()
//    {
//        var_dump(123);
//        exit;
//    }

//    public function __construct($owner, $params)
//    {
//        if(!($owner instanceof IObjectBehavior)) {
//            return;
//        }
//        $this->owner = $owner;
//    }

//    public function __destruct()
//    {
//        $this->owner = null;
//    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->_owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->_owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->_enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->_name = $name;
    }
}