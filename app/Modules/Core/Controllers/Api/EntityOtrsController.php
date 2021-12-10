<?php

namespace Modules\Core\Controllers\Api;

use Modules\Goods\Models\ProductQuestionModel;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class EntityOtrsController extends Controller
{
    public function getOtrsMessage(): void
    {
        try {
            $data = Xcart::app()->request->get;
            $entity = $data['entity'];
            $entityId = $data['entityId'];
            $entity_model = null;
            switch ($entity) {
                case 'order':
                    /** @var OrderModel $order_model */
                    $entity_model = OrderModel::objects()->get(['pk' => $entityId]);
                    break;
                case 'question':
                    /** @var ProductQuestionModel $entity_model */
                    $entity_model = ProductQuestionModel::objects()->get(['pk' => $entityId]);
                    break;
            }

            if ($entity_model) {
                $ticket_resolver_messages = OrderHelper::getOTRSMessages($entity_model);

                $ticket_resolver_link = $entity_model->otrs_ticket;

                $this->jsonResponse(['link' => $ticket_resolver_link, 'message' => $ticket_resolver_messages]);
            }
        } catch (Throwable $e) {
            $this->jsonResponse(['message' => 'Error'], 400);
        }
    }
}