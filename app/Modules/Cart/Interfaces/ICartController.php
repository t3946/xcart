<?php

namespace Modules\Cart\Interfaces;

interface ICartController
{
    public function addInternal($uniqueId, $quantity = 1);
}
