<?php
namespace Xcart;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\BillingInfo;
use PayPal\Api\Currency;
use PayPal\Api\MerchantInfo;
use PayPal\Api\Phone;
use PayPal\Api\Invoice;
use PayPal\Api\InvoiceAddress;
use PayPal\Api\InvoiceItem;

class Paypal
{
    private $sAccessToken = null;
    private $apiContext = null;

    public function __construct()
    {
        $this->fetchPayPalToken();
    }

    private function fetchPayPalToken()
    {
        global $config;
        $USERPWD_username_ClientId = $config['Paypal_API']['live_client_id'];
        $USERPWD_password_Secret = $config['Paypal_API']['live_secret_key'];
        if ($config['Paypal_API']['debug_mode'] == "Y") {
            $USERPWD_username_ClientId = $config['Paypal_API']['sandbox_client_id'];
            $USERPWD_password_Secret = $config['Paypal_API']['sandbox_secret_key'];
        }
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $USERPWD_username_ClientId,
                $USERPWD_password_Secret
            )
        );
        $this->apiContext->setConfig(
            array(
                'mode' => ($config['Paypal_API']['debug_mode'] == "Y") ? 'sandbox' : 'live',
                'log.LogEnabled' => true,
                'log.FileName' => '../var/log/paypal.log',
                'log.LogLevel' => ($config['Paypal_API']['debug_mode'] == "Y") ? 'DEBUG' : 'INFO',
                'cache.enabled' => true,
                'http.CURLOPT_SSLVERSION' => 1,
                //'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
                //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
            )
        );
        $this->sAccessToken = $this->apiContext->getCredential()->getAccessToken($this->apiContext->getConfig());
        if (empty($this->sAccessToken)) {
            throw new \Exception('Paypal Access Token - failed');
        }
    }

    public function captureTransaction($authorized_transaction_id, $data_arr)
    {
        return func_paypal_capture($this->sAccessToken, $authorized_transaction_id, $data_arr);
    }

    public function sendPaypalRequest($aParams = [])
    {
        $invoice = null;
        if (!empty($aParams['paypal_request_email'])) {
            $invoice = new Invoice();

            $invoice
                ->setMerchantInfo(new MerchantInfo())
                ->setBillingInfo(array(new BillingInfo()))
                ->setNote($aParams['paypal_request_subject']);

            $invoice->getMerchantInfo()
                ->setEmail("paypal@s3stores.com")
                ->setBusinessName("S3 Stores, Inc.")
                ->setPhone(new Phone())
                ->setAddress(new InvoiceAddress());

            $invoice->getMerchantInfo()->getPhone()
                ->setCountryCode("001")
                ->setNationalNumber("8009292431");

            $invoice->getMerchantInfo()->getAddress()
                ->setLine1("27 Joseph St.")
                ->setCity("Chatham")
                ->setState("ON")
                ->setPostalCode("N7L 3G4")
                ->setCountryCode("CA");

            $oOrder = Order::model(['orderid' => (int)$aParams['send_request_orderid']]);
            $billing = $invoice->getBillingInfo();
            $billing[0]->setEmail($aParams['paypal_request_email']);
            $billing[0]->setFirstName($oOrder->getCustomerEntity()->getCustomerFullName());

            $items = array();
            $items[0] = new InvoiceItem();
            $items[0]
                ->setName($aParams['paypal_request_notes'])
                ->setQuantity(1)
                ->setUnitPrice(new Currency());

            $items[0]->getUnitPrice()
                ->setCurrency($aParams['paypal_request_currency'])
                ->setValue(price_format($aParams['paypal_request_amount']));

            $invoice->setItems($items);

            $invoice->setLogoUrl('https://www.artistsupplysource.com/skin1_kolin/images/S3-Stores-Logo-S2.png');
            if (!empty($aParams['paypal_request_invoice_number'])) {
                $invoice->setNumber($aParams['paypal_request_invoice_number']);
            }

            try {
                $invoice->create($this->apiContext);
                $sendStatus = $invoice->send($this->apiContext);
                if (!$sendStatus) {
                    $invoice = null;
                }
            } catch (\Exception $e) {
                Logs::_log('orders', $oOrder->getOrderId(), 'X', $e->getMessage());
                $invoice = null;
            }
        }
        return $invoice;
    }

    public function getPayPalInvoice($invoiceId)
    {
        $invoice = Invoice::get($invoiceId, $this->apiContext);
        return $invoice;
    }
}