<?php
namespace Modules\PBX\Helpers;

use Mindy\QueryBuilder\Expression;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrdersCallsModel;
use Modules\Order\Models\OrderUserActivityModel;
use Modules\PBX\Models\PbxAnveoCallModel;
use Modules\User\Models\PbxOptionsModel;
use Modules\User\Models\UserModel;

class AnveoAssignCalls
{
    public static function eventBindCallToOrder($sender = null, $model)
    {

        self::bindCallToOrder($model) ?: self::bindCallToOrderThird($model);
        self::bindCallToOrderSecond($model);

        if (rand(0, 6) >= 5) {
            self::reValidate();
        }
    }

    /**
     * @param \Modules\PBX\Models\PbxAnveoCallModel $model
     *
     * @return bool
     */
    public static function bindCallToOrder($model = null)
    {
        if ($model && $model->start_at && $model->end_at)
        {
            if ($model->file) {
                $account = self::parseAccount($model->file);
                $e164 = self::parseE164($model->file);

                if ($account && $model->anveo_account != $account) {

                    $model->anveo_account = $account;
                    $model->save(['anveo_account']);
                }
            }

            if ($model->anveo_account && $model->options && $user_model = $model->options->user)
            {
                $filter = [
                    'user_id' => $user_model->id,
                    'created_at__gte' => $model->start_at,
                    'created_at__lte' => $model->end_at,
                ];

                /** @var OrderUserActivityModel[] $oua_models */
                if ($oua_models = OrderUserActivityModel::objects()->filter($filter)->all()) {
                    $manager = OrdersCallsModel::objects();

                    foreach ($oua_models as $oua_model) {
                        $manager->updateOrCreate(['call_id' => $model->id, 'order_id' => $oua_model->order_id],
                                                 ['relevance_type' => OrdersCallsModel::TYPE_VIEWING_SAME_OPERATOR, 'relevance_order' => 10]);

                        $log_category = "anveo_calls";
                        $log_text = "{$e164} - Привязан к заказу - {$oua_model->order_id} по первой связке";
                        func_backprocess_log($log_category, $log_text);
                    }
                    return true;
                }

                $log_category = "anveo_calls";
                $log_text = "{$e164} - Не привязан к заказу по первой привязке";
                func_backprocess_log($log_category, $log_text);

            }
        }
        return false;
    }

    /**
     * @param \Modules\PBX\Models\PbxAnveoCallModel $model
     */
    public static function bindCallToOrderSecond($model = null)
    {
        if ( $model->e164 || $model->file ){

            $e164 = $model->e164 ?: self::parseE164($model->file);

            $e164 = substr($e164, -10);

            $qs = OrderModel::objects()->getQuerySet();
            if ( $order_models = OrderModel::objects()
                                           ->filter([(new Expression("SUBSTRING({$qs->getTableAlias()}.phone, -10)"))->toSQL() => $e164])
                                           ->order(['-date'])
                                           ->limit(5)
                                           ->all() )
            {
                $relevance_order = 0;
                for ($i = 0; $i < count ($order_models); $i++){

                    $relevance_order += 10;

                    $mass = [
                        'call_id' => $model->id,
                        'order_id' => $order_models[$i]->orderid,
                        'relevance_type' => OrdersCallsModel::ORDER_PHONE_EQUALS_CALLED_PHONE,
                        'relevance_order' => $relevance_order
                    ];

                    (new OrdersCallsModel($mass))->save();

                    $log_category = "anveo_calls";
                    $log_text = "{$e164} - Привязан к заказу - {$order_models[$i]->order_id} по второй привязке";
                    func_backprocess_log($log_category, $log_text);
                }
            }

            $log_category = "anveo_calls";
            $log_text = "{$e164} - Не привязан к заказу по второй привязке";
            func_backprocess_log($log_category, $log_text);
        }
    }

    /**
     * @param \Modules\PBX\Models\PbxAnveoCallModel $model
     */
    public static function bindCallToOrderThird($model = null)
    {
        if ($model && $model->start_at && $model->end_at){

            if ($model->file) {
                $account = self::parseAccount($model->file);
                $e164 = self::parseE164($model->file);

                if ($account && $model->anveo_account != $account) {

                    $model->anveo_account = $account;
                    $model->save(['anveo_account']);
                }
            }

            if ($model->anveo_account && $model->options && $user_model = $model->options->user) {

                $filter = [
                    'user_id__isnt' => $user_model->id,
                    'created_at__gte' => $model->start_at,
                    'created_at__lte' => $model->end_at,
                ];

                if ($oua_models = OrderUserActivityModel::objects()->filter($filter)->order(['-created_at'])->limit(5)->all()) {

                    if (count($oua_models) > 0 && count($oua_models) < 2){
                        $manager = OrdersCallsModel::objects();

                        foreach ($oua_models as $oua_model) {
                            $manager->updateOrCreate(['call_id' => $model->id, 'order_id' => $oua_model->order_id],
                                                     ['relevance_type' => OrdersCallsModel::TYPE_VIEWING_OTHER_OPERATOR, 'relevance_order' => 20]);

                            $log_category = "anveo_calls";
                            $log_text = "{$e164} - Привязан к заказу - {$oua_model->order_id} по третьей связке";
                            func_backprocess_log($log_category, $log_text);
                        }
                    }
                }

                $log_category = "anveo_calls";
                $log_text = "{$e164} - Не привязан к заказу по третьей привязке";
                func_backprocess_log($log_category, $log_text);
            }
        }
    }

    public static function reValidate()
    {
        $models = PbxAnveoCallModel::objects()->filter(['orders__through__order_id__isnull' => true])->order([(rand(0,1) ?'': '-') .'id'])->limit(20)->all();

        foreach ($models as $model) {
            self::bindCallToOrder($model);
        }
    }

    public static function parseAccount($file)
    {
        $account = null;
        $file_parts = explode('-', $file);

        if (empty($file_parts[4]) || $file_parts[4] == "na") {
            return null;
        }
        else {
            return $file_parts[4];
        }
    }

    public static function parseE164($file)
    {
        $e164 = null;
        $file_parts = explode('-', $file);

        if (!empty($file_parts[5])) {

            if (preg_match('/(.*)\..*/', $file_parts[5], $matches)) {
                $e164 = $matches[1];
            }
            else {
                $e164 = $file_parts[5];
            }
        }

        return $e164;
    }

    public static function getResource($order_id)
    {
        $result = [];

        if ( $order_calls_models = OrdersCallsModel::objects()->filter(['order_id' => $order_id])->all() ) {
            foreach ($order_calls_models as $order_calls_model) {

                /** @var OrdersCallsModel $order_calls_model */
                /** @var PbxAnveoCallModel $anveo_call_model */
                $anveo_call_model = $order_calls_model->call;

                $mass = [
                   'account' =>  self::parseAccount($anveo_call_model->file),
                   'e164' => self::parseE164($anveo_call_model->file),
                   'url' => $anveo_call_model->getUrl(),
                   'start_at' => $anveo_call_model->start_at,
                   'end_at' => $anveo_call_model->end_at,
                   'cname' => $anveo_call_model->cname
                ];

                if ($anveo_call_model->isOutgoing()){
                    $mass['direction'] = "Out";
                } else {
                    $mass['direction'] = "In";
                }

                $datetime1 = new \DateTime($anveo_call_model->end_at);
                $datetime2 = new \DateTime($anveo_call_model->start_at);
                $interval = $datetime1->diff($datetime2);

                $mass['diff'] = $interval->format('%H:%I:%S');
                $mass['type'] = $order_calls_model->getField('relevance_type')->toText();
                $mass['relevance_order'] = $order_calls_model->relevance_order;

                $result[] = $mass;
            }
        }
        if (!empty($result)){
            return $result;
        }
    }
}