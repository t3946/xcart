<?php

namespace Omnipay\Xpay;

use Modules\Order\Models\OrderModel;
use Omnipay\Common\AbstractGateway;
use Omnipay\Xpay\Message\CheckCartRequest;
use Omnipay\Xpay\Message\DetailInfoRequest;
use Omnipay\Xpay\Message\SaleRequest;

/**
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{

    public function getName(): string
    {
        return 'Xpay';
    }

    public function getShoppingCartId():? string
    {
        return $this->getParameter('shopping_cart_id');
    }

    public function setShoppingCartId($value): object
    {
        return $this->setParameter('shopping_cart_id', $value);
    }

    public function getPublicKey():? string
    {
        return $this->getParameter('public_key');
    }

    public function setPublicKey($value): object
    {
        return $this->setParameter('public_key', $value);
    }

    public function getPrivateKey():? string
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKey($value): object
    {
        return $this->setParameter('private_key', $value);
    }

    public function getPrivateKeyPassword():? string
    {
        return $this->getParameter('private_key_password');
    }

    public function setPrivateKeyPassword($value): object
    {
        return $this->setParameter('private_key_password', $value);
    }

    public function getConfigurationId():? string
    {
        return $this->getParameter('configuration_id');
    }

    public function setConfigurationId($value): object
    {
        return $this->setParameter('configuration_id', $value);
    }

    public function getMerchantEmail():? string
    {
        return $this->getParameter('merchant_email');
    }

    public function setMerchantEmail($value): object
    {
        return $this->setParameter('merchant_email', $value);
    }

    public function setOrder(OrderModel $value): object
    {
        return $this->setParameter('order', $value);
    }

    public function getOrder($value): OrderModel
    {
        return $this->getParameter('order');
    }

    public function getToken():? string
    {
        return $this->getParameter('token');
    }

    public function setToken($value): object
    {
        return $this->setParameter('token', $value);
    }

    public function getDeveloperMode()
    {
        return $this->getParameter('developerMode');
    }

    public function setDeveloperMode($value)
    {
        $this->setParameter('developerMode', $value);
    }

    public function authorize(array $parameters = array())
    {
    }

    public function capture(array $parameters = array())
    {
    }

    public function refund(array $parameters = array())
    {
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(SaleRequest::class, $parameters);
    }

    public function void(array $parameters = array())
    {
    }

    public function achPurchase(array $parameters = array())
    {
    }

    public function lookup(array $parameters = array())
    {
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
    }

    public function check_card(array $parameters = [])
    {
        return $this->createRequest(CheckCartRequest::class, $parameters);
    }

    public function get_detail_info(array $parameters = [])
    {
        return $this->createRequest(DetailInfoRequest::class, $parameters);
    }
}
