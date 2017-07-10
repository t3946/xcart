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

        $this->getEventManager()->on('app:end', [$this, 'save']);
    }

    public function getCartNumber()
    {
        $this->getStorage()->getCartNumber();
    }

    public function save()
    {
        $this->getStorage()->save($this->discounts);
    }
}
