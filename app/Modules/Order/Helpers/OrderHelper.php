<?php
namespace Modules\Order\Helpers;

use DateTime;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\Q\QOr;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Order\Forms\ShippingAddressForm;
use Modules\Order\Models\OrderEventsModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\OrderUserLastActivityModel;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\User\Models\UserModel;
use Xcart\App\Form\BaseForm;
use Xcart\App\Main\Xcart;
use Xcart\OrderToTicketResolver;

class OrderHelper
{
    protected static $__events_count = [];
    protected static $__max_eta = [];

    public static function getMaxEtaTimeByOrder(array $ids)
    {
        $keys = array_keys(self::$__max_eta);
        $diff = array_diff($ids, $keys);

        if (!empty($diff)) {
            $connection = Xcart::app()->db->getConnection();
            $max_eta_sql = QueryBuilder::getInstance($connection)->from('xcart_products')
                                       ->select(['max_eta' => new Expression('MAX(t.eta_date_mm_dd_yyyy)'), 'details.orderid'])
                                       ->setAlias('t')
                                       ->join('inner join', 'xcart_order_details', ['t.productid' => 'details.productid'], 'details')
                                       ->where(['details.orderid__in' => $diff, 'eta_date_mm_dd_yyyy__gt' => 0])
                                       ->group(['details.orderid'])->toSQL();

            $orders_max_eta = $connection->fetchAll($max_eta_sql);

            foreach ($orders_max_eta as $item) {
                self::$__max_eta[$item['orderid']] = $item['max_eta'];
            }
        }

        $result = [];
        foreach (self::$__max_eta as $id => $eta) {
            if (in_array($id, $ids)) {

                $result[$id] = $eta;
            }
        }

        return $result;
    }


    public static function getCountEvents(array $ids, $user_id = null, $group = true)
    {
        $need_request = false;
        $userModel = null;

        if (empty($user_id) && Xcart::app()->getIsWebMode())
        {
            $userModel = Xcart::app()->getUser();
            $user_id = $userModel->id;
        }

        foreach ($ids as $id) {
            $need_request = !isset(self::$__events_count[$id]) || !isset(self::$__events_count[$id][$user_id]);

            if ($need_request) {
                break;
            }
        }

        if ($need_request && !$userModel) {
            $userModel = UserModel::objects()->get(['id' => $user_id]);
        }

        if ($need_request && $user_id && $userModel && $userModel->show_events) {

            $connection = Xcart::app()->db->getConnection();

            $min_date = ($userModel->show_events_min_date) ? (new DateTime($userModel->show_events_min_date)) : null;

            $qs = static::getCountEventsQS($user_id, $min_date);

            $sql = $qs->filter(['order_id__in' => $ids,])
                      ->group(["order_id"])
                      ->allSql();

            $counts = $connection->fetchAll($sql);
            if ($counts) {
                foreach ($counts as $item) {
                    self::$__events_count[$item['order_id']][$user_id] = $item['count'];
                }
            }
            foreach ($ids as $id) {
                if (empty(self::$__events_count[$id])) {
                    self::$__events_count[$id][$user_id] = 0;
                }
            }
        }

        $result = [];
        foreach (self::$__events_count as $id => $user_count) {
            if (in_array($id, $ids)) {

                $result[$id] = $user_count[$user_id];
            }
        }

        return ($group) ? $result : array_sum($result);
    }

    /**
     * Return QuerySet without order filtrate
     *
     * @param int $user_id
     * @param null|\DateTime $min_show_date Minimal date for show event
     *
     * @return \Xcart\App\Orm\Manager
     */
    public static function getCountEventsQS($user_id, $min_show_date = null)
    {
        $qs = OrderEventsModel::objects();
        $topAlias = $qs->getTableAlias();

        if ($min_show_date && $min_show_date instanceof \DateTime) {
            $qs = $qs->filter(['created_at__gte' => $min_show_date]);
        }

        $qs = $qs
            ->filter([
                         new QAnd(['created_at__gte' => (new \DateTime())->modify('-6 month'),]),
                         new QOr([
                                     new QAnd(['a.user_id' => $user_id, new QAnd(new Expression("`{$topAlias}`.`created_at` >= `a`.`created_at`"))]),
                                     'a.user_id__isnull' => true
                                 ]),
                         new QAndNot(['user_id' => $user_id,]),
                     ])
            ->getQuerySet()
            ->join('left join', OrderUserLastActivityModel::tableName(), ['a.order_id' => 'order_id', 'a.user_id' => new Expression($user_id)], 'a')
            ->select(['order_id', 'count' => new Expression('count(*)')]);

        return $qs;
    }

    public static function getCountEventsActiveUserQS()
    {
        $userModel = Xcart::app()->getUser();
        $min_date = ($userModel->show_events_min_date) ? (new DateTime($userModel->show_events_min_date)) : null;

        return static::getCountEventsQS($userModel->pk, $min_date);
    }

    /**
     * Return Log string
     *
     * @param OrderModel $model
     * @param $status
     *
     * @return array
     */
    public static function changeOrderCBStatus(OrderModel $model, $status)
    {
        $log = null;
        $send = false;
        if ($model->groups) {
            /** @var OrderGroupModel $group */
            foreach ($model->groups as $group) {
                if (in_array($group->cb_status, ['Q', 'N', 'I'])) {
                    if ($group->cb_status != $status) {
                        $log = "<br/><b>" . $group->manufacturer->code . ":</b> cb_status: " . $group->cb_status_model->name
                               . " -> " . OrderStatusModel::objects()->get(['code' => $status])->name;
                    }
                    $send = true;
                    $group->cb_status = $status;
                    $group->save();
                }
            }
            if ($send && $model->cb_status != $status) {
                $model->cb_status = $status;
                $model->save();
            }
        }
        return [$log, $send];
    }

    public static function cancelOrder($order_id)
    {
        $log = null;

        if ($order_model = OrderModel::objects()->get(['orderid' => $order_id])) {

            $auth_transactions = array_filter($order_model->transactions->all(), function ($a) {
                return ($a->type == OrderTransactionModel::TYPE_AUTHORIZATION
                    && in_array($a->transaction_status,
                        [
                            OrderTransactionModel::STATUS_AUTHORIZED,
                            OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,
                            OrderTransactionModel::STATUS_PENDING
                        ]
                    ));
            });
            foreach ($auth_transactions as $auth_tr) {
                $amount = [
                    'amount' => number_format($auth_tr->transaction_amount, 2),
                    'currency' => $auth_tr->transaction_currency,
                ];
                $params = array_merge(PaymentHelper::getPaymentParams($auth_tr, $amount),
                    [
                        'mode' => 'void',
                        'new_method_model' => $auth_tr->payment_method_model,
                        'order' => $order_model,
                        'orderTransaction' => $auth_tr,
                    ]
                );

                $trStore = new OrderTransactionStore($params, $auth_tr);
                $model = $trStore->void();

                $log .= "<br />" . $trStore->log;
            }
        }

        return $log;
    }

    public static function getSubmitOperator(): ?UserModel
    {
        /** @var UserModel $user */

        $user = null;

        if (($user_ids = Xcart::app()->request->session->get('identifiers')) && ($login = $user_ids['A'] ?: null) && !empty($login['login'])) {

            $user = UserModel::objects()->filter(['login' => $login['login']])->limit(1)->get();
        }


        return $user;
    }

    public static function isValidShippingAddress(array $post_data = []): array
    {
        $errors = [];

        if ($post_data) {
            foreach ($post_data as $f_c => $values) {
                $f_class = "Modules\\Order\\Forms\\$f_c";
                /** @var BaseForm $form */
                if (class_exists($f_class)) {
                    $form = new $f_class;
                    if (!$form->populate($post_data)->isValid()) {
                        $errors[$f_c] = $form->getErrors();
                    }
                }
            }
        }

        return $errors;
    }

    public static function getOTRSMessages(OrderModel $model) : int
    {
        $ticket_resolver_messages = 0;
        $url = "http://helpdesk.s3stores.com/otrs/index.pl";
        $TicketConnector_link = "http://helpdesk.s3stores.com/otrs/nph-genericinterface.pl/Webservice/TicketConnector";

        if ($model) {

            $curl_err = false;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
            curl_exec($ch);

            if (curl_errno($ch) != 0 || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                $curl_err = true;
            }
            curl_close($ch);

            if (!$curl_err) {
                $resolver = new OrderToTicketResolver(
                    "xcart", "@Pp6Lcg^VNMC",
                    $TicketConnector_link,
                    "otrs-soap",
                    "%s",
                    "http://helpdesk.s3stores.com/otrs/index.pl?Action=AgentTicketZoom;TicketID=%d"
                );
                $ticket_resolver = $resolver->fetch_ticket_info($model->getOrderNumber());
                if (!empty($ticket_resolver[0]["url"])) {
                    $ticket_resolver_link = $ticket_resolver[0]["url"];

                    if (!empty($ticket_resolver[0]["messages"])) {
                        $ticket_resolver_messages = $ticket_resolver[0]["messages"];

                        $t_arr = Xcart::app()->cache->get('ticket_resolver_messages', []);
                        $t_arr [$model->orderid] = $ticket_resolver_messages;
                        Xcart::app()->cache->set('ticket_resolver_messages', $t_arr);
                    }
                    $model->otrs_ticket = $ticket_resolver_link;
                }
            }
        }
        return $ticket_resolver_messages;
    }

    public static function getCartOrder() :? OrderModel
    {
        $order = null;
        $cart = Xcart::app()->cart;

        /** @var OrderModel $order */

        if ($cart->getCartNumber() && !$cart->getIsEmpty()) {
            $order = OrderModel::objects()->get([
                'cart_number' => $cart->getCartNumber(),
            ]);
        }

        return $order;
    }
}