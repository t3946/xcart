<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\OrderCancelItemsModel;
use Modules\Account\Models\OrderCancelRequestModel;
use Modules\Account\Models\OrderProblemsModel;
use Modules\Account\Models\OrderProblemStatusesModel;
use Modules\Forms\Models\EmailModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\RMA\ImagesModel;
use Modules\Order\Models\RMA\RMADetailModel;
use Modules\Order\Models\RMA\RMAModel;
use Modules\Order\Models\RMA\RMAStatusModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Form\PrepareData;
use Xcart\App\Main\Xcart;

class OrdersApi extends Controller
{
    private const ORDERS_TYPE_CLOSED = 'closed';
    private const ORDER_TYPE_COMPLETED = 'completed';
    private const ORDER_TYPE_OPEN = 'open';

    public function getOrders($orders_type, $to_date)
    {
        $filter = [];
        /** @var UserModel $user */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            http_response_code(401);
            return;
        }

        if ($to_date !== 'undefined') {
            $filter = ['date__gte' => $to_date];
        }

        switch ($orders_type) {
            case self::ORDERS_TYPE_CLOSED:
                $filter = array_merge(
                    $filter, [
                    'cb_status' => OrderStatusModel::ORDER_STATUS_FAILED
                ]);
                break;
            case self::ORDER_TYPE_COMPLETED:
                $filter = array_merge(
                    $filter, [
                    'cb_status' => OrderStatusModel::ORDER_STATUS_COMPLETED,
                    'dc_status' => OrderStatusModel::ORDER_DC_STATUS_DELIVERED
                ]);
                break;
            case self::ORDER_TYPE_OPEN:
                $filter = array_merge($filter, [
                    'cb_status__in' => [
                        OrderStatusModel::ORDER_STATUS_AUTHORIZED,
                        OrderStatusModel::ORDER_STATUS_COMPLETED,
                        OrderStatusModel::ORDER_STATUS_QUEUED
                    ],
                    'dc_status__isnt' => OrderStatusModel::ORDER_DC_STATUS_DELIVERED,
                ]);
                break;
        }
        foreach ($user->orders->filter($filter) as $order_model) {
            $group_data = [];
            foreach ($order_model->groups as $group) {
                $ar_products = [];
                $manufacturer = $group->manufacturer;
                foreach ($group->detail_models as $model) {
                    $ar_products[] = $model->getFrontendProduct();
                }
                $group_data[] = [
                    'manufacturer' => $manufacturer->getFrontendAddress(),
                    'products' => $ar_products ?? [],
                ];
            }

            $ar_data[] = [
                'orderNumber' => $order_model->getOrderNumber(),
                'date' => $order_model->date,
                'total' => $order_model->total,
                'type' => 'shipping',
                'orderId' => $order_model->pk,
                'groups' => $group_data,
                'address' => $order_model->getFrontendAddress(),
            ];
        }
        $this->jsonResponse($ar_data ?? []);
    }

    public function getOrder($order_id)
    {
        /**
         * @var $user UserModel
        */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            http_response_code(401);
            return;
        }

        /** @var OrderModel $order_model */
        $order_model = $user->orders->get(["pk" => $order_id]);
        foreach ($order_model->groups as $group_model) {
            $manufacturer = $group_model->manufacturer;

            foreach ($group_model->detail_models as $model) {
                $ar_products[] = $model->getFrontendProduct();
            }

            foreach ($group_model->trackings as $tracking_model) {
                $tracks[] = [
                    'number' => $tracking_model->tracknum,
                    'link' => $tracking_model->link->shipping,
                ];
            }
            $groups[] = [
                'tracks' => $tracks ?? [],
                'products' => $ar_products ?? [],
                'manufacturer' => $manufacturer->getFrontendAddress(),
                'a2bStatus' => $group_model->a2b_status,
                'a2cStatus' => $group_model->a2c_status,
                'shippingGross' => $group_model->shipping_gross,
                'totalPst' => $group_model->total_pst,
                'totalTax' => $group_model->total_tax,
                'totalGross' => $group_model->total_gross,
            ];
        }
        foreach ($order_model->logs_model->exclude(['type' => OrderLogModel::LOG_TYPE_PAYMENT_PROCESS]) as $log_model) {
            $logs[] = [
                'type' => $log_model->type,
                'date' => $log_model->date,
                'login' => $log_model->login,
                'action' => $log_model->log,
                'id' => $log_model->pk
            ];
        }
//        $emails = EmailModel::objects()->filter(["order_models__orderid" => $order_id])->order(['-date']);
//        /** @var EmailModel $email_model */
//        foreach ($emails as $email_model) {
//            $ar_emails[] = $email_model->getFrontendEmail();
//        }
        $transaction = $order_model->transactions[0];
        if ($order_model->isPurchaseOrder() && $extra_model = $order_model->extra_model) {
            $purchase_data = $extra_model->getFrontendPurchase();
        }
        $order = [
            'orderNumber' => $order_model->getOrderNumber(),
            'client' => [
                'firstName' => $order_model->firstname,
                'phone' => $order_model->phone,
                'phoneExt' => $order_model->phone_ext ?: '',
                'email' => $order_model->email,
                'shippingName' => $order_model->s_firstname,
                'billingName' => $order_model->b_firstname,
                'company' => $order_model->b_company,
            ],
            'groups' => $groups ?? [],
            'orderId' => $order_model->orderid,
            'poNumber' => $order_model->po_number,
            'address' => $order_model->getFrontendAddress(),
            'payment' => [
                'status' => $order_model->cb_status_model->name,
                'date' => $transaction ? $transaction->date : $order_model->date, // TODO: Поменять на метод getFirstTransaction из master branch
            ],
            'logs' => $logs ?? [],
            'emails' => [],
            'purchase' => $purchase_data ?? null
        ];
        $this->jsonResponse($order);
    }

    public function sendProblemMessage()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user) {
            $this->jsonResponse('user not login');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        OrderProblemsModel::objects()->create($data);

        $this->jsonResponse(['success']);
    }

    public function getProblemStatuses()
    {
        /** @var OrderProblemStatusesModel $status */
        foreach (OrderProblemStatusesModel::objects()->all() as $status) {
            $statuses[] = [
                'value' => $status->pk,
                'viewValue' => $status->status_text
            ];
        }
        $this->jsonResponse($statuses ?? []);
    }

    public function openCancelItemsRequest()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user) {
            $this->jsonResponse('user not login');
            return;
        }

        [$order, $items] = array_values(json_decode(file_get_contents('php://input'), true));

        OrderCancelRequestModel::objects()->create($order);

        foreach ($items as $key => $item) {
            OrderCancelItemsModel::objects()->create($item);
        }


        $this->jsonResponse(['success']);
    }

    public function openRmaRequest()
    {
        $rma_details = [];

        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            $this->jsonResponse('user not login', 401);
            return;
        }

        $request_data = Xcart::app()->request->post->all();

        $order = $user->orders->get(['orderid' => $request_data['orderId']]);

        $items = json_decode($request_data['items'], true, 512, JSON_THROW_ON_ERROR);

        if (!$order) {
            $this->jsonResponse('order not found', 400);
            return;
        }

        foreach ($items as $item) {
            if ((int)$item['amount'] === 0) {
                continue;
            }

            $detail = $order->detail_models->get(['productid' => $item['productId']]);

            $rma_details[] = [
                'productid' => $detail->productid,
                'productcode' => $detail->productcode,
                'product' => $detail->product,
                'amount' => $item['amount'],
                'would_like' => $item['wouldLike'],
            ];
        }

        if (!$rma_details) {
            $this->jsonResponse('RMA items not selected', 400);
            return;
        }

        $rma = new RMAModel([
            'orderid' => $order->pk,
            'zipcode' => $order->s_zipcode,
            'email' => $user->email,
            'order_email' => $order->email,
            'explanation' => $request_data['rmaText'],
            'status' => RMAStatusModel::STATUS_SUBMIT_TO_US,
            'rma_number' => 1,

        ]);

        if ($_FILES) {
            $files = PrepareData::fixFiles($_FILES);
            foreach ($files['files'] as $file) {
                $image = new ImagesModel([
                    'path' => $file,
                ]);
                $image->save();
                $rma->images[] = $image;
            }
        }

        $rma->save();

        array_map(static fn($rma_detail) => RMADetailModel::objects()->create(
            ['rma_id' => $rma->pk] + $rma_detail
        ), $rma_details);

        $this->jsonResponse(['success']);
    }

    public function editShippingAddress()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user) {
            $this->jsonResponse('user not login');
            return;
        }

        [$order_id, $address_data] = array_values(json_decode(file_get_contents('php://input'), true));

        $order_model = OrderModel::objects()->get(['orderid' => $order_id]);

        $order_model->setAttributes($address_data);

        $order_model->save();

        $this->jsonResponse($order_model->getAttributes());
    }
}