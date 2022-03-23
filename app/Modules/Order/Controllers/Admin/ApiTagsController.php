<?php

namespace Modules\Order\Controllers\Admin;

use Modules\Admin\Controllers\BackendController;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\AttentionTagUserModel;
use Xcart\App\Main\Xcart;

class ApiTagsController extends BackendController
{
    public function actionAdd($order_id, $status_id): void
    {
        $login = Xcart::app()->getUser()->login;

        /** @var AttentionTagModel $status */
        $status = AttentionTagModel::objects()->get(['status_id' => $status_id]);

        $allowed_to_set_flag = AttentionTagUserModel::objects()->filter(
                ['status_id' => $status_id, 'action' => 'set', 'login__in' => [$login, '_ANY_']]
            )->count() > 0;

        if ($allowed_to_set_flag) {
            OrderTagEventHelper::orderTagEvent($status_id, $order_id, true);

            $this->jsonResponse([
                'content' => 'Attention tag has been added!',
                'type' => 'success',
            ]);

            die();
        }

        $this->jsonResponse([
            'content' => "You cannot add the '$status->status' tag.",
            'type' => 'error',
        ]);
    }

    public function actionDel($order_id, $status_id): void
    {
        $login = Xcart::app()->getUser()->login;

        /** @var AttentionTagModel $status */
        $status = AttentionTagModel::objects()->get(['status_id' => $status_id]);

        $allowed_to_unset_flag = AttentionTagUserModel::objects()->filter(
                ['status_id' => $status_id, 'action' => 'unset', 'login__in' => [$login, '_ANY_']]
            )->count() > 0;

        if ($allowed_to_unset_flag) {
            OrderHelper::unsetOrderTag($order_id, $status_id, true);

            $this->jsonResponse([
                'content' => 'Attn tag has been removed',
                'type' => 'success',
            ]);

            die();
        }

        $this->jsonResponse([
            'content' => "You cannot remove the '$status->status' tag.",
            'type' => 'error',
        ]);
    }
}