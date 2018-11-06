<?php


namespace Omnipay\PayPal;

use Modules\Order\Models\OrderModel;


/**
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 */
class Gateway extends RestGateway
{

    public function setOrder(OrderModel $value): object
    {
        return $this->setParameter('order', $value);
    }

    public function getOrder($value): OrderModel
    {
        return $this->getParameter('order');
    }


    //
    // Payments -- Create payments or get details of one or more payments.
    //
    // @link https://developer.paypal.com/docs/api/#payments
    //

    /**
     * Create a purchase request.
     *
     * PayPal provides various payment related operations using the /payment
     * resource and related sub-resources. Use payment for direct credit card
     * payments and PayPal account payments. You can also use sub-resources
     * to get payment related details.
     *
     * @link https://developer.paypal.com/docs/api/#create-a-payment
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\CheckoutAuthorizeRequest', $parameters);
    }

    public function lookup(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestLookupRequest', $parameters);
    }




    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
    }
}
