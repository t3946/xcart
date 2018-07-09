<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 15:53
 */

namespace Xcart\App\Behaviours\Interfaces;


interface IObjectBehavior
{
    public function attachBehaviour($name, $behavior);

    public function detachBehaviour($name);

}