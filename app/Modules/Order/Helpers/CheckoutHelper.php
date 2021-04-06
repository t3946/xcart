<?php


namespace Modules\Order\Helpers;


use Exception;
use Modules\Cart\Components\XCart;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;

class CheckoutHelper
{
    /**
     * @param OrderModel $order
     * @param array $shipping_rates
     * @param int|null $selected_rate
     * @throws Exception
     */
    public static function updateOrderShippingRates(OrderModel $order, array $shipping_rates, int $selected_rate = null): void
    {
        $order->shipping_cost = 0;

        foreach ($order->groups as $group) {
            if (isset($shipping_rates[$group->manufacturerid])) {
                if ($selected_rate) {
                    $shipping_rate = $shipping_rates[$group->manufacturerid][$selected_rate] ?? null;
                } else {
                    $order_rate = array_values(array_filter($shipping_rates[$group->manufacturerid], static fn($a) => (int)$a->shippingid === (int)$group->shippingid));
                    $shipping_rate = $order_rate ? $order_rate[0] : reset($shipping_rates[$group->manufacturerid]);
                }

                if ($shipping_rate) {
                    $charge = $shipping_rate->getShippingCharge();

                    $group->setAttributes([
                        'shippingid' => $shipping_rate->shippingid,
                        'shipping' => $shipping_rate->shipping->getFrontendName(),
                        'shipping_quote' => $shipping_rate->getShippingQuote(),
                        'shipping_gross' => $charge,
                        'shipping_net' => $charge,
                    ]);
                    $group->save();
                }

                $order->shipping_cost += $group->shipping_gross;
            }
        }
        self::updateOrderTotalValues($order);
    }

    public static function updateOrderGroupsFromCart(OrderModel $order, XCart $cart): void
    {
        $cart_groups = $cart->getItemsGroupedBy();
        $order->groups->exclude(['manufacturerid__in' => array_keys($cart_groups)])->delete();

        foreach ($cart_groups as $mid => $cart_group) {
            [$group] = OrderGroupModel::objects()->getOrNew([
                'manufacturerid' => $mid,
                'orderid' => $order->orderid,
            ]);
            $group->setAttributes([
                'total_gross' => $cart_group['subtotal'],
                'total_net' => $cart_group['subtotal'],
                'distributor_price_multiplier' => $group->manufacturer->supplier_products_price_multiplier,
            ]);
            $group->save();
            self::updateOrderDetailsFromCart($group, $cart_group['items']);
        }

        self::updateOrderTotalValues($order);
    }

    /**
     * @param OrderGroupModel $group
     * @param array $items
     */
    public static function updateOrderDetailsFromCart(OrderGroupModel $group, array $items): void
    {
        $updated_details = [];

        foreach ($items as $item) {
            /** @var ProductModel $product */
            $product = $item->getObject();

            [$detail] = OrderDetailModel::objects()->getOrNew([
                'productid' => $product->productid,
                'order_group_id' => $group->order_group_id,
            ]);
            $detail->setAttributes([
                'orderid' => $group->orderid,
                'price' => $product->getFrontendPrice($item->getQuantity()),
                'amount' => $item->getQuantity(),
                'productcode' => $product->productcode,
                'product' => $product->getFrontendName(),
                'provider' => $product->provider,
                'original_provider' => $product->original_provider,
                'item_cost_to_us' => $product->cost_to_us,
                'product_options' => $item->data ?? null,
            ]);
            $detail->save();

            $updated_details[] = $detail->itemid;
        }
        if ($updated_details) {
            $group->detail_models->exclude(['itemid__in' => $updated_details])->delete();
        }
    }

    public static function updateOrderTotalValues(OrderModel $order)
    {
        $order->shipping_cost = 0;
        $order->subtotal = 0;

        foreach ($order->groups as $group)
        {
            $order->shipping_cost += $group->shipping_gross;
            $order->subtotal += $group->total_gross;
            $order->tax += $group->total_tax;
        }

        $order->total = $order->subtotal + $order->shipping_cost;
    }

    public static function updateBillingDetails(OrderModel $order, $form_instance): void
    {
        if ($order->billing_same_shipping === false) {
            $order->setAttributes([
                'b_firstname' => $form_instance->s_firstname,
                'b_company' => $form_instance->s_company,
                'b_address' => $form_instance->s_address,
                'b_country' => $form_instance->s_country,
                'b_zipcode' => $form_instance->s_zipcode,
                'b_state' => $form_instance->s_state,
                'b_city' => $form_instance->s_city,
            ]);
            $order->save();
        }
    }
}