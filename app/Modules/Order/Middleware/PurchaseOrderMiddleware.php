<?php


namespace Modules\Order\Middleware;


use Xcart\App\Middleware\Middleware;

class PurchaseOrderMiddleware extends Middleware
{
    public function processHttpRequest($request)
    {
        if ($request->get->has('purchase_order_selected')) {
            $request->session->add('frontend_purchase_order_id', $request->get->get('purchase_order_selected'));
        }
    }
}