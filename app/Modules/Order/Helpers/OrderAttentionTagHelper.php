<?php


namespace Modules\Order\Helpers;


use Xcart\App\QueryBuilder\Q\QAnd;
use Xcart\App\QueryBuilder\Q\QOr;
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
        $filter = $order->tags->count() ? new QOr([new QAnd($filter), ['status_id__in' => $order->tags->valuesList(['pk'], true)]]) : new QAnd($filter);

        return AttentionTagModel::ordered()->filter([$filter])->group(['status_id']);
    }
}