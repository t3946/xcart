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
    public function getName();

    /**
     * @return mixed
     */
    public function setName();

    /**
     * @return mixed
     */
    public function getOwner();

    /**
     * @return mixed
     */
    public function setOwner();

    /**
     * @return boolean whether this behavior is enabled
     */
    public function getEnabled();

    /**
     * @param boolean $value whether this behavior is enabled
     */
    public function setEnabled($value);
}
