<?php

namespace Modules\Account\Controllers\Api;

use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderGroupRefundModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderTrackingModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class OrdersApi extends FrontendController
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
            $mass[$key]['orderInfo'] = $item->getAttributes();
            $mass[$key]['orderGroups'] = OrderGroupModel::objects()->filter(['orderid' => $item->orderid])->asArray()->all();
            $mass[$key]['type'] = 'shipping';
            $mass[$key]['trackings'] = [];

            foreach ( $mass[$key]['orderGroups'] as $group_key => $group_item)
            {
                $mass[$key]['orderGroups'][$group_key]['orderGroupsItems'] = OrderDetailModel::objects()->
                filter(['order_group_id' =>  $mass[$key]['orderGroups'][$group_key]['order_group_id']])->
                asArray()->all();
                array_merge( $mass[$key]['trackings'], OrderTrackingModel::objects()->filter(['order_group_id' => $mass[$key]['orderGroups'][$group_key]['order_group_id']])->asArray()->all());
            }
        }

        $this->jsonResponse(['data'=>$mass]);
    }
}