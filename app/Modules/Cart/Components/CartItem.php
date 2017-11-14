<?php
namespace Modules\Cart\Components;

use Modules\Cart\Interfaces\ICartItem;
use Modules\Cart\Interfaces\IDiscount;
use Serializable;
use Xcart\App\Helpers\Accessors;

class CartItem implements Serializable
{
    use Accessors;

    /**
     * @var \Xcart\App\Orm\Model|\Modules\Cart\Interfaces\ICartItem
     */
    private $_object;
    /**
     * @var string weight type
     */
    private $_type;
    /**
     * @var
     */
    private $_quantity = 1;
    /**
     * @var array
     */
    private $_data = [];
    /**
     * @var float original calculated product price based on price * quantity with custom data. See $_data.
     */
    private $_price;
    /**
     * @var float price with applied discounts
     */
    private $_discountPrice;

    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->{'_' . $key} = $value;
        }
        $this->fetchPrice();
    }

    /**
     * @param $data
     * @return CartItem
     */
    public function setData($data)
    {
        $this->_data = $data;
        return $this->fetchPrice();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param $type
     * @return CartItem
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this->fetchPrice();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param $quantity
     * @return CartItem
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
        return $this->fetchPrice();
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * @param ICartItem $object
     * @return $this
     */
    public function setObject(ICartItem $object)
    {
        $this->_object = $object;
        return $this->fetchPrice();
    }

    /**
     * @return \Xcart\App\Orm\Model|ICartItem
     */
    public function getObject()
    {
        return $this->_object;
    }

    public function recalculate()
    {
        return $this->getObject()->recalculate($this->_quantity, $this->_type, $this->_data);
    }

    /**
     * @return $this
     */
    private function fetchPrice()
    {
        if ($this->getObject()) {
            $this->_price = $this->recalculate();
        }
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return (float)str_replace(',', '', $this->_discountPrice ?: $this->_price);
    }

    public function getDiscountSum()
    {
        if ($this->_discountPrice && $this->_discountPrice > 0) {
            return $this->_price - $this->_discountPrice;
        }

        return 0;
    }

    /**
     * @param Cart $cart
     * @param IDiscount[] $discounts
     */
    public function applyDiscount(Cart $cart, array $discounts)
    {
        foreach ($discounts as $discount) {
            $this->_discountPrice = $discount->applyDiscount($cart, $this);
        }
    }

    public function serialize()
    {
        return serialize([
            'data' => $this->getData(),
            'pk' => $this->_object->getUniqueId(),
            'class' => get_class($this->_object),
            'quantity' => $this->getQuantity()
         ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->_data = $data['data'];
        $this->_quantity = $data['quantity'];

        /** @var \Xcart\App\Orm\Model $class */
        $class = $data['class'];
        $this->_object = $class::objects()->get(['pk' => $data['pk']]);
        $this->fetchPrice();
    }
}
