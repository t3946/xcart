<?php

namespace Modules\Order\Controllers\Api;

use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Helpers\Paths;

class InvoiceConventerController extends Controller
{
    public function convertToPdf()
    {
        $request = $this->getRequest();
        $orderId = $request->get->get('orderid');

        /** @var OrderModel $order_model */
        if ($orderId && $order_model = OrderModel::objects()->get(['orderid' => $orderId])) {

            $slug = $request->get->get('p');
            $mode = $request->get->get('mode');

            $hash = $order_model->getOrderHash();

            if ($slug === $hash) {

                $options = new Options();
                $options->setIsFontSubsettingEnabled(true);
                $options->setIsRemoteEnabled(true);
                $options->setIsHtml5ParserEnabled(true);

                $dompdf = new Dompdf($options);

                $dompdf->setBasePath(Paths::get('www.static.frontend.dist.css.fonts'));

                $html_invoice = OrderInvoiceHelper::getInvoiceHtml($order_model, "mail/invoice_pdf.tpl", $mode);

                $dompdf->loadHtml($string . $html_invoice);

                $dompdf->render();

                $dompdf->stream();
            }
        }

        $this->redirect(404);
    }

    public function printInvoice()
    {
        $request = $this->getRequest();

        $orderId = $request->get->get('orderid');

        /** @var OrderModel $order_model */
        if ($orderId && $order_model = OrderModel::objects()->get(['orderid' => $orderId])) {

            $slug = $request->get->get('p');
            $mode = $request->get->get('mode');

            $hash = $order_model->getOrderHash();

            if ($slug === $hash) {
                echo OrderInvoiceHelper::getInvoiceHtml($order_model, 'mail/invoice.tpl', $mode);
               exit();
            }
        }
        $this->redirect(404);
    }
}