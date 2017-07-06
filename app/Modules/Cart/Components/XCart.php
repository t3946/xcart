<?php

namespace Modules\Cart\Components;

class XCart extends Cart
{
    public $storageConfig = [
        'class' => '\Modules\Cart\Components\XSessionStorage'
    ];
}
