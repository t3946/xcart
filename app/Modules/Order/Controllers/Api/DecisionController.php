<?php

namespace Modules\Order\Controllers\Api;

use Modules\Order\Models\DecisionModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\Controller;

class DecisionController extends Controller
{
    private $data;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->data = json_decode(file_get_contents('php://input'), true);
    }

    public function createEstimatedTimeArrivalDecisionAction()
    {
        $order_id = $this->data['order_id'];
        $order =  OrderModel::objects()->filter(['orderid' => $order_id])->get();
        $order_number = $order->getOrderNumber();
        $options = $this->data['options'];
        $options['order_number'] = $order_number;
        $decision = new DecisionModel([
            'type' => DecisionModel::DECISION_TYPE_ESTIMATED_TIME_ARRIVAL,
            'resolved' => 0,
            'options' => $options,
            'order_id' => $order_id,
        ]);

        $decision->save();

        echo $decision->getAttribute('pk');
    }

    public function getDecisionsAction()
    {
        $this->jsonResponse(DecisionModel::objects()->asArray()->all());
    }
}