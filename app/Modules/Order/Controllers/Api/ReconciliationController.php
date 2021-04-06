<?php


namespace Modules\Order\Controllers\Api;


use DateTime;
use Modules\Order\Helpers\OrderReconciliationHelper;
use Modules\Order\Models\OrderGroupInvoiceModel;
use Modules\Order\Models\OrderGroupMemoModel;
use Modules\Order\Models\ReconciliationManufacturerModel;
use Modules\Order\Models\ReconciliationModel;
use Xcart\App\Controller\Controller;

class ReconciliationController extends Controller
{
    public const INVOICE_STATUS_RECONCILE = [
        OrderGroupInvoiceModel::INVOICE_STATUS_PRE_RECONCILED => [
            'reconciliation_status' => ReconciliationModel::RECONCILIATION_STATUS_PRE_RECONCILED,
            'description' => 'PRE RECONCILIATION TRANSACTION',
        ],
        OrderGroupInvoiceModel::INVOICE_STATUS_TENTATIVELY => [
            'reconciliation_status' => ReconciliationModel::RECONCILIATION_STATUS_PRE_RECONCILED,
            'description' => 'PRE RECONCILIATION TRANSACTION',
        ]
    ];

    public function actionPayableManufacturers(): void
    {
        $request = $this->getRequest();
        $data = OrderReconciliationHelper::getPayableManufacturers($request->post->all());
        $this->jsonResponse($data);
    }

    public function actionPayableOrders(): void
    {
        $request = $this->getRequest();
        $data = OrderReconciliationHelper::getPayableOrders($request->post->all());
        if ($data) {
            echo $this->render('reconciliation/order_payable.tpl', ['orders' => $data]);
        }
    }

    /**
     * @return OrderGroupInvoiceModel[]|OrderGroupMemoModel[]
     */
    private function getInvoices(): array
    {
        $inv_memos = [];
        $request = $this->getRequest();

        if ($invoices = $request->post->get('invoices')) {
            foreach ($invoices as $invoice) {
                [$order_id, $manufacturer_id, $inv_number] = explode('_', $invoice);
                if ($model = OrderGroupInvoiceModel::objects()->get([
                    'orderid' => $order_id,
                    'manufacturerid' => $manufacturer_id,
                    'invoice_number' => $inv_number
                ])) {
                    $inv_memos[] = $model;
                }
            }
        }
        if ($memos = $request->post->get('memos')) {
            foreach ($memos as $memo) {
                [$order_id, $manufacturer_id, $memo_number] = explode('_', $memo);
                if ($model = OrderGroupMemoModel::objects()->get(['orderid' => $order_id, 'manufacturerid' => $manufacturer_id, 'memo_number' => $memo_number])) {
                    $inv_memos[] = $model;
                }
            }
        }
        return $inv_memos;
    }

    private function processInvoices(string $new_status): void
    {
        $total_sum = 0;

        $request = $this->getRequest();

        $inv_memos = $this->getInvoices();

        if ($inv_memos) {
            $total_sum = array_sum(array_map(
                static fn($item) => $item instanceof OrderGroupInvoiceModel
                    ? $item->invoice_total
                    : -$item->ref_to_us_total, $inv_memos));
        }

        $manufacturer_id = $request->post->get('manufacturer_id');

        if (!$reconciliation = ReconciliationModel::objects()->get(
            [
                'action' => self::INVOICE_STATUS_RECONCILE[$new_status]['reconciliation_status'],
                'amount_csv' => -$total_sum,
                'distributor__manufacturerid' => $manufacturer_id
            ])) {
            $rec_model = new ReconciliationModel([
                'action' => self::INVOICE_STATUS_RECONCILE[$new_status]['reconciliation_status'],
                'amount_csv' => -$total_sum,
                'date_csv' => (new DateTime('now'))->getTimestamp(),
                'description_csv' => self::INVOICE_STATUS_RECONCILE[$new_status]['description'],
                'type' => 'SPEND',
                'status' => 'AUTHORISED'
            ]);

            if ($rec_model->save()) {
                ReconciliationManufacturerModel::objects()->getOrCreate([
                    'reconciliation_id' => $rec_model->id,
                    'manufacturer_id' => $manufacturer_id,
                ]);
                foreach ($inv_memos as $model) {
                    $model->status = $new_status;
                    $model->reconciliation_id = $rec_model->id;
                    $model->save();
                }
            }
        }
    }

    public function actionPayableOrdersPreReconcile(): void
    {
        $this->processInvoices(OrderGroupInvoiceModel::INVOICE_STATUS_PRE_RECONCILED);
    }

    public function actionPayableOrdersTentatively(): void
    {
        $this->processInvoices(OrderGroupInvoiceModel::INVOICE_STATUS_TENTATIVELY);
    }
}