<?php
namespace Modules\PBX\Helpers;

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
        self::bindCallToOrder($model);

        if (rand(0, 6) >= 5) {
            self::reValidate();
        }
    }

    /**
     * @param \Modules\PBX\Models\PbxAnveoCallModel $model
     */
    public static function bindCallToOrder($model = null)
    {
        if ($model && $model->start_at && $model->end_at)
        {
            if ($model->file) {
                $account = self::parseAccount($model->file);

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
                    }
                }


            } else
            {
                self::bindCallToOrderSecond($model);
            }
        }
    }

    /**
     * @param \Modules\PBX\Models\PbxAnveoCallModel $model
     */
    public static function bindCallToOrderSecond($model = null){

        if ( $model->e164 || $model->file ){

            $e164 = $model->e164 ?: self::parseE164($model->file);

            if ( $order_models = OrderModel::objects()->filter(['phone' => $e164])->order(['date'])->limit(5)->all() )
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
                }
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
}