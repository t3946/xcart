<?php

namespace Modules\Order\Controllers\Api;

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
            $string = '<html lang="ru">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />';

                $mpdf = new Mpdf();
                $html_invoice = OrderInvoiceHelper::getInvoiceHtml($order_model, "mail/invoice_pdf.tpl");
                $html_invoice = $string . $html_invoice;
                $mpdf->WriteHTML($html_invoice);
                $mpdf->Output();
            }
        }

        $this->redirect(404);
    }

    public function printInvoice()
    {
        $request = $this->getRequest();

        /** @var OrderModel $order_model */
        if ($order_model = OrderModel::objects()->get(['orderid' => $request->get->get('orderid')])) {

            $slug = $request->get->get('p');
            $mode = $request->get->get('mode');

            $hash = OrderHelper::getOrderHash([$order_model->orderid, $order_model->total, $order_model->email]);

            if ($slug == $hash) {
                echo OrderInvoiceHelper::getInvoiceHtml($order_model, 'mail/invoice.tpl', $mode);
            }
        }
        $this->redirect(404);
    }
}