<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.2017
 * Time: 11:26
 */

namespace Modules\Dashboard\Stores;

use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\QueryBuilder;
use Xcart\App\Store\BaseStore;
use Xcart\Order;

class OrderSearchStore extends BaseStore
{
    public static function getFeatures()
    {
        return [
            'mobile_added' => 'Orders with products added via mobile-storefront',
            'gc_applied' => 'Entirely or partially paid by Gift Certificate',
            'discount_applied' => 'Global discount applied',
            'coupon_applied' => 'Discount coupon applied',
            'free_ship' => 'Free shipping',
            'free_tax' => 'Tax exempt',
            'gc_ordered' => 'Gift Certificates purchased',
            'notes' => 'Orders that have notes assigned',
        ];
    }

    public static function getSources()
    {
        return [
            'xcart_orders_only' => 'S3 Stores websites',
            'amazon_orders_only' => 'Amazon website',
            'amazon_orders_MFN' => 'Amazon - MFN',
            'amazon_orders_FBA' => 'Amazon - FBA',
        ];
    }

    public static function getQuestionStatuses()
    {
        return [
            "question_received_from_cust" => "Question received from customer",
            "question_sent_to_distr_brand" => "Question sent to distributor/brand",
            "call_distributor_brand" => "Call distributor/brand",
            "answer_sent_to_cust" => "Answer sent to customer",
            "order_pending" => "Order pending",
            "closed" => "Closed"
        ];
    }

    /**
     * @param array $data
     *
     * @return \Xcart\App\Orm\QuerySet
     */
    public function populate(array $data)
    {
//        $data1 = $this->clearRecursive($data);
//        func_dump($data1);

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
                $where['login_last_opened_or_saved__in'] = $data['order']['operator'];
            }
            if (!empty($data['order']['payment_method'])) {
                $where['paymentid__in'] = $data['order']['payment_method'];
            }
            if (!empty($data['order']['delivery_method'])) {
                $where['shippingid__in'] = $data['order']['delivery_method'];
            }
            if (!empty($data['order']['c2b_status'])) {
                $where['cb_status__in'] = $data['order']['c2b_status'];
            }
            if (!empty($data['order']['d2c_status'])) {
                $where['dc_status__in'] = $data['order']['d2c_status'];
            }
            if (!empty($data['order']['po_status'])) {
                $qs->join('inner join', 'xcart_order_groups', ['t.orderid' => 'group.orderid'], 'group');
                $where['group.po_status__in'] = $data['order']['po_status'];
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

//            # Search by product title
//            if (!empty($data["by_title"])) {
//                $search_in_products = true;
//                $condition[] = "$sql_tbl[products].product LIKE '%".$data["product_substring"]."%'";
//            }
//
//            # Search by product options
//            if (!empty($data["by_options"])) {
//                $search_in_order_details = true;
//                $condition[] = "$sql_tbl[order_details].product_options LIKE '%".$data["product_substring"]."%'";
//            }
        }
        $qs = $qs->limit(5)->filter($where)->exclude($exclude);

        func_dump($qs->getSql());

        return $qs;
    }

    private function clearRecursive($data)
    {
        if (is_array($data) )
        {
            if (!empty($data))
            {
                $data = array_filter($data);

                $ta = [];
                foreach ($data as $k=>$v)
                {
                    $t = $this->clearRecursive($v);

                    if (!empty($t)) {
                        $ta[$k] = $v;
                    }
                }

                if (!empty($ta)) {
                    return $ta;
                }
            }
        }
        elseif (is_string($data)) {
            $t = trim($data);
            if (!empty($t)) {
                return $data;
            }
        }
        elseif (!empty($data)) {
            return $data;
        }

        return null;
    }
}