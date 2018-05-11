<?php

namespace Modules\Cart\Components;

use Xcart\App\Helpers\Accessors;
use Modules\Cart\Interfaces\IDiscount;
use Modules\Cart\Interfaces\ICartItem;
use Xcart\App\Helpers\Creator;
use Xcart\App\Main\Xcart;
use Xcart\App\Traits\Configurator;

class Cart
{
    use Accessors, Configurator;

    /**
     * @var string|array component configuration
     */
    public $storageConfig = [
        'class' => SessionStorage::class
    ];
    public $storageKey = 'cart';
    /**
     * @var IDiscount[]
     */
    public $discounts = [];
    /**
     * @var bool force renew object when call getItems() method
     */
    public $forceFetch = false;
    /**
     * @var \Modules\Cart\Components\SessionStorage
     */
    private $_storage;
    /**
     * @var IDiscount[]
     */
    private $_discounts;

    public function init()
    {
        $signal = $this->getEventManager();
        $signal->on('cart:addItem', [$this, 'onAddItem']);
        $signal->on('cart:removeItem', [$this, 'onRemoveItem']);
        $signal->on('cart:change', [$this, 'onChange']);
    }

    public function getEventManager(): \Xcart\App\Event\EventManager
    {
        return Xcart::app()->event;
    }

    public function onAddItem($item): void {}

    public function onRemoveItem($item): void {}

    public function onChange(): void {}

    /**
     * @return \Modules\Cart\Interfaces\ICartStorage
     */
    public function getStorage(): \Modules\Cart\Interfaces\ICartStorage
    {
        if ($this->_storage === null) {
            $this->_storage = Creator::createObject($this->storageConfig, $this, $this->storageKey);
        }
        return $this->_storage;
    }

    /**
     * @param ICartItem $object
     * @param array $data
     * @return string
     */
    public function makeKey(ICartItem $object, array $data = []): string
    {
        $prefix = '';
        $clss = \get_class($object);
        $clss = explode('\\', $clss);

        foreach ($clss as $cls) {
            $prefix .= $cls[0];
        }
        $prefix .= $cls[\strlen($cls) - 1];

        return strtr('{prefix}{unique_id}{data}', [
            '{prefix}' => $prefix,
            '{unique_id}' => $object->getUniqueId(),
            '{data}' => (empty($data)?:'-') . implode('-', $data),
        ]);
    }

    /**
     * @param ICartItem $object
     * @param array $data
     * @return CartItem|null
     */
    public function get(ICartItem $object, array $data = []): ?CartItem
    {
        return $this->getStorage()->get($this->makeKey($object, $data));
    }

    /**
     * @param ICartItem $object
     * @param int $quantity
     * @param null $type
     * @param array $data
     * @return $this
     */
    public function add(ICartItem $object, $quantity = 1, $type = null, array $data = []): self
    {
        $key = $this->makeKey($object, $data);
        if ($this->has($object, $data)) {
            $oldItem = $this->get($object, $data);
            $item = new CartItem([
                'object' => $oldItem->object,
                'data' => $oldItem->data,
                'quantity' => $oldItem->quantity + $quantity,
                'type' => $type,
            ]);

            $this->getStorage()->remove($key);
        }
        else {
            $item = new CartItem([
                'object' => $object,
                'data' => $data,
                'quantity' => $quantity,
                'type' => $type,
            ]);
        }

        $item->applyDiscount($this, $this->getDiscounts());
        $this->getStorage()->add($key, $item);
        return $this;
    }

    /**
     * @param $key
     * @return null
     */
    public function getPositionByKey($key)
    {
        return $this->getStorage()->has($key)?$key:null;
    }

    /**
     * @param $key
     * @param $quantity
     * @return bool
     */
    public function updateQuantityByKey($key, $quantity): bool
    {
        $positionKey = $this->getPositionByKey($key);
        if ($positionKey) {
            $item = $this->getStorage()->get($positionKey);
            $item->setQuantity($quantity);
            $item->applyDiscount($this, $this->getDiscounts());
            $this->getStorage()->add($positionKey, $item);
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function increaseQuantityByKey($key): bool
    {
        $positionKey = $this->getPositionByKey($key);
        if ($positionKey) {
            $item = $this->getStorage()->get($positionKey);
            $item->setQuantity($item->getQuantity() + 1);
            $item->applyDiscount($this, $this->getDiscounts());
            $this->getStorage()->add($positionKey, $item);
            return true;
        }
        return false;
    }

    /**
     * @param ICartItem $object
     * @param array $data
     * @return bool
     */
    public function increaseQuantity(ICartItem $object, array $data = []): bool
    {
        $item = $this->get($object, $data);
        if ($item) {
            $item->setQuantity($item->getQuantity() + 1);
            $item->applyDiscount($this, $this->getDiscounts());
            $key = $this->makeKey($object, $data);
            $this->getStorage()->add($key, $item);
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function decreaseQuantityByKey($key): bool
    {
        $positionKey = $this->getPositionByKey($key);
        if ($positionKey) {
            $item = $this->getStorage()->get($positionKey);
            if ($item->getQuantity() > 1) {
                $item->setQuantity($item->getQuantity() - 1);
                $item->applyDiscount($this, $this->getDiscounts());
                $this->getStorage()->add($positionKey, $item);
            } else {
                $this->removeByKey($key);
            }
            return true;
        }
        return false;
    }

    /**
     * @param ICartItem $object
     * @param array $data
     * @return bool
     */
    public function decreaseQuantity(ICartItem $object, array $data = []): bool
    {
        $item = $this->get($object, $data);
        if ($item) {
            if ($item->getQuantity() > 1) {
                $item->setQuantity($item->getQuantity() - 1);
                $item->applyDiscount($this, $this->getDiscounts());
                $key = $this->makeKey($object, $data);
                $this->getStorage()->add($key, $item);
            } else {
                $this->remove($object);
            }
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function removeByKey($key): bool
    {
        $key = $this->getPositionByKey($key);
        return $key === null ? false : $this->getStorage()->remove($key);
    }

    /**
     * @param ICartItem $object
     * @param array $data
     * @return bool
     */
    public function remove(ICartItem $object, array $data = []): bool
    {
        return $this->getStorage()->remove($this->makeKey($object, $data));
    }

    /**
     * @param ICartItem $object
     * @param array $data
     * @return bool
     */
    public function has(ICartItem $object, array $data = []): bool
    {
        $key = $this->makeKey($object, $data);
        return $this->getStorage()->has($key);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->getStorage()->clear();
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        $quantity = 0;
        foreach ($this->getItems() as $item) {
            $quantity += $item->quantity;
        }
        return $quantity;
    }

    /**
     * @return float|int
     */
    public function getTotal()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getPrice();
        }

        return $total;
    }

    public function getDiscountSum()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getDiscountSum();
        }

        return $total;
    }

    /**
     * @return \Modules\Cart\Components\CartItem[]
     */
    public function getItems(): array
    {
        $items = $this->getStorage()->getData();
        if ($this->forceFetch) {
            $newItems = [];
            foreach ($items as $key =>$item) {
                $object = $item->getObject();
                if ($newObject = $object->objects()->get(['pk' => $object->pk])) {
                    $item->setObject($newObject);
                    $newItems[$key] = $item;
                } else {
                    $this->remove($object, []);
                }
            }
            $items = $newItems;
        }
        return $items;
    }

    /**
     * @return bool
     */
    public function getIsEmpty()
    {
        return $this->getStorage()->count() === 0;
    }

    public function applyDiscount(ICartItem $object, $quantity = 1, $type = null, array $data = [])
    {
        $item = new CartItem([
            'quantity' => $quantity,
            'type' => $type,
            'data' => $data,
            'object' => $object
        ]);
        $item->applyDiscount($this, $this->getDiscounts());
        return $item->getPrice();
    }

    public function getDiscounts(): ?array
    {
        if ($this->_discounts === null) {
            $this->_discounts = [];

            $this->setDiscounts($this->discounts);
        }

        return $this->_discounts;
    }

    /**
     * @param IDiscount[] $discounts
     */
    public function setDiscounts(array $discounts = []): void
    {
        foreach ($discounts as $discount) {
            if (\in_array(IDiscount::class, class_implements($discount), true)) {
                $this->setDiscount($discount);
            }
        }
    }

    public function setDiscount(IDiscount $discount): void
    {
        if (\is_object($discount)) {
            $this->_discounts[] = $discount;
        }
        else if (\is_string($discount)) {
            $this->_discounts[] = Creator::createObject($discount);
        }
    }
}
