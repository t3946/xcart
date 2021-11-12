<?php

namespace Modules\Order\Controllers\Api;

use Modules\Order\Models\DecisionModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use function Mindy\app;

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
        $order = OrderModel::objects()->filter(['orderid' => $order_id])->get();
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
        $user_id = $this->data['user_id'];
        $this->jsonResponse(self::getDecisions($user_id));
    }

    public static function getDecisions($user_id, $resolved, $limit, $offset)
    {
        $filters = ['orders__user_id' => $user_id, 'resolved' => $resolved];
        $qm = DecisionModel::objects()->filter($filters)->asArray();

        if (isset($limit)) {
            $qm->limit($limit);
        }

        if (isset($offset)) {
            $qm->offset($offset);
        }

        $decisions = $qm->all();

        return array_map(function ($decision) {
            $decision['options'] = json_decode($decision['options']);
            return $decision;
        }, $decisions);
    }
}
