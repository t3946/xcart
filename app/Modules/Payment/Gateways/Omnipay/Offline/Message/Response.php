<?php

namespace Omnipay\Offline\Message;

use Modules\Order\Models\OrderStatusModel;
use Omnipay\Common\Message\AbstractResponse;
use Xcart\App\Main\Xcart;

class Response extends AbstractResponse
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
        return $this->getRequest()->getReturnUrl();
    }

    public function redirect(): void
    {
        $model = $this->getRequest()->getOrder();
        Xcart::app()->event->trigger('order:paid', ['model' => $model, 'status' => $model->payment_method == 'Purchase Order' ? 'IO' : OrderStatusModel::ORDER_STATUS_QUEUED]);
        Xcart::app()->request->redirect($this->getRedirectUrl());
    }

}