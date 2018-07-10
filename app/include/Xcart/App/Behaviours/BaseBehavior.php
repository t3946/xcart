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


    public function __construct()
    {
        var_dump(123);

    }

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
    public function init()
    {

        var_dump('init' . $this->name);
        // TODO: Implement init() method.
    }
}