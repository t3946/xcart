<?php


namespace Modules\Order\Controllers\Api;


use Modules\Order\Helpers\OrderReconciliationHelper;
use Modules\Order\Models\OrderGroupInvoiceModel;
use Modules\Order\Models\OrderGroupMemoModel;
use Modules\Order\Models\ReconciliationManufacturerModel;
use Modules\Order\Models\ReconciliationModel;
use Xcart\App\Controller\Controller;

class ReconciliationController extends Controller
{
    public function actionPayableManufacturers()
    {
        $request = $this->getRequest();
        $data = OrderReconciliationHelper::getPayableManufacturers($request->post->all());
        $this->jsonResponse($data);
    }

    public function actionPayableOrders()
    {
        $request = $this->getRequest();
        $data = OrderReconciliationHelper::getPayableOrders($request->post->all());
        if ($data) {
            echo $this->render('reconciliation/order_payable.tpl', ['orders' => $data]);
        }
    }

    public function actionPayableOrdersPreReconcile()
    {
        $total_sum = 0;
        $request = $this->getRequest();

        $manufacturer_id =  $request->post->get('manufacturer_id');

        if ($invoices = $request->post->get('invoices')) {
            foreach ($invoices as $invoice) {
                [$order_id, $manufacturer_id, $inv_number] = explode('_', $invoice);
                if ($model = OrderGroupInvoiceModel::objects()->get(['orderid' => $order_id, 'manufacturerid' => $manufacturer_id, 'invoice_number' => $inv_number])){
                    $inv_memos[] = $model;
                }
            }
        }
        if ($memos = $request->post->get('memos')) {
            foreach ($memos as $memo) {
                [$order_id, $manufacturer_id, $memo_number] = explode('_', $memo);
                if ($model = OrderGroupMemoModel::objects()->get(['orderid' => $order_id, 'manufacturerid' => $manufacturer_id, 'memo_number' => $memo_number])){
                    $inv_memos[] = $model;
                }
            }
        }

        if ($inv_memos) {
            $total_sum = array_sum(array_map(function ($item) {
                if ($item instanceof OrderGroupInvoiceModel) {
                    return $item->invoice_total;
                }
                if ($item instanceof OrderGroupMemoModel) {
                    return -$item->ref_to_us_total;
                }
                return 0;
            }, $inv_memos));
        }

        if (!$reconciliation = ReconciliationModel::objects()->get(['action' => 'P', 'amount_csv' => -$total_sum, 'distributor__manufacturerid' => $manufacturer_id])) {
            $rec_model = new ReconciliationModel([
                'action' => 'P',
                'amount_csv' => -$total_sum,
                'date_csv' => (new \DateTime('now'))->getTimestamp(),
                'description_csv' => 'PRE RECONCILIATION TRANSACTION',
                'type' => 'SPEND',
                'status' => 'AUTHORISED'
            ]);
            if ($rec_model->save()) {
                ReconciliationManufacturerModel::objects()->getOrCreate([
                    'reconciliation_id' => $rec_model->id,
                    'manufacturer_id' => $manufacturer_id,
                ]);
                foreach ($inv_memos as  $model) {
                    $model->status = 'P';
                    $model->reconciliation_id = $rec_model->id;
                    $model->save();
                }
            }
        }
    }
}