<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 31.10.17
 * Time: 20:52
 */

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\Interfaces\IDiscountRestriction;
use Xcart\App\Helpers\ClassNames;

abstract class AbstractRestriction implements IDiscountRestriction
{
    use ClassNames;

    public $data = [];

    abstract public function getFormClass();

    public function setData($data) {
        $this->data = $data;
    }
}