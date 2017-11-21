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
     * @param $model
     */
    public static function bindCallToOrder($model = null)
    {
        if ($model) {
            if (!empty($model->anveo_account)) {
                $anveo_account = $model->anveo_account;
            }
            else {
                $file_parts = explode('-', $model->file);

                if (empty($file_parts[4]) || $file_parts[4] == "na") {
                    exit;
                }
                else {
                    $regexp = '/(.*)\..*/';
                    if (preg_match($regexp, $file_parts[4], $matches)) {
                        $anveo_account = $matches[1];
                    }
                    else {
                        $anveo_account = $file_parts[4];
                    }
                }
            }

            /** @var PbxOptionsModel $pbx_options_model */
            $pbx_options_model = PbxOptionsModel::objects()->get(['anveo_account' => $anveo_account]);

            $pbx_extension = $pbx_options_model->extension;

            /** @var UserModel $user_model */
            if ($user_model = UserModel::objects()
                                       ->get(['pbx_extension' => $pbx_extension, 'usertype' => 'A', 'status' => 'Y'])) {
                $user_id = $user_model->id;

                if ($oua_models = OrderUserActivityModel::objects()
                                                        ->filter(['user_id' => $user_id, 'created_at__gte' => $model->start_at, 'created_at__lte' => $model->end_at])
                                                        ->all()) {
                    foreach ($oua_models as $oua_model) {
                        OrdersCallsModel::objects()
                                        ->updateOrCreate(['call_id' => $model->id, 'order_id' => $oua_model->order_id],
                                                         ['relevance_type' => OrdersCallsModel::TYPE_VIEWING_SAME_OPERATOR, 'relevance_order' => 10]);
                    }
                }

            }

        }
    }

}