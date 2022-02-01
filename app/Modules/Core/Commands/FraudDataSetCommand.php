<?php

namespace Modules\Core\Commands;


use JsonException;
use Modules\Order\Models\FraudCheckBaseQuestionModel;
use Modules\Order\Models\OrderDataSetModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;
use Xcart\App\Commands\Command;

class FraudDataSetCommand extends Command
{

    /**
     * @param array $arguments
     */
    public function handle($arguments = [])
    {
//        $this->collectDataSet();
//        $name = '';
//        Xcart::app()->queue->setCount(1)->consume($name, [$this, 'consume']);
//        $base_orders = OrderModel::objects()->filter(
//            [
//                'cb_status' => OrderStatusModel::ORDER_STATUS_COMPLETED,
//                'dc_status__in' => [OrderStatusModel::ORDER_DC_STATUS_DELIVERED, OrderStatusModel::ORDER_DC_STATUS_SHIPPED],
//                'fraud_status' => 'C',
//                'order_fraud__id__isnull' => false,
//                'order_fraud__additional_info__contains' => 'PartyOwner1NameFull',
//            ])->group(['orderid'])->order(['?']);
//        $fraud_orders = OrderModel::objects()->filter(
//            [
//                'fraud_status__in' => ['K', 'P'],
//            ])->group(['orderid']);
    }

    /**
     * @param AMQPMessage $message
     * @throws JsonException
     */
    public function consume(AMQPMessage $message): void
    {
        if ($message->body && $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            try {
                /** @var OrderModel $order */
                $order = OrderModel::objects()->get(['pk' => $data['orderid']]);
                $order->orderFraudCheck();
                $data_set = new OrderDataSetModel();
                $data_set->order_id = $order->pk;
                $data_set->is_fraud = true; // поставить false если для обычных заказов
                $data_set->save();
                $message->ack();

            } catch (Throwable $e) {
                $message->nack(true);
            }
        }
    }

    public function collectDataSet()
    {
        /** @var OrderDataSetModel $item */
        foreach (OrderDataSetModel::objects()->filter(['fraud_check__id__isnull' => false])->order(['data_id'])->group(['order_id']) as $item) {
            $ar_set = [];
            $order_model = $item->order;

            foreach ($order_model->base_fraud_answer as $answer) {
                $question = $answer->question;
                if ($question->type === FraudCheckBaseQuestionModel::FRAUD_TYPE_RED_FLAGS && is_null($question->auto)) {
                    continue;
                }
                $ar_set[$answer->question->question_code] = $answer->outcome;
            }
            foreach ($order_model->fa_fraud_answer as $answer) {
                $question = $answer->question;
                $name_answer = "{$question->f_fraud->name}_{$question->t_fraud->name}";
                $ar_set[$name_answer] = $answer->outcome;
            }

            $ar_collect[] = [
                'order_id' => $order_model->pk,
                'is_fraud' => $item->is_fraud,
                'data' => $ar_set
            ];
        }
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . 'app/Modules/Core/dataset.json', json_encode($ar_collect ?? [], true));
    }

}