<?php


namespace Modules\Order\Middleware;


use Xcart\App\Middleware\Middleware;

class OrderCheckoutMiddleware extends Middleware
{
    public const ONE_PAGE_CHECKOUT_TYPE = 'one_page';
    public const MULTIPLE_PAGE_CHECKOUT_TYPE = 'multiple_page';

    public function processHttpRequest($request)
    {
        if (!$request->session->has('order_checkout_type')) {
            $type = $request->session->getStorage()->id % 2 ? self::ONE_PAGE_CHECKOUT_TYPE : self::MULTIPLE_PAGE_CHECKOUT_TYPE;
            $request->session->add('order_checkout_type', $type);
        }
    }
}