<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.2017
 * Time: 11:26
 */

namespace Modules\Dashboard\Stores;

use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\Q\QOr;
use Xcart\App\Store\BaseStore;
use Xcart\Order;

class OrderSearchStore extends BaseStore
{
    const CONST_MANUAL_STRING = '=> ';

    public static function getFeatures()
    {
        return [
            'mobile_added'     => 'Orders with products added via mobile-storefront',
            'gc_applied'       => 'Entirely or partially paid by Gift Certificate',
            'discount_applied' => 'Global discount applied',
            'coupon_applied'   => 'Discount coupon applied',
            'free_ship'        => 'Free shipping',
            'free_tax'         => 'Tax exempt',
            'gc_ordered'       => 'Gift Certificates purchased',
            'notes'            => 'Orders that have notes assigned',
        ];
    }

    public static function getSources()
    {
        return [
            'xcart_orders_only'  => 'S3 Stores websites',
            'amazon_orders_only' => 'Amazon website',
            'amazon_orders_MFN'  => 'Amazon - MFN',
            'amazon_orders_FBA'  => 'Amazon - FBA',
        ];
    }

    public static function getQuestionStatuses()
    {
        return [
            "question_received_from_cust"  => "Question received from customer",
            "question_sent_to_distr_brand" => "Question sent to distributor/brand",
            "call_distributor_brand"       => "Call distributor/brand",
            "answer_sent_to_cust"          => "Answer sent to customer",
            "order_pending"                => "Order pending",
            "closed"                       => "Closed",
        ];
    }

    /**
     * @param array $data
     *
     * @return \Xcart\App\Orm\QuerySet
     */
    public function populate(array $data)
    {
        $data = $this->clearRecursive($data);

        $where = [];
        $exclude = [];

        $qs = Order::objects()->getQuerySet();

        if (!empty($data['order']))
        {
            if (!empty($data['order']['date'])) {
                $date = explode(' - ', $data['order']['date']);

                if (isset($date[1])) {
                    $date[0] = strtotime($date[0]);
                    $date[1] = strtotime($date[1]);
                }
                else {
                    $date[0] = strtotime($date[0]);
                    $date[1] = $date[0];
                }

                $where['date__gte'] = $date[0];
                $where['date__lte'] = $date[1] + 86400; //24 * 60 * 60;
            }

            if (!empty($data['order']['total'])) {
                if (!empty($data['order']['total']['from']) && !empty($data['order']['total']['to'])) {
                    $where['total__gte'] = $data['order']['total']['from'];
                    $where['total__lte'] = $data['order']['total']['to'];
                }
                else {
                    $total = !empty($data['order']['total']['from']) ? $data['order']['total']['from'] : $data['order']['total']['to'];

                    if (!empty($total)) {
                        $where['total'] = $total;
                    }
                }
            }

            if (!empty($data['order']['id'])) {
                if (!empty($data['order']['id']['from']) && !empty($data['order']['id']['to'])) {
                    $where['orderid__gte'] = $data['order']['id']['from'];
                    $where['orderid__lte'] = $data['order']['id']['to'];

                }
                else {
                    $orderid = !empty($data['order']['id']['from']) ? $data['order']['id']['from'] : $data['order']['id']['to'];

                    if (!empty($orderid)) {
                        $where['orderid'] = $orderid;
                    }
                }
            }

            if (!empty($data['order']['source'])) {
                foreach ($data['order']['source'] as $source)
                {
                    switch ($source)
                    {
                        case 'xcart_orders_only': {
                            $where['amazonorderid'] = ''; break;
                        }
                        case 'amazon_orders_only': {
                            $where[] = new QAndNot(['amazonorderid' => '']); break;
                        }
                        case 'amazon_orders_MFN' : {
                            $where['amazon_fulfillment_channel__in'][] = 'MFN'; break;
                        }
                        case 'amazon_orders_FBA' : {
                            $where['amazon_fulfillment_channel__in'][] = 'AFN'; break;
                        }
                    }
                }
            }

            if (!empty($data['order']['operator'])) {
                $qs->join('inner join', 'xcart_order_logs', ['t.orderid' => 'logs.orderid'], 'logs');
                $where[] = new QOr([
                    'login_last_opened_or_saved__in' => $data['order']['operator'],
                    'logs.login__in' => $data['order']['operator'],
                                   ]);

//                $where['login_last_opened_or_saved__in'] = $data['order']['operator'];
            }

            if (!empty($data['order']['payment_method'])) {
                $where['paymentid__in'] = $data['order']['payment_method'];
            }

            if (!empty($data['order']['delivery_method'])) {
                $qs->join('inner join', 'xcart_order_groups', ['t.orderid' => 'group.orderid'], 'group');
                $where['group.shippingid__in'] = $data['order']['delivery_method'];
            }

            if (!empty($data['order']['c2b_status'])) {
                $qs->join('inner join', 'xcart_order_groups', ['t.orderid' => 'group.orderid'], 'group');
                $where['group.cb_status__in'] = $data['order']['c2b_status'];
            }

            if (!empty($data['order']['d2c_status'])) {
                $qs->join('inner join', 'xcart_order_groups', ['t.orderid' => 'group.orderid'], 'group');
                $where['group.dc_status__in'] = $data['order']['d2c_status'];
            }

            if (!empty($data['order']['po_transit_status'])) {
                $qs->join('inner join', 'xcart_order_groups', ['t.orderid' => 'group.orderid'], 'group');
                $where['group.po_status__in'] = $data['order']['po_transit_status'];
            }

            if (!empty($data['order']['fraud_status'])) {
                $where['fraud_status__in'] = $data['order']['fraud_status'];
            }

            if (!empty($data['order']['tag'])) {
                $qs->join('inner join', 'xcart_orders_additional_tags', ['t.orderid' => 'tagl.orderid'], 'tagl');
                $where['tagl.status_id__in'] = $data['order']['tag'];
            }

            if (!empty($data['order']['distributor'])) {
                $qs->join('inner join', 'xcart_order_groups', ['t.orderid' => 'group.orderid'], 'group');
                $where['group.manufacturerid__in'] = $data['order']['distributor'];
            }

            if (!empty($data['order']['vn_status'])) {
                $where['group.vn_status__in'] = $data['order']['vn_status'];
            }

            if (!empty($data['order']['po_status'])) {
                $qs->join('inner join', 'xcart_po_pipeline', ['t.orderid' => 'po.order_id'], 'po');
                $where['po.status__in'] = $data['order']['po_status'];
            }
        }

        if (!empty($data["features"])) {
            foreach ($data["features"] as $feature)
            {
                switch ($feature) {
                    case 'gc_applied': {
                        $where['giftcert_discount__gt'] = 0; break;
                    }
                    case 'discount_applied': {
                        $where['discount__gt'] = 0; break;
                    }
                    case 'coupon_applied' : {
                        $exclude['coupon'] = ''; break;
                    }
                    case 'free_ship' : {
                        $where['shipping_cost'] = 0; break;
                    }
                    case 'free_tax' : {
                        $where['tax'] = 0; break;
                    }
                    case 'notes' : {
                        $exclude['notes'] = ''; break;
                    }
                    case 'gc_ordered' : {
                        $qs->join('inner join', 'xcart_giftcerts', ['t.orderid' => 'sert.orderid'], 'sert'); break;
                    }
                }
            }
        }

        if (!empty($data['product']))
        {
            if (!empty($data['product']['name'])) {
                $qs->join('inner join', 'xcart_order_details', ['t.orderid' => 'details.orderid'], 'details');
//                $where[] = new QOr([
//                                       'details.product__contains'         => $data['product']['name'],
//                                       'details.product_options__contains' => $data['product']['name'],
//                                       new QAnd(array_map(function ($word) {
//                                           return new QAnd(['details.product__contains' => $word]);
//                                       }, explode(' ', $data['product']['name']))),
//                                   ]);
                $where[] = new QAnd(array_map(function ($word) {
                    return new QOr([
                                       'details.product__contains'         => $word,
                                       'details.product_options__contains' => $word,
                                   ]);
                }, explode(' ', $data['product']['name'])));
            }

            if (!empty($data['product']['sku'])) {
                $qs->join('inner join', 'xcart_order_details', ['t.orderid' => 'details.orderid'], 'details');
                $where['details.productcode__contains'] = $data['product']['sku'];
            }

            if (!empty($data['product']['id'])) {
                $qs->join('inner join', 'xcart_order_details', ['t.orderid' => 'details.orderid'], 'details');
                $where['details.productid'] = $data['product']['id'];
            }

            if (!empty($data['product']['question_status'])) {
                $qs->join('inner join', 'xcart_order_details', ['t.orderid' => 'details.orderid'], 'details');
                $qs->join('inner join', 'xcart_product_question', ['details.productid' => 'question.productid'], 'question');
                $where['question.status__in'] = $data['product']['question_status'];
            }
        }

        if (!empty($data['customer']))
        {
            if (!empty($data['customer']['company']))
            {
                $tmp = [];
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_company__in'] = $data['customer']['company'];
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_company__in'] = $data['customer']['company'];
                }
                $tmp['company__in'] = $data['customer']['company'];
                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['city']))
            {
                $tmp = [];
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_city__in'] = $data['customer']['city'];
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_city__in'] = $data['customer']['city'];
                }
                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['state']))
            {
                $tmp = [];
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_state__in'] = $data['customer']['state'];
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_state__in'] = $data['customer']['state'];
                }
                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['country']))
            {
                $tmp = [];
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_country__in'] = $data['customer']['country'];
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_country__in'] = $data['customer']['country'];
                }
                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['address']))
            {
                $tmp = [];
                list($in, $like) = $this->explodeInOrLike($data['customer']['address']);

                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    if (!empty($in)) {
                        $tmp['b_address__in'] = $in;
                    }
                    if (!empty($like)) {
                        $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 'b_address'));
                    }
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    if (!empty($in)) {
                        $tmp['s_address__in'] = $in;
                    }
                    if (!empty($like)) {
                        $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 's_address'));
                    }
                }
                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['zip_code']))
            {
                $tmp = [];
                list($in, $like) = $this->explodeInOrLike($data['customer']['zip_code']);

                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    if (!empty($in)) {
                        $tmp['b_zipcode__in'] = $in;
                    }
                    if (!empty($like)) {
                        $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 'b_zipcode'));
                    }
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    if (!empty($in)) {
                        $tmp['s_zipcode__in'] = $in;
                    }
                    if (!empty($like)) {
                        $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 's_zipcode'));
                    }
                }
                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['email']))
            {
                $tmp = [];
                list($in, $like) = $this->explodeInOrLike($data['customer']['email']);

                if (!empty($in)) {
                    $tmp['email__in'] = $in;
                }
                if (!empty($like)) {
                    $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 'email'));
                }

                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['phone']))
            {
                $tmp = [];
                list($in, $like) = $this->explodeInOrLike($data['customer']['phone']);

                if (!empty($in)) {
                    $tmp['phone__in'] = $in;
                }
                if (!empty($like)) {
                    foreach ($like as $t)
                    {
                        $tmp[] = new QAnd(['phone__raw' => "RLIKE '" . self::getPhoneRegexp($t) ."'"]);
                    }

                    $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 'phone'));
                }

                $where[] = new QOr($tmp);
            }

            if (!empty($data['customer']['name']))
            {
                $where[] = new QOr(['firstname__contains'   => $data['customer']['name'],
                                    'b_firstname__contains' => $data['customer']['name'],
                                    's_firstname__contains' => $data['customer']['name'],
                                   ]);
            }


        }


        $qs->filter($where)->exclude($exclude);

        if (count($qs->getJoins()) !== 0) {
            $qs->group([ $qs->getAlias() . '.orderid']);
        }

        func_dump($qs->getSql());

        return $qs;
    }

    private function arrLikeToLookup($data, $field)
    {
        foreach ($data as $k => $v)
        {
            $data[$k] = new QOr([$field.'__contains' => $v]);
        }

        return $data;
    }

    private function explodeInOrLike($data)
    {
        $tmp_like = [];
        $tmp_in = [];

        if (is_array($data))
        {
            foreach ($data as $v) {
                $v = html_entity_decode($v);

                if (strpos($v, self::CONST_MANUAL_STRING) === 0) {
                    $tmp_like[] = substr($v, 3);
                }
                else {
                    $tmp_in[] = $v;
                }
            }
        }
        else {
            if (strpos($data, self::CONST_MANUAL_STRING) === 0) {
                $tmp_like[] = substr($data, 3);
            }
            else {
                $tmp_like[] = $data;
            }
        }

        return [$tmp_in, $tmp_like];
    }

    private function clearRecursive($data)
    {
        if (is_array($data) )
        {
            if (!empty($data))
            {
                $ta = [];
                foreach ($data as $k=>$v)
                {
                    $t = $this->clearRecursive($v);

                    if (!empty($t)) {
                        $ta[$k] = $t;
                    }
                }

                if (!empty($ta)) {
                    return $ta;
                }
            }
        }
        elseif (is_string($data)) {
            if (!empty($data)) {
                return str_replace(['\\n', '\\r'], ["\n", "\r"], $data);
            }
        }
        elseif (!empty($data)) {
            return $data;
        }

        return null;
    }

    public static function replaceNewLine($text)
    {
        return str_replace(["\n", "\r"], ['\\n', '\\r'], $text);
    }

    public static function getPhoneRegexp($phone)
    {
        $t = ['.*'];
        foreach (str_split($phone) as $char)
        {
            $t[] = $char;
            $t[] = '[^0-9]*';
        }
        $t[] = '.*';

        return implode('',$t);
    }
}