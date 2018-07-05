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

                        /** @var OrdersCallsModel $oc_model */
                        [$oc_model] = $manager->getOrNew(['call_id' => $model->id, 'order_id' => $oua_model->order_id, 'relevance_type' => OrdersCallsModel::TYPE_VIEWING_SAME_OPERATOR]);
                        $oc_model->relevance_order = 10;

                        $oc_model->save();

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

            /** @var OrderModel $order_model */
            $qs = OrderModel::objects()->getQuerySet();
            if ( $order_model = OrderModel::objects()
                                           ->filter([(new Expression("SUBSTRING({$qs->getTableAlias()}.phone, -10)"))->toSQL() => $e164])
                                           ->order(['-date'])
                                           ->limit(1)
                                           ->get() )
            {
                $relevance_order = 10;

                $mass = [
                    'call_id' => $model->id,
                    'order_id' => $order_model->orderid,
                    'relevance_type' => OrdersCallsModel::ORDER_PHONE_EQUALS_CALLED_PHONE,
                ];

                /** @var OrdersCallsModel $oc_model */
                [$oc_model, $is_created] = OrdersCallsModel::objects()->getOrNew($mass);

                if ($is_created) {
                    $oc_model->relevance_order = $relevance_order;
                    $oc_model->save();
                }

                $log_category = "anveo_calls";
                $log_text = "{$e164} - Привязан к заказу - {$order_model->orderid} по второй привязке";
                func_backprocess_log($log_category, $log_text);
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

                    if (count($oua_models) > 0 && count($oua_models) < 3){
                        $manager = OrdersCallsModel::objects();

                        foreach ($oua_models as $oua_model) {
                            $manager->updateOrCreate(['call_id' => $model->id, 'order_id' => $oua_model->order_id, 'relevance_type' => OrdersCallsModel::TYPE_VIEWING_OTHER_OPERATOR],
                                                     ['relevance_order' => 20]);

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

        if ( $order_calls_models = OrdersCallsModel::objects()->filter(['order_id' => $order_id])->order(['-call_id'])->all() ) {
            foreach ($order_calls_models as $order_calls_model) {

                /** @var OrdersCallsModel $order_calls_model */
                /** @var PbxAnveoCallModel $anveo_call_model */
                $anveo_call_model = $order_calls_model->call;

                $user = '';
                if ($anveo_call_model->anveo_account) {
                    $user = $anveo_call_model->options->user->firstname;
                }

                $mass = [
                   'account' =>  $user,
                   'e164' => $anveo_call_model->getFrontendE164(),
                   'url' => $anveo_call_model->getUrl(),
                   'start_at' => $anveo_call_model->start_at,
                   'end_at' => $anveo_call_model->end_at,
                   'cname' => $anveo_call_model->cname
                ];

                if ($anveo_call_model->isOutgoing()){
                    $mass['direction'] = "Outbound";
                } else {
                    $mass['direction'] = "Inbound";
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

    public static function addToTitleName($order_id)
    {
        $count = 0;
        $new = 0;

        if ($order_calls = OrdersCallsModel::objects()->filter(['order_id' => $order_id])->all()){

            $count = count($order_calls);
            $now = new \DateTime(date("Y-m-d H:i:s"));
            // DateTime::setTime ( int $hour , int $minute [, int $second = 0 [, int $microseconds = 0 ]] )

            $now->modify("-1 day");

            $now->setTime(0,0,0);

//            if ($count > 1){
                foreach ($order_calls as $order_call){
                    $time = new \DateTime($order_call->call->start_at);
                    $interval = $now->diff($time);
                    if ( $interval->format('%a') < 1) {
                        $new++;
                    }
                }
//            }
        }

        if ($new > 0) {
            return " ({$count} + {$new})";
        } else {
            return " ({$count})";
        }
    }
}