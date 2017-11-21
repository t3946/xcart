<?php
namespace Modules\PBX\Helpers;

use Modules\Order\Models\OrdersCallsModel;
use Modules\Order\Models\OrderUserActivityModel;
use Modules\User\Models\PbxOptionsModel;
use Modules\User\Models\UserModel;

class AnveoAssignCalls
{
    public static function eventBindCallToOrder($sender = null, $model)
    {
        self::bindCallToOrder($model);
    }

    /**
     * @param \Modules\PBX\Models\PbxAnveoCallModel $model
     */
    public static function bindCallToOrder($model = null)
    {
        if ($model && $model->start_at && $model->end_at)
        {
            if (empty($model->anveo_account) && $model->file)
            {
                $file_parts = explode('-', $model->file);

                if (empty($file_parts[4]) || $file_parts[4] == "na") {
                    exit;
                }
                else {

                    if (preg_match('/(.*)\..*/', $file_parts[4], $matches)) {
                        $model->anveo_account = $matches[1];
                    }
                    else {
                        $model->anveo_account = $file_parts[4];
                    }

                    $model->save(['anveo_account']);
                }
            }

            if ($model->anveo_account && $model->options && $user_id = $model->options->user->id)
            {
                $filter = [
                    'user_id' => $user_id,
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
            }
        }
    }
}