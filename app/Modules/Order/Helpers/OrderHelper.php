<?php
namespace Modules\Order\Helpers;

use DateTime;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\Q\QOr;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Forms\Helpers\SnippetHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\AttentionTagModel;
use Modules\Order\Models\OrderAdditionalTagLinkModel;
use Modules\Order\Models\OrderEventsModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\OrderUserLastActivityModel;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Payment\Helpers\PaymentHelper;
use Modules\Sites\Models\SiteModel;
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
    public static function changeOrderCBStatus(OrderModel $model, $status):array
    {
        $log = null;
        $send = false;
        if ($model->groups) {
            /** @var OrderGroupModel $group */
            foreach ($model->groups as $group) {
                if (\in_array($group->cb_status,
                    [
                        OrderStatusModel::ORDER_STATUS_QUEUED,
                        OrderStatusModel::ORDER_STATUS_UNPAID,
                        OrderStatusModel::ORDER_STATUS_NOT_FINISHED,
                        OrderStatusModel::ORDER_STATUS_CANCELED,
                        OrderStatusModel::ORDER_STATUS_FAILED,
                        OrderStatusModel::ORDER_STATUS_DECLINED,
                    ],true)) {
                    if ($group->cb_status !== $status) {
                        $log = "<br/><b>{$group->manufacturer->code}:</b> cb_status: {$group->cb_status_model->name} -> " . OrderStatusModel::objects()->get(['code' => $status])->name;
                    }
                    $send = true;
                    $group->cb_status = $status;
                    $group->save();
                }
            }
            if ($send && $model->cb_status !== $status) {
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
                    && \in_array($a->transaction_status, [
                        OrderTransactionModel::STATUS_AUTHORIZED,
                        OrderTransactionModel::STATUS_PARTIALLY_CAPTURED,
                        OrderTransactionModel::STATUS_PENDING
                    ], true));
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

                $log .= "<br />{$trStore->log}";
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

    public static function validateForm(array $post_data = []): array
    {
        $errors = [];

        if ($post_data) {
            foreach ($post_data as $f_c => $values) {
                /** @var BaseForm $form */
                if ($form = static::getForm($f_c))
                {
                    if (!$form->populate($post_data)->isValid()) {
                        $errors[$f_c] = $form->getErrors();
                    }
                }
            }
        }

        return $errors;
    }

    public static function getForm(string $form)
    {
        $f_class = "Modules\\Order\\Forms\\$form";
        /** @var BaseForm $form */
        if (class_exists($f_class)) {
            return new $f_class;
        }

        return null;
    }

    public static function getOTRSMessages(OrderModel $model) : int
    {
        $ticket_resolver_messages = 0;
        $url = 'http://helpdesk.s3stores.com/otrs/index.pl';
        $TicketConnector_link = 'http://helpdesk.s3stores.com/otrs/nph-genericinterface.pl/Webservice/TicketConnector';

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
                    'xcart', '@Pp6Lcg^VNMC',
                    $TicketConnector_link,
                    'otrs-soap',
                    '%s',
                    'http://helpdesk.s3stores.com/otrs/index.pl?Action=AgentTicketZoom;TicketID=%d'
                );
                $ticket_resolver = $resolver->fetch_ticket_info($model->getOrderNumber());
                if (!empty($ticket_resolver[0]['url'])) {
                    $ticket_resolver_link = $ticket_resolver[0]['url'];

                    if (!empty($ticket_resolver[0]['messages'])) {
                        $ticket_resolver_messages = $ticket_resolver[0]['messages'];

                        $t_arr = Xcart::app()->cache->get('ticket_resolver_messages', []);
                        $t_arr[$model->orderid] = $ticket_resolver_messages;
                        Xcart::app()->cache->set('ticket_resolver_messages', $t_arr);
                    }
                    $model->update(['otrs_ticket' => $ticket_resolver_link]);
                    $model->save();
                }
            }
        }
        return $ticket_resolver_messages;
    }

    public static function getCartOrder() :? OrderModel
    {
        /** @var OrderModel $res */
        $cart = Xcart::app()->cart;
        if ($cart->getCartNumber() && !$cart->getIsEmpty()) {
            $res = OrderModel::objects()->order(['-orderid'])->limit(1)->get([
                'cart_number' => $cart->getCartNumber(),
            ]);
        }
        return $res ?? null;
    }

    public static function orderStepsReset($cart_number): void
    {
        if ($order = OrderModel::objects()->order(['-orderid'])->limit(1)->get(['cart_number' => $cart_number])) {
            $order->cb_status = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1;
            $order->save();
        }
    }

    public static function getOrderHash(array $data = []): string
    {
        return md5(implode('', $data));
    }

    public static function hasCustomerSiblingsOrders(OrderModel $order, $hours = 12): bool
    {
        return OrderModel::objects()->filter([
            'cb_status__in' => [OrderStatusModel::ORDER_STATUS_COMPLETED, OrderStatusModel::ORDER_STATUS_AUTHORIZED, OrderStatusModel::ORDER_STATUS_QUEUED],
            'date__gte' =>  new Expression("UNIX_TIMESTAMP(DATE_SUB(FROM_UNIXTIME({$order->date}), INTERVAL {$hours} HOUR))"),
            'date__lte' =>  new Expression("UNIX_TIMESTAMP(DATE_ADD(FROM_UNIXTIME({$order->date}), INTERVAL {$hours} HOUR))"),
            'email' => $order->email,
            'orderid__isnt' => $order->orderid
        ])->count() > 0 ;
    }

    public static function genReceivedConfirmation(OrderGroupModel $orderGroup): string
    {
        $url = 'http://www.s3stores.com/index.php';
        $secure_check = $orderGroup->orderid . $orderGroup->manufacturerid;
        $secure_check = text_crypt($secure_check);
        $params = [
            'pageid' => 42,
            's' => $secure_check,
            'o' => $orderGroup->orderid,
            'm' => $orderGroup->manufacturerid
        ];
        $query = http_build_query($params);

        $result = <<<HTML
<a href='{$url}?{$query}'>
<img src='https://{$orderGroup->order->site->domain}/skin1_kolin/images/received_img.png' alt='Please click to confirm that you received this order'/>
</a>
HTML;
        return $result;
    }

    public static function checkOrderTrackedAll(OrderModel $order)
    {
        $all =true;
        /** @var OrderGroupModel $group */
        foreach ($order->groups as $group) {
            if (!$group->trackings->count()) {
                $all = false;
                break;
            }
        }
        if ($all) {
            if ($order->tracking_all_filled !== 'Y') {
                $order->tracking_fill_time = time();
            }
            $order->tracking_all_filled = 'Y';
        } else {
            $order->tracking_all_filled = 'N';
        }
        $order->save();
    }

    public static function fetchMap($zipcode): ?string
    {
        $client = new \Goutte\Client(['timeout' => 5]);
        $url = 'https://www.ups.com/maps/results';
        $data = ['loc' => 'en_US', 'zip' => $zipcode, 'stype' => 'O'];
        if (($res = $client->request('POST', $url, $data)) &&
            ($mapUrl = $res->filter('#imgMap')) &&
            $mapUrl->count() &&
            $image = $mapUrl->image()->getUri())
        {
            return $image;
        }
        return null;
    }

    public static function setOrderTag($orderId, $tagId, $isLog = true): ?AttentionTagModel
    {
        /** @var AttentionTagModel $model */
        $model = AttentionTagModel::objects()->get(['status_id' => $tagId]);

        if ($model) {
            [,$created] = OrderAdditionalTagLinkModel::objects()->getOrCreate(['status_id' => $tagId, 'orderid' => $orderId]);
            $message = "Attention tag added: " . $model->status;
            if ($isLog && $created) {
                (new OrderLogModel([
                    'orderid' => $orderId,
                    'type' => OrderLogModel::LOG_TYPE_XCART,
                    'log' => $message,
                    'login' => Xcart::app()->user->login
                ]))->save();
                return $model;
            }
        }
        return null;
    }

    public static function changeOrderStatus(OrderModel $order, $value, $status = 'cb', $sendNotification = false): void
    {
        $sts = "{$status}_status";
        $order->$sts = $value;
        $order->save();
        $order->groups->update([$sts => $value]);
        if ($sendNotification) {
            OrderInvoiceHelper::sendOrderStatusNotification($order);
        }
    }

    public static function getOrderVerificationStatus(OrderModel $order): string
    {
        $max = $min = null;
        foreach ($order->getProducts() as $product) {
            $status_id = (int)$product->verification_statusid;
            $max = max($max, $status_id);
            $min = min($min ?? $status_id, $status_id);
        }

        if ($order->amazon_fulfillment_channel === 'AFN') {
            return OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED;
        }

        if ($min === $max) {
            switch ($max) {
                case (ProductModel::PRODUCT_STATUS_NOT_VERIFY) :
                    return OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_NOT_YET_STARTED;
                case (ProductModel::PRODUCT_STATUS_PROBLEM_NOT_FIXED) :
                case (ProductModel::PRODUCT_STATUS_PROBLEM_FIXED) :
                    return OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND;
                case (ProductModel::PRODUCT_STATUS_VERIFY):
                    return OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED;
            }
        } elseif ($min === ProductModel::PRODUCT_STATUS_NOT_VERIFY && $max > ProductModel::PRODUCT_STATUS_NOT_VERIFY) {
            return OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_IN_PROGRESS;
        } elseif ($min > ProductModel::PRODUCT_STATUS_NOT_VERIFY && $max > ProductModel::PRODUCT_STATUS_NOT_VERIFY) {
            return OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_PROBLEM_FOUND;
        }

        return OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_NOT_YET_STARTED;
    }

    public static function submitOrderEntry(OrderModel $order): void
    {
        foreach ($order->groups as $group) {
            $dx = $group->manufacturer;
            if (($dx->submit_to_operator === 'through_distributor_website') && in_array(
                    $group->cb_status,
                    [
                        OrderStatusModel::ORDER_STATUS_COMPLETED,
                        OrderStatusModel::ORDER_STATUS_UNPAID_PO,
                        OrderStatusModel::ORDER_STATUS_PENDING_PARTIAL_REFUND,
                        OrderStatusModel::ORDER_STATUS_PARTIAL_REFUND,
                        OrderStatusModel::ORDER_STATUS_AUTHORIZED
                    ],
                    true
                ) &&
                in_array(
                    $group->dc_status,
                    [
                        OrderStatusModel::ORDER_DC_STATUS_NOT_SHIPPED,
                        OrderStatusModel::ORDER_DC_STATUS_PENDING_AVAIL_CHECK,
                        OrderStatusModel::ORDER_DC_STATUS_PENDING_ADDL_PAYMENT
                    ],
                    true
                )) {
                $message = SnippetHelper::render(
                    $dx->d_instructions_to_order_entry_operator,
                    ['order' => $order, 'user' => Xcart::app()->user, 'group' => $group]
                );
                $subject = SnippetHelper::render(
                    $dx->d_order_entry_operator_subject_line_8,
                    ['order' => $order, 'user' => Xcart::app()->user, 'group' => $group]
                );

                /** @var SiteModel $site */
                $site = Xcart::app()->getModule('Sites')->getSite();
                $config = $site->getGlobalConfig();

                $log = "The order is AUTOMATICALLY sent to operator for order entry on distributor's website.<br /><b>From: </b>{$config['orders_department']}<br /><b>To: </b>{$dx->d_order_entry_operator_email}<br /><b>Subject: </b>{$subject}";

                (new OrderLogModel(
                    [
                        'orderid' => $order->orderid,
                        'type' => OrderLogModel::LOG_TYPE_SYSTEM,
                        'log' => $log,
                        'login' => Xcart::app()->user->login
                    ]
                ))->save();

                $params = ['from' => $config['orders_department']];

                $emails = explode(',', $dx->d_order_entry_operator_email);
                $email_to = array_shift($emails);
                if ($emails) {
                    $params['bcc'] = array_map(static fn($e) => trim($e), $emails);
                }

                Xcart::app()->mail->raw(
                    $email_to,
                    $subject,
                    $message,
                    $params
                );

                /** @var OrderStatusModel $new_status */
                $new_status = OrderStatusModel::objects()->get(['code' =>  OrderStatusModel::ORDER_STATUS_PENDING_ORDER_ENTRY]);
                $log = "<b>{$dx->code}:</b> dc_status: {$group->dc_status_model} -> {$new_status}";
                (new OrderLogModel(
                    [
                        'orderid' => $order->orderid,
                        'type' => OrderLogModel::LOG_TYPE_XCART,
                        'log' => $log,
                        'login' => Xcart::app()->user->login
                    ]
                ))->save();

                $group->dc_status = OrderStatusModel::ORDER_STATUS_PENDING_ORDER_ENTRY;
                $group->save();
            }
        }
    }

    /**
     * Check possibility to dispatch order entry operator
     * @param OrderModel $order
     * @return bool
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public static function isAllowSendToOrderEntry(OrderModel $order): bool
    {
        if ($order->isAmazon()) {
            return false;
        }

        if ($order->fraud_status !== OrderStatusModel::ORDER_FRAUD_CHECK_STATUS_CLEARED) {
            return false;
        }

        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getGlobalConfig();

        if ($config["Customer_notes_field_is_NOT_empty"] === 'Y' && $order->customer_notes) {
            return false;
        }

        $otrs_messages = self::getOTRSMessages($order);

        if ($config["number_of_OTRS_messages"] === 'Y' && $otrs_messages !== (int)$config["number_of_OTRS_messages_is_NOT_equal_to_value"]) {
            return false;
        }

        if ($config["ETA_date_is_present_for_at_least_one_of_the_items"] === 'Y') {
            foreach ($order->getProducts() as $product) {
                if ($product->eta_date_mm_dd_yyyy > time()) {
                    return false;
                }
            }
        }

        if ($config["Order_shipping_method_carrier"] === 'Y') {
            foreach ($order->groups as $group) {
                if (($group->shippingModel->code ?? '') === 'Amazon') {
                    return false;
                }
            }
        }
        return true;
    }

    public static function changeOrderVerificationStatus(OrderModel $order, string $new_status): void
    {
        /** @var OrderStatusModel $new */
        /** @var OrderStatusModel $old */
        $new = OrderStatusModel::objects()->get(['code' => $new_status]);
        $old = OrderStatusModel::objects()->get(['code' => $order->vn_status]);
        if ($new && $old && $new->code !== $old->code) {
            $order->vn_status = $new->code;
            $order->save();
            (new OrderLogModel(
                [
                    'orderid' => $order->orderid,
                    'type' => OrderLogModel::LOG_TYPE_XCART,
                    'log' => "Verification status: {$old->name} -> {$new->name}",
                    'login' => Xcart::app()->user->login
                ]
            ))->save();
            if ($new_status === OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED && self::isAllowSendToOrderEntry($order)) {
                self::submitOrderEntry($order);
            }
        }
    }
}