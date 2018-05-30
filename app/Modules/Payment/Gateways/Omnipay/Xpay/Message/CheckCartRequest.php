<?php

namespace Omnipay\Xpay\Message;
use Modules\Payment\Gateways\Xpay;
use Xcart\App\Helpers\Xml;
use Xcart\App\Main\Xcart;


/**
 * Xpay Sale Request
 */
class CheckCartRequest extends AbstractRequest
{

    protected function getBaseData(): array
    {
        return [
            'status' => 'cart-not-changed',
        ];

    }

    public function getData()
    {
        return $this->getBaseData();
    }

    public function getMethod()
    {
        return '';
    }

    public function getAnswer()
    {
        $data = $this->getData();

        preg_match("/<response>(.*)<\/response>/s", Xml::encode('response', $data, false), $matches);
        return Xpay::encrypt($matches[1],$this->getPublicKey());

        /*try {
            $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $request)->send();
        } catch(\Exception $e) {
            Xcart::app()->logger->debug("error", [$e->getMessage()], 'payment');
        }

        return $this->response;*/
    }

}
