<?php

namespace Modules\Payment\Helpers;


use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTransactionModel;
use Omnipay\Common\CreditCard;

class PaymentHelper
{
    public static function getCardHolderName($fullName)
    {
        preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#', trim($fullName), $results);
        return $results;
    }

    /**
     * @param $input
     * @param OrderModel $model
     * @return array
     */
    public static function prepareAuthorize($input, $model)
    {
        list(, , $first_name, $last_name) = PaymentHelper::getCardHolderName($input["cardholderl_name"]);
        $params = [
            'amount' => number_format(trim($input["grand_total"]), 2, '.', ''),
            'currency' => $input["currency"],
            'card' => new CreditCard([
                'firstName' => $first_name,
                'billingFirstName' => $first_name,
                'shippingFirstName' => $model->s_firstname,
                'lastName' => $last_name,
                'billingLastName' => $last_name,
                'number' => trim($input["card_number"]),
                'expiryMonth' => trim($input["expiration_month"]),
                'expiryYear' => substr(date("Y"), 0, 2) . trim($input["expiration_year"]),
                'cvv' => trim($input["csc"]),
                'billingAddress1' => trim($input["b_address"]),
                'billingAddress2' => trim($input["b_address_2"]),
                'billingCity' => trim($input["b_city"]),
                'billingState' => trim($input["b_state"]),
                'billingPostcode' => trim($input["b_zipcode"]),
                'billingCountry' => trim($input["b_country"]),
                'email' => $model->email,
                'shippingAddress1' => ($model->s_address) . (!empty($model->s_address_2) ? " " . ($model->s_address_2) : ""),
                'shippingCity' => $model->s_city,
                'shippingPostcode' => $model->s_zipcode,
                'shippingState' => $model->s_state,
                'shippingCountry' => $model->s_country,
            ])];
        return $params;
    }


    /**
     * @param OrderModel $model
     * @return bool
     */
    public static function isAuthorizeAllowed($model, $count)
    {
        if ($count) {
            return true;
        }

        if ($groups = $model->groups) {
            foreach ($groups as $group) {
                if (!in_array($group->cb_status, ['Q', 'N', 'I'])) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function getPaymentParams($orderTransaction, $amount = null)
    {
        if (!$amount) {
            $amount =
                [
                    'amount' => $orderTransaction->transaction_amount,
                    'currency' => $orderTransaction->transaction_currency
                ];
        }
        return array_merge(
            [
                'transactionReference' => $orderTransaction->transaction_id,
                'status' => $orderTransaction->transaction_status,
                'processor' => $orderTransaction->payment_method_model->processor
            ],
            $amount
        );
    }
}