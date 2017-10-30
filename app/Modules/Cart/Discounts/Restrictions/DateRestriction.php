<?php

namespace Modules\Cart\Discounts\Restrictions;

use Modules\Cart\CartModule;
use Modules\Cart\Interfaces\IDiscountRestriction;

class DateRestriction implements IDiscountRestriction
{
    public function getName()
    {
        return CartModule::t('Date');
    }

    public function getModel()
    {
        // TODO: Implement getModel() method.
    }

    public function getForm()
    {
        // TODO: Implement getForm() method.
    }
}