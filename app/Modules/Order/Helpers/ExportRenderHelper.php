<?php


namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderGroupModel;
use Xcart\OrderDetail;

class ExportRenderHelper
{
    public static function export(OrderGroupModel $group): array
    {
        $dx = $group->manufacturer;
        $order = $group->order;
        $name = explode(' ', $order->s_firstname, 2);
        $address = explode("\n", $order->s_address, 2);
        switch ($dx->code) {
            case 'MPP':
                /** @var OrderDetail $detail */
                foreach ($group->detail_models as $detail) {
                    $result[] = [
                        'VendorOrderId' => $order->getOrderNumber(),
                        'ShippingFirstName' => $name[0] ?? '',
                        'ShippingLastName' => $name[1] ?? '',
                        'ShippingCompany' => '',
                        'ShippingAddressLine1' => $address[0],
                        'ShippingAddressLine2' => $address[1] ?? '',
                        'ShippingCity' => $order->s_city,
                        'ShippingState' => $order->s_state,
                        'ShippingZipCode' => $order->s_zipcode,
                        'ShippingCountry' => $order->s_country === 'US' ? 'USA' : $order->s_country,
                        'ProductSku' => $detail->product_model->getMpn(),
                        'ProductQty' => (int) $detail->amount,
                        'ProductBasePrice' => number_format((float) $detail->item_cost_to_us, 2, '.', ''),
                        'VendorCode' => 'S3 STORES',
                        'BillingFullName' => 'S3 STORES',
                        'BillingAddressLine1' => '27 Joseph Street',
                        'BillingAddressLine2' => 'S3 STORES',
                        'BillingCompany' => 'S3 STORES',
                        'BillingCity' => 'Chatham',
                        'BillingState' => 'ON',
                        'BillingZipCode' => 'N7l 3G4',
                        'BillingCountry' => 'CAN',
                    ];
                }
                break;
        }
        return $result ?? [];
    }
}