<?php

namespace Modules\Payment\Gateways;


use Modules\Order\Models\OrderTransactionModel;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;
use Xcart\App\Main\Xcart;

class Offline extends Gateway
{
    public function __construct($model)
    {
        $this->model = $model;

        if (!$this->model) {
            parent::__construct($model);
        }

        $this->gateway = new OfflineGateway();

        $this->init();
    }

    public static function getProcessorName()
    {
        return 'Offline';
    }

    /**
     * @param $params
     * @return bool
     */
    public function refund($params)
    {
        // TODO: Implement refund() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function void($params)
    {
        // TODO: Implement void() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function capture($params)
    {
        // TODO: Implement capture() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function lookup($params)
    {
        // TODO: Implement lookup() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function authorize($params)
    {
        // TODO: Implement authorize() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function reauthorize($params)
    {
        // TODO: Implement reauthorize() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function purchase($params)
    {
        $this->result = new OfflineResponse($this->gateway->purchase($params), $params);

        return false;
    }

    /**
     * @param $params
     * @return bool
     */
    public function complete($params)
    {
        // TODO: Implement complete() method.
    }

    public function getState($mode)
    {
        return OrderTransactionModel::STATUS_AUTHORIZED;
    }
}

class OfflineResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return Xcart::app()->router->url('checkout:complete', ['order_id' => $this->getRequest()->getOrder()->orderid]);
    }

    public function redirect()
    {
        Xcart::app()->event->trigger('order:paid', ['model' => $this->getRequest()->getOrder()]);
        Xcart::app()->request->redirect($this->getRedirectUrl());
    }


}

class OfflineRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        // TODO: Implement getData() method.
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        // TODO: Implement sendData() method.
    }

    public function setOrder(object $value): object
    {
        return $this->setParameter('order', $value);
    }

    public function getOrder(): object
    {
        return $this->getParameter('order');
    }
}

/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class OfflineGateway extends AbstractGateway
{
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Offline';
    }

    public function purchase(array $options)
    {
        return $this->createRequest(OfflineRequest::class, $options);
    }

}