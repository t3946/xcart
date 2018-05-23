<?php

namespace Modules\Order\Api\Controllers;

use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Models\OrderModel;
use Mpdf\Mpdf;
use Xcart\App\Controller\Controller;

class InvoiceConventerController extends Controller
{
    public function convertToPdf()
    {
        $request = $this->getRequest();

        /** @var OrderModel $order_model */
        if ($order_model = OrderModel::objects()->get(['orderid' => $request->get->get('orderid')])) {

            $slug = $request->get->get('p');

            $hash = OrderHelper::getOrderHash([$order_model->orderid, $order_model->total, $order_model->email]);

            if ($slug == $hash) {

                $mpdf = new Mpdf();
                $html_invoice = OrderInvoiceHelper::getInvoiceHtml($order_model);
                $mpdf->WriteHTML($html_invoice);
                $mpdf->Output();
            }
        }
    }
}