<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\OrderCancelItemsModel;
use Modules\Account\Models\OrderCancelRequestModel;
use Modules\Account\Models\OrderProblemsModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Forms\Models\EmailModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTrackingModel;
use Modules\Order\Models\RMADetailModel;
use Modules\Order\Models\RMAModel;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use function Clue\StreamFilter\fun;

class OrdersApi extends Controller
{
    public function getOrders($orders_type, $to_date)
    {
        $filter = [];

        if($to_date !== 'undefined'){
            $filter = ['date__gte' => $to_date];
        }

        switch ($orders_type)
        {
            case 'closed': {
                $filter =   array_merge($filter, ['cb_status' => 'F']);
            }
            case 'completed': {
                $filter =   array_merge($filter, ['cb_status' => 'P', 'dc_status' => 'Z']);
            }
            case 'open': {
                $filter =  array_merge($filter, ['cb_status__in' => ['AP', 'P', 'Q'], 'dc_status__isnt' => 'Z']);
            }
        }


        $user = Xcart::app()->auth->getUser(true);
        $mass = [];
        foreach ($user->orders->filter($filter) as $key => $item)
        {

            $mass[$key]['orderInfo']['emails'] = [];
            $mass[$key]['orderInfo'] = $item->getAttributes();
            $mass[$key]['orderInfo']['payments'] = $item->payment_method_model->getAttributes();
            $mass[$key]['orderInfo']["payment_status"] = OrderStatusModel::objects()->get(['code' =>  $mass[$key]['orderInfo']['cb_status']])->description;

            $mass[$key]['orderGroups'] = OrderGroupModel::objects()->filter(['orderid' => $item->orderid])->asArray()->all();
            $mass[$key]['type'] = 'shipping';

            $qs = EmailModel::objects()->getQuerySet()->order(['-date']);

            $email_qs = $qs->filter(["order_models__orderid" => (int)$mass[$key]['orderInfo']['orderid']]);


            $pagination = new Pagination($email_qs, [
                'page' => 0,
                'pageSize' => 40,
            ], new QuerySetDataSource());

            try {
                foreach ($pagination->paginate() as $email_key => $model)
                {
                    /** @var EmailModel $model */
                    $mass[$key]['orderInfo']['emails'][$email_key] = $model->getAttributes();
                    $mass[$key]['orderInfo']['emails'][$email_key]['attachment'] = $model->getAttachment();
                    if((string)$model->body){
                        $mass[$key]['orderInfo']['emails'][$email_key]['body'] = (string)$model->body;
                    }
                    $mass[$key]['orderInfo']['emails'][$email_key]['emailType'] = $model->getEmailType($model->id);
                }
            } catch (Throwable $exception) {
                $this->jsonResponse(['error' => $exception->getMessage()]);
                return;
            }

            foreach ( $mass[$key]['orderGroups'] as $group_key => $group_item)
            {
                $mass[$key]['orderGroups'][$group_key]['manufacturer'] = DistributorModel::objects()->get(['manufacturerid' => $mass[$key]['orderGroups'][$group_key]['manufacturerid']])->getAttributes();
                $mass[$key]['orderGroups'][$group_key]['orderGroupsItems'] = OrderDetailModel::objects()->
                filter(['order_group_id' =>  $mass[$key]['orderGroups'][$group_key]['order_group_id']])->
                asArray()->all();
                foreach ($mass[$key]['orderGroups'][$group_key]['orderGroupsItems'] as $product_key => $product_item)
                {
                    $product = OrderDetailModel::objects()->get(['productid' => $product_item['productid'], 'order_group_id' =>$mass[$key]['orderGroups'][$group_key]['order_group_id'] ]);
                    $mass[$key]['orderGroups'][$group_key]['orderGroupsItems'][$product_key]['image'] =  (string) $product->product_model->getMainImage();
                }
                $mass[$key]['orderGroups'][$group_key]['trackings'] =  OrderTrackingModel::objects()->filter(['order_group_id' => $mass[$key]['orderGroups'][$group_key]['order_group_id']])->asArray()->all();
            }
        }

        $this->jsonResponse(['data'=>$mass]);
    }

    public function getOneOrder($order_id)
    {
        $user = Xcart::app()->auth->getUser(true);

        $orderModel = $user->orders->get(["orderid" => $order_id]);

        $order = [];

        $order['orderInfo'] = $orderModel->getAttributes();
        $order['orderInfo']["payment_status"] = OrderStatusModel::objects()->get(['code' =>  $order['orderInfo']['cb_status']])->description;
        $order['orderInfo']['payments'] = $orderModel->payment_method_model->getAttributes();
        $order['orderGroups'] = OrderGroupModel::objects()->filter(['orderid' => $orderModel->orderid])->asArray()->all();
        $order['type'] = 'shipping';

        $qs = EmailModel::objects()->getQuerySet()->order(['-date']);

        $order['orderInfo']['emails'] = [];

        $email_qs = $qs->filter(["order_models__orderid" =>  $order['orderInfo']['orderid']]);

        $pagination = new Pagination($email_qs, [
            'page' => 0,
            'pageSize' => 40,
        ], new QuerySetDataSource());

        if($pagination->getTotal()){
            try {
                foreach ($pagination->paginate() as $email_key => $model)
                {

                    /** @var EmailModel $model */
                    $order['orderInfo']['emails'][$email_key] = $model->getAttributes();
                    $order['orderInfo']['emails'][$email_key]['attachment'] = $model->getAttachment();
                    $order['orderInfo']['emails'][$email_key]['body'] = (string)$model->body;
                    $order['orderInfo']['emails'][$email_key]['emailType'] = $model->getEmailType($model->id);
                }
            } catch (Throwable $exception) {
                $this->jsonResponse(['error' => $exception->getMessage()]);
                return;
            }
        }

        foreach ( $order['orderGroups'] as $group_key => $group_item)
        {
            $order['orderGroups'][$group_key]['manufacturer'] = DistributorModel::objects()->get(['manufacturerid' => $order['orderGroups'][$group_key]['manufacturerid']])->getAttributes();
            $order['orderGroups'][$group_key]['orderGroupsItems'] = OrderDetailModel::objects()->
            filter(['order_group_id' =>  $order['orderGroups'][$group_key]['order_group_id']])->
            asArray()->all();
            foreach ($mass[$key]['orderGroups'][$group_key]['orderGroupsItems'] as $product_key => $product_item)
            {
                $product = OrderDetailModel::objects()->get(['productid' => $product_item['productid'], 'order_group_id' =>$mass[$key]['orderGroups'][$group_key]['order_group_id'] ]);
                $mass[$key]['orderGroups'][$group_key]['orderGroupsItems'][$product_key]['image'] =  (string) $product->product_model->getMainImage();
            }
            $order['orderGroups'][$group_key]['trackings'] =  OrderTrackingModel::objects()->filter(['order_group_id' => $order['orderGroups'][$group_key]['order_group_id']])->asArray()->all();
        }
        $this->jsonResponse(['data'=>$order]);
    }

    public function sendProblemMessage()
    {
        $user = Xcart::app()->auth->getUser(true);

        if(!$user)
        {
            $this->jsonResponse('user not login');
            return;
        }

        $data = json_decode(file_get_contents('php://input'),true);


        OrderProblemsModel::objects()->create($data);

        $this->jsonResponse(['success']);
    }

    public function openCancelItemsRequest()
    {
        $user = Xcart::app()->auth->getUser(true);

        if(!$user)
        {
            $this->jsonResponse('user not login');
            return;
        }

        [$order, $items] = array_values(json_decode(file_get_contents('php://input'),true));

        OrderCancelRequestModel::objects()->create($order);

        foreach ($items as $key => $item)
        {
            OrderCancelItemsModel::objects()->create($item);
        }


        $this->jsonResponse(['success']);
    }

    public function openRmaRequest()
    {
        $user = Xcart::app()->auth->getUser(true);

        if(!$user)
        {
            $this->jsonResponse('user not login');
            return;
        }

        $request_data = json_decode(file_get_contents('php://input'),true);

        RMAModel::objects()->create($request_data['rma_info']);

        foreach ($request_data['rma_items'] as $item)
        {
            RMADetailModel::objects()->create($item);
        }

        $this->jsonResponse(['success']);
    }

    public function editShippingAddress()
    {
        $user = Xcart::app()->auth->getUser(true);

        if(!$user)
        {
            $this->jsonResponse('user not login');
            return;
        }

        [$order_id, $address_data] = array_values(json_decode(file_get_contents('php://input'),true));

        $order_model = OrderModel::objects()->get(['orderid' => $order_id]);

        $order_model->setAttributes($address_data);

        $order_model->save();

        $this->jsonResponse($order_model->getAttributes());
    }
}