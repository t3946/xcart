<?php

namespace Modules\Order\Models;

use Modules\Core\Models\TelephoneAreaModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\Helpers\BaseFraudCheckHelperV2;
use Modules\Order\Helpers\FraudCheckHelper;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Helpers\PhoneHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property mixed importance_factor
 * @property mixed auto
 * @property mixed question_template_body
 * @property mixed question_code
 */
class FraudCheckModel extends Model
{
    use AutoMetaTrait;

    private $result;
    private $order_fraud_model;

    public static function tableName()
    {
        return 'xcart_fraud_check';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'question_template_body' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ]
        ];
    }

    public function getCompiledBody(OrderModel $order): string
    {
        $question_template_body = $geoip_state = $phone_area_code_state = $areacode_state = $geoip_address = $phone_area_code_address = $google_phone_name = $google_email_name = '';
        if ($app = Xcart::app()) {
            $oPaymentMethod = null;
            $google_billing_address = $google_shipping_address = '';
            $phone = $order->phone . ($order->phone_ext ? " ext {$order->phone_ext}" : '');
            if ($address = $order->getAddressInfo()) {
                foreach ($address as $key => $a) {
                    $addr = $a['address'][0] . (!empty($a['address'][1]) ? " {$a['address'][1]}" : '') . " {$a['city']} {$a['state']} {$a['zipcode']}";
                    $addr = str_replace([' ', '#', '&'], ['+', '', 'and'], $addr);
                    if ($key) {
                        $google_billing_address = $addr;
                    } else {
                        $google_shipping_address = $addr;
                    }
                }
            }
            $fraud_Google_address_search_exclusions = $fraud_Google_phone_search_exclusions = $fraud_Google_email_search_exclusions = '';
            /** @var SiteModel $site */
            $site = $app->getModule('Sites')->getSite();
            $config = $site->getGlobalConfig();
            foreach (['fraud_Google_address_search_exclusions', 'fraud_Google_phone_search_exclusions', 'fraud_Google_email_search_exclusions'] as $exclusion) {
                if ($$exclusion = trim($config[$exclusion])) {
                    $$exclusion = str_replace([',', ' '], ['+-', '+'], $$exclusion);
                    $$exclusion = "+-{$$exclusion}";
                }
            }

            [$userinfo_area_code, $google_phone] = PhoneHelper::getGooglePhone($order->phone, $order->phone_ext);
            /** @var OrderTransactionModel $oTransaction */
            $oTransaction = $this->getFirstTransaction($order);
            if ($geo_litecity_location = $order->getGeoLocation()) {
                $geoip_state = $geo_litecity_location->region;
                $geoip_address = $geo_litecity_location;
            }
            if ($telephoneModel = TelephoneAreaModel::objects()->get(['area_code' => $userinfo_area_code])) {
                $areacode_state = $telephoneModel->state_code;
                $phone_area_code_state = "{$telephoneModel->area} ({$telephoneModel->state_code})";
                $phone_area_code_address = "{$telephoneModel->country_code}, {$telephoneModel->state_code}, {$telephoneModel->area}";
            }
            $google_name_s = $google_name_b = '';
            foreach (['Google shipping address' => [$google_shipping_address, 's'], 'Google billing address' => [$google_billing_address, 'b']] as $address_key => [$add_link, $prefix]) {
                $unique_names = [];
                $pn = "google_name_{$prefix}";
                $$pn = '';
                foreach (['Contact Last name' => $order->firstname, 'Shipping Last name' => $order->s_firstname, 'Billing Last name' => $order->b_firstname] as $key => $name) {
                    if ($name && !in_array($name, $unique_names, true)) {
                        $unique_names[] = $name;
                        $name_c = str_replace([' ', '#', '&'], ['+', '', 'and'], $name);
                        $$pn .= <<<HTML
{$name} <a target="_blank" href="https://www.google.com/search?q={$add_link}+{$name_c}{$fraud_Google_address_search_exclusions}" style="color: #1F08F8;">{$address_key} + {$key}</a>
HTML;
                    }
                }
            }
            $google_company_s = $google_company_b = '';
            foreach (['Google shipping address' => [$google_shipping_address, 's'], 'Google billing address' => [$google_billing_address, 'b']] as $address_key => [$add_link, $prefix]) {
                $unique_names = [];
                $pn = "google_company_{$prefix}";
                $$pn = '';
                foreach (['Shipping Company name' => $order->s_company, 'Billing Company name' => $order->b_company] as $key => $name) {
                    if ($name && !in_array($name, $unique_names, true)) {
                        $unique_names[] = $name;
                        $name_c = str_replace([' ', '#', '&'], ['+', '', 'and'], $name);
                        $$pn .= <<<HTML
{$name} <a target="_blank" href="https://www.google.com/search?q={$add_link}+{$name_c}{$fraud_Google_address_search_exclusions}" style="color: #1F08F8;">{$address_key} + {$key}</a>
HTML;
                    }
                }
            }

            foreach (['Google phone' => $google_phone] as $address_key => $add_link) {
                $unique_names = [];
                foreach (['Contact Last name' => $order->firstname, 'Shipping Last name' => $order->s_firstname, 'Billing Last name' => $order->b_firstname] as $key => $name) {
                    if ($name && !in_array($name, $unique_names, true)) {
                        $unique_names[] = $name;
                        $name_c = str_replace([' ', '#', '&'], ['+', '', 'and'], $name);
                        $google_phone_name .= <<<HTML
{$name} <a target="_blank" href="https://www.google.com/search?q={$add_link}+{$name_c}" style="color: #1F08F8;">{$address_key} + {$key}</a>
HTML;
                    }
                }
            }

            foreach (['Google email' => $order->email] as $address_key => $add_link) {
                $unique_names = [];
                foreach (['Contact Last name' => $order->firstname, 'Shipping Last name' => $order->s_firstname, 'Billing Last name' => $order->b_firstname] as $key => $name) {
                    if ($name && !in_array($name, $unique_names, true)) {
                        $unique_names[] = $name;
                        $name_c = str_replace([' ', '#', '&'], ['+', '', 'and'], $name);
                        $google_email_name .= <<<HTML
{$name} <a target="_blank" href="https://www.google.com/search?q={$add_link}+{$name_c}" style="color: #1F08F8;">{$address_key} + {$key}</a>
HTML;
                    }
                }
            }

            $email_domain_website = <<<HTML
<a target="_blank" href="//www.{$order->getEmailDomain()}" style="color: #1F08F8;">www.{$order->getEmailDomain()}</a>
HTML;
            $google_shipping_l = <<<HTML
<a target="_blank" href="https://www.google.com/search?q={$google_shipping_address}{$fraud_Google_address_search_exclusions}" style="color: #1F08F8;">Google shipping address</a>
HTML;
            $google_bill_l = <<<HTML
<a target="_blank" href="https://www.google.com/search?q={$google_billing_address}{$fraud_Google_address_search_exclusions}" style="color: #1F08F8;">Google billing address</a>
HTML;
            $google_email = <<<HTML
<a target="_blank" href="https://www.google.com/search?q={$order->email}{$fraud_Google_email_search_exclusions}" style="color: #1F08F8;">Google email</a>
HTML;
            $google_phone_l = <<<HTML
<a target="_blank" href="https://www.google.com/search?q={$google_phone}{$fraud_Google_phone_search_exclusions}" style="color: #1F08F8;">Google phone</a>
HTML;
            $fs_phone = substr($order->phone, 0, 3) . '-' . substr($order->phone, 3, 3) . '-' . substr($order->phone, 6);
            $fs_phone = <<<HTML
<a target="_blank" href="https://www.fastpeoplesearch.com/{$fs_phone}" style="color: #1F08F8;">Fast People Search phone</a>
HTML;
            $fs_address = str_replace(' ', '-', $order->s_address);
            $fastpeoplesearch_shipping = <<<HTML
<a target="_blank" href="https://www.fastpeoplesearch.com/address/{$fs_address}_{$order->s_zipcode}" style="color: #1F08F8;">Fast People Search shipping address</a>
HTML;
            $fb_address = str_replace(' ', '-', $order->b_address);
            $fastpeoplelink = <<<HTML
<a target="_blank" href="https://www.fastpeoplesearch.com/address/{$fb_address}_{$order->b_zipcode}" style="color: #1F08F8;">Fast People Search billing address</a>
HTML;
            $ws_address = str_replace(' ', '+', "{$order->s_address} {$order->s_city} {$order->s_state}");
            $whitepages_shipping = <<<HTML
<a target="_blank" href="https://premium.whitepages.com/results/address/?type=person_address_query&address={$ws_address}" style="color: #1F08F8;">Whitepages Search shipping address</a>
HTML;
            $wb_address = str_replace(' ', '+', "{$order->b_address} {$order->b_city} {$order->b_state}");
            $whitepages_billing = <<<HTML
<a target="_blank" href="https://premium.whitepages.com/results/address/?type=person_address_query&address={$wb_address}" style="color: #1F08F8;">Whitepages Search billing address</a>
HTML;
            $sTransactionReplaceText = '';
            $sPaymentMethodReplaceText = '';
            if ($oTransaction && $oPaymentMethod = PaymentMethodModel::objects()->get(['paymentid' => $oTransaction->paymentid])) {
                $sTransactionLink = str_replace('{{trans-id}}', $oTransaction->transaction_id, $oPaymentMethod->transaction_id_link);
                $sTransactionReplaceText = "<a target='_blank' href='{$sTransactionLink}' style='color:#1F08F8;'>Link to transaction</a>";
                $sPaymentMethodReplaceText = "{$oPaymentMethod->payment_method} ({$oPaymentMethod->transaction_link_anchor})";
            }
            $google_shipping_link = <<<HTML
<a target="_blank" href="http://www.spokeo.com/search?q={$google_shipping_address}" style="color: #1F08F8;">Spokeo shipping address</a>
HTML;
            $google_billing_link = <<<HTML
<a target="_blank" href="http://www.spokeo.com/search?q={$google_billing_address}" style="color: #1F08F8;">Spokeo billing address</a>
HTML;
            $orders_full_names = "{$order->s_firstname}<br />{$order->b_firstname}<br />{$order->firstname}";
            $orders_company_names = "{$order->s_company}<br />{$order->b_company}";

            if ($aProductLinks = BaseFraudCheckHelperV2::getProductList($order)) {
                $links_to_ordered_products = implode('<br>', $aProductLinks);
            }

            $question_template_body = str_replace(
                ['{{customer_phone}}', '{{google_shipping}}', '{{google_billing}}', '{{google_email}}', '{{google_phone}}', '{{fastpeoplesearch_phone}}', '{{fastpeoplesearch_shipping}}', '{{emails_domain}}', '{{link_to_paypal_transaction}}', '{{payment_method}}', '{{spokeo_shipping}}', '{{spokeo_billing}}', '{{links_to_ordered_products}}', '{{billing_address}}', '{{shipping_address}}', '{{orders_full_names}}', '{{shipping_state}}', '{{billing_state}}', '{{geoip_state}}', '{{phone_area_code_state}}', '{{customer_email}}', '{{areacode_state}}', '{{geoip_address}}', '{{phone_area_code_address}}', '{{orders_company_names}}', '{{email_domain_website}}', '{{fastpeoplesearch_billing}}', '{{google_name_s}}', '{{google_name_b}}', '{{google_company_s}}', '{{google_company_b}}', '{{whitepages_shipping}}', '{{whitepages_billing}}', '{{google_phone_name}}', '{{google_email_name}}'],
                [$phone, $google_shipping_l, $google_bill_l, $google_email, $google_phone_l, $fs_phone, $fastpeoplesearch_shipping, "@{$order->getEmailDomain()}", $sTransactionReplaceText, $sPaymentMethodReplaceText, $google_shipping_link, $google_billing_link, $links_to_ordered_products ?? '', $order->getBillingAddressString(), $order->getShippingAddressString(), $orders_full_names, $order->s_state, $order->b_state, $geoip_state, $phone_area_code_state, $order->email, $areacode_state, $geoip_address, $phone_area_code_address, $orders_company_names, $email_domain_website, $fastpeoplelink, $google_name_s, $google_name_b, $google_company_s, $google_company_b, $whitepages_shipping, $whitepages_billing, $google_phone_name, $google_email_name], $this->question_template_body);
        }

        return $question_template_body;
    }



    public function getImportanceFactor()
    {
        return explode(',', str_replace(' ', '', $this->importance_factor));
    }

    public function getScore(OrderModel $order, $recalc = true)
    {
        if ($result = $this->getMethodResult($order, $recalc)) {
            [$fraud_result, $bare_fraud_score] = $result;
            $importance_factor_arr = $this->getImportanceFactor();
            switch ($fraud_result) {
                case 'negative':
                    $selected_importance_factor = $importance_factor_arr[0];
                    break;
                case 'positive':
                    $selected_importance_factor = $importance_factor_arr[2];
                    break;
                default:
                    $selected_importance_factor = $importance_factor_arr[1];
            }
            return round($bare_fraud_score * $selected_importance_factor, 2);
        }
        return null;
    }

    public function isPaypalPayment(OrderModel $order): bool
    {
        /** @var OrderTransactionModel $oTransaction */
        if ($oTransaction = $this->getFirstTransaction($order)) {
            return ($oTransaction->payment_method_model->frontend_processor->processor_name === 'PayPal');
        }
        return false;
    }

    public function getMethodResult(OrderModel $order, $recalc = true)
    {
        if ($this->result === null) {
            if (!$recalc && $order_fraud = OrderFraudCheckModel::objects()->get(['orderid' => $order, 'question_code' => $this->question_code])) {
                $this->result = [$order_fraud->fraud_result, $order_fraud->bare_fraud_score, $order_fraud->additional_info, $order_fraud->manual_action];
                return $this->result;
            }
            $method = "score{$this->question_code}";
            if (method_exists(FraudCheckHelper::class, $method)) {
                $this->result = FraudCheckHelper::$method($order, $this);
            }
        }
        return $this->result;
    }

    public function getFirstTransaction(OrderModel $order)
    {
        if ($log = $order->transactions_log->limit(1)->order(['date'])->get()) {
            return $log->transaction;
        }
        return $order->transactions->limit(1)->order(['-date'])->get();
    }

    public function getOrderFraudCheck($orderModel)
    {
        if ($this->order_fraud_model === null) {
            $this->order_fraud_model = OrderFraudCheckModel::objects()->get(['orderid' => $orderModel->orderid, 'question_code' => $this->question_code]);
        }
        return $this->order_fraud_model;
    }

    public function getManualAction(OrderModel $order):? string
    {
        if (($this->auto !== 'Y') && $of = $this->getOrderFraudCheck($order)) {
            return $of->manual_action;
        }
        return null;
    }

    public function getResponse(OrderModel $order)
    {
        if (Xcart::app()->template->exists($template = "fraud_check/{$this->question_code}.tpl")) {
            [,,$add] = $this->getMethodResult($order, false);
            return Xcart::app()->template->render($template, ['item' => $this, 'additional_info' => $add]);
        }
        return '';
    }

}