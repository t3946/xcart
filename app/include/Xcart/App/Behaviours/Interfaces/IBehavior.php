<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 13:42
 */

namespace Xcart\App\Behaviours\Interfaces;


interface IBehavior
{

    /**
     * @return mixed
     */
    public function init();

    public function canGetProperty($name, $checkVars = true);

    public function canSetProperty($name, $checkVars = true);

    public function hasMethod($name);
}
