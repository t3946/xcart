<?php


namespace Modules\Order\Helpers;


use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\Manager;

class OrderAttentionTagHelper
{
    /**
     * Return list of order Attention Tags to set by user
     * @param OrderModel $order
     * @param UserModel $user
     * @param string|null $action
     * @return Manager
     */
    public static function getOrderAttentionTagsListForUser(OrderModel $order, UserModel $user, string $action = null): Manager
    {
        $filter = [new QOr(['users__user__id' => $user->id, 'users__login' => '_ANY_'])];
        if ($action) {
            $filter += ['users__action' => $action];
        }
        $and = new QAnd($filter);
        $or = new QOr([$and, ['status_id__in' => $order->tags->valuesList(['pk'], true)]]);
        return AttentionTagModel::ordered()->filter([$or])->group(['status_id']);
    }
}