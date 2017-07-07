<?php

namespace Modules\Cart\Components;

class XCart extends Cart
{
    public $storageConfig = [
//        'class' => '\Modules\Cart\Components\XSessionStorage,
        'class' => '\Modules\Cart\Components\XTableStorage',
    ];

    public function init()
    {


        parent::init();
    }
}
