<?php
namespace Modules\Dashboard\Stores;

use DateTime;
use Xcart\App\QueryBuilder\Aggregation\Count;
use Xcart\App\QueryBuilder\Aggregation\Max;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QAnd;
use Xcart\App\QueryBuilder\Q\QAndNot;
use Xcart\App\QueryBuilder\Q\QOr;
use Xcart\App\QueryBuilder\QueryBuilder;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Modules\Goods\Models\ProductQuestionModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\QuerySet;

use Xcart\App\Store\BaseStore;
use Xcart\Connection;

class OrderSearchStore extends BaseStore
{
    public const VIEW_TEMPLATE = 'dashboard/filter_view.tpl';
    public const CONST_CACHE_KEY_EVENT = 'order_search_store_events_count_';
    public const CONST_CACHE_KEY_PRIORITY = 'order_search_store_priority_count_';

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
            'amazon_orders_FB'  => 'Amazon - FB',
        ];
    }

    public static function getReconciliationStatuses()
    {
        return [
            'F' => 'Fully reconciled',
            'FP' => 'Fully or partially reconciled',
            'P' => 'Partially reconciled',
            'N' => 'Not reconciled',
        ];
    }

    public static function getQuestionStatuses()
    {
        return ProductQuestionModel::getFields()['status']['choices'];
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function checkNot($type): bool
    {
        $type = explode('.', $type);

        if (count($type) == 2) {
            $not = (!empty($this->form_data['not'][$type[0]][$type[1]]));
        }
        else {
            $not = (!empty($this->form_data['not'][$type[0]]));
        }

        return $not;
    }

    /**
     * @param array $where
     * @param string $type
     */
    private function getQ(array $where, $type)
    {
        if (!empty($where))
        {
            $not = $this->checkNot($type);

            $this->where[] = ($not) ? new QAndNot($where) : new QAnd($where);
        }
    }

    /**
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function populate(array $data)
    {
        $qs = $this->getQuerySet();

        if (!empty($data['order']) || $this->checkNot('order'))
        {
            if (!empty($data['order']['date'])) {
                $tmp = SearchHelper::getDateRange($data['order']['date'], 'date');

                $this->getQ($tmp, 'order.date');
            }


            if (!empty($data['order']['total'])) {
                $tmp = [];

                if (isset($data['order']['total']['from'])) {
                    $tmp['total__gte'] = $data['order']['total']['from'];
                }

                if (isset($data['order']['total']['to'])) {
                    $tmp['total__lte'] = $data['order']['total']['to'];
                }

                $this->getQ($tmp, 'order.total');
            }
            elseif ($this->checkNot('order.total')) {
                $this->getQ(['total' => 0], 'order.total');
            }

            if (!empty($data['order']['id'])) {
                $tmp = [];

                if (!empty($data['order']['id']['from']) && !empty($data['order']['id']['to'])) {
                    $tmp['orderid__gte'] = $data['order']['id']['from'];
                    $tmp['orderid__lte'] = $data['order']['id']['to'];

                }
                else {
                    $orderid = !empty($data['order']['id']['from']) ? $data['order']['id']['from'] : $data['order']['id']['to'];

                    if (!empty($orderid)) {
                        $tmp['orderid'] = $orderid;
                    }
                }

                $this->getQ($tmp, 'order.id');
            }

            if (!empty($data['order']['storefront'])) {
                $this->getQ(['storefrontid__in' => $data['order']['storefront']], 'order.storefront');
            }

            if (!empty($data['order']['source'])) {
                $tmp = [];

                foreach ($data['order']['source'] as $source)
                {
                    switch ($source)
                    {
                        case 'xcart_orders_only': {
                            $tmp['order_type'] = 'XCART'; break;
                        }
                        case 'amazon_orders_only': {
                            $tmp['order_type__in'] = ['MFN', 'FBA']; break;
                        }
                        case 'amazon_orders_MFN' : {
                            $tmp['order_type__in'][] = 'MFN'; break;
                        }
                        case 'amazon_orders_FBA' : {
                            $tmp['order_type__in'][] = 'FBA'; break;
                        }
                        case 'amazon_orders_FB' : {
                            $tmp['order_type__in'][] = 'FB'; break;
                        }
                    }
                }

                $this->getQ([new QOr($tmp)], 'order.source');
            }

            if (!empty($data['order']['operator']) || $this->checkNot('order.operator')) {
                $qs->join('inner join', 'xcart_order_logs', ['orderid' => 'logs.orderid'], 'logs');

                $val = ($data['order']['operator']) ? $data['order']['operator'] : [''];

                $tmp = [new QOr([
                                    'login_last_opened_or_saved__in' => $val,
                                    'logs.login__in'                 => $val,
                                ]),
                ];

                $this->getQ($tmp, 'order.operator');
            }

            if (!empty($data['order']['submit_operator']) || $this->checkNot('order.submit_operator')) {
                $qs->join('inner join', 'order_extra', ['orderid' => 'ext.order_id'], 'ext');

                $val = ($data['order']['submit_operator']) ? $data['order']['submit_operator'] : [''];

                $qs->join('inner join', 'xcart_customers', ['xcart_customers.id' => 'ext.submit_operator_id'], 'xcart_customers');

                $tmp = [
                    'xcart_customers.login__in' => $val,
                ];

                $this->getQ($tmp, 'order.submit_operator');
            }

            if (!empty($data['order']['payment_method']) || $this->checkNot('order.payment_method')) {
                $val = ($data['order']['payment_method']) ? $data['order']['payment_method'] : [''];
                $tmp = ['paymentid__in' => $val];
                $this->getQ($tmp, 'order.payment_method');
            }

            if (!empty($data['order']['has_payment_processor'])) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                if (($data['order']['has_payment_processor'] == 'N')) {
                    $this->where['group.acc_paymentid'] = '';
                }
                else {
                    $this->where[] = new QAndNot(['group.acc_paymentid' => '']);
                }
            }

            if (!empty($data['order']['payment_processor']) || $this->checkNot('order.payment_processor')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = ($data['order']['payment_processor']) ? $data['order']['payment_processor'] : [''];

                $this->getQ(['group.acc_paymentid__in' => $val], 'order.payment_processor');
            }

            if (!empty($data['order']['delivery_method']) || $this->checkNot('order.delivery_method')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = ($data['order']['delivery_method']) ? $data['order']['delivery_method'] : [''];

                $this->getQ(['group.shippingid__in' => $val], 'order.delivery_method');
            }

            if (!empty($data['order']['c2b_status']) || $this->checkNot('order.c2b_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = ($data['order']['c2b_status']) ? $data['order']['c2b_status'] : [''];

                $this->getQ(['group.cb_status__in' => $val], 'order.c2b_status');
            }

            if (!empty($data['order']['d2c_status']) || $this->checkNot('order.d2c_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = ($data['order']['d2c_status']) ? $data['order']['d2c_status'] : [''];

                $this->getQ(['group.dc_status__in' => $val], 'order.d2c_status');
            }

            if (!empty($data['order']['c2a_status']) || $this->checkNot('order.c2a_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = $data['order']['c2a_status'] ?: [''];

                $this->getQ(['group.c2a_status__in' => $val], 'order.c2a_status');
            }

            if (!empty($data['order']['a2c_status']) || $this->checkNot('order.a2c_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = $data['order']['a2c_status'] ?: [''];

                $this->getQ(['group.a2c_status__in' => $val], 'order.a2c_status');
            }

            if (!empty($data['order']['a2b_status']) || $this->checkNot('order.a2b_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = $data['order']['a2b_status'] ?: [''];

                $this->getQ(['group.a2b_status__in' => $val], 'order.a2b_status');
            }

            if (!empty($data['order']['d2a_status']) || $this->checkNot('order.d2a_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = $data['order']['d2a_status'] ?: [''];

                $this->getQ(['group.d2a_status__in' => $val], 'order.d2a_status');
            }

            if (!empty($data['order']['po_transit_status']) || $this->checkNot('order.po_transit_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = ($data['order']['po_transit_status']) ? $data['order']['po_transit_status'] : [''];

                $this->getQ(['group.po_status__in' => $val], 'order.po_transit_status');
            }

            if (!empty($data['order']['fraud_status']) || $this->checkNot('order.fraud_status')) {
                $val = ($data['order']['fraud_status']) ? $data['order']['fraud_status'] : [''];

                $this->getQ(['fraud_status__in' => $val], 'order.fraud_status');
            }

            if (!empty($data['order']['tag']) || $this->checkNot('order.tag')) {
                $qs->join('left join', 'xcart_orders_additional_tags', ['orderid' => 'tagl.orderid'], 'tagl');

                $val = ($data['order']['tag']) ?: [''];

                $this->where[] = $this->checkNot('order.tag')
                    ? new QOr([new QAndNot(['tagl.status_id__in' => $val]), ['tagl.status_id__isnull' => true]])
                    : new QAnd(['tagl.status_id__in' => $val]);
            }

            if (!empty($data['order']['distributor']) || $this->checkNot('order.distributor')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = $data['order']['distributor'] ?: [''];

                $this->getQ(['group.manufacturerid__in' => $val], 'order.distributor');
            }

            if (!empty($data['order']['vn_status']) || $this->checkNot('order.vn_status')) {
                $val = ($data['order']['vn_status']) ? $data['order']['vn_status'] : [''];

                $this->getQ(['vn_status__in' => $val], 'order.vn_status');
            }

            if (!empty($data['order']['po_status']) || $this->checkNot('order.po_status')) {
                $qs->join('right outer join', 'xcart_po_pipeline', ['orderid' => 'po.order_id'], 'po');
                $qs->addGroup(['orderid', 'po.po_id']);

                $val = ($data['order']['po_status']) ? $data['order']['po_status'] : [''];

                $this->getQ(['po.status__in' => $val], 'order.po_status');
            }

            if (!empty($data['order']['transaction_status']) || $this->checkNot('order.transaction_status')) {
                $qs->join('left join', 'xcart_order_transactions', ['orderid' => 'ot.orderid'], 'ot');

                $val = ($data['order']['transaction_status']) ? $data['order']['transaction_status'] : [''];

                $this->getQ(['ot.transaction_status__in' => $val], 'order.transaction_status');
            }

            if (!empty($data['order']['transaction_payment_method']) || $this->checkNot('order.transaction_status')) {
                $qs->join('left join', 'xcart_order_transactions', ['orderid' => 'ot.orderid'], 'ot');

                $val = ($data['order']['transaction_payment_method']) ? $data['order']['transaction_payment_method'] : [''];

                $this->getQ(['ot.paymentid__in' => $val], 'order.transaction_payment_method');
            }

            if (!empty($data['order']['reconciliation_status']) || $this->checkNot('order.reconciliation_status')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
                $qs->join('left join', 'xcart_order_group_invoices', ['orderid' => 'invoice.orderid', 'group.manufacturerid' => 'invoice.manufacturerid'], 'invoice');
                $qs->join('left join', 'xcart_reconciliations', ['invoice.reconciliation_id' => 'reconciliations.id'], 'reconciliations');
                $qs->addSelect(['*', new Count('invoice.orderid', 'icount'), new Count('group.orderid', 'gcount'), new Count('reconciliations.id', 'rcount') ]);

                $this->where[] = new QOr(['reconciliations.action' => 'R', 'reconciliations.action__isnull' => true]);

                if ( $data['order']['reconciliation_status'] === 'F') {
                    $this->having[] = new QAnd(new Expression('rcount = icount'));
                    $this->having[] = new QAnd(new Expression('rcount > 0'));
                }
                elseif ($data['order']['reconciliation_status'] === 'FP') {
                    $this->having[] = new QAnd(new Expression('rcount <= icount'));
                    $this->having[] = new QAnd(new Expression('rcount > 0'));
                }
                elseif ($data['order']['reconciliation_status'] === 'P') {
                    $this->having[] = new QAnd(new Expression('rcount < icount'));
                    $this->having[] = new QAnd(new Expression('rcount > 0'));
                }
                elseif ($data['order']['reconciliation_status'] === 'N') {
                    $this->having[] = new QAnd(new Expression('rcount = 0'));
                    $this->having[] = new QAnd(new Expression('icount > 0'));
                }
            }

            if (!empty($data['order']['all_dx'])) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
                $qs->join('left join', 'xcart_order_group_invoices', ['orderid' => 'invoice.orderid', 'group.manufacturerid' => 'invoice.manufacturerid'], 'invoice');
                $qs->addSelect(['*', new Count('invoice.orderid', 'icount'), new Count('group.orderid', 'gcount') ]);

                if ($data['order']['all_dx'] === 'Y') { // Присутствует во всех группах
                    $this->having['gcount__gte'] = 1;
                    $this->having[] = new QAnd(new Expression('gcount <= icount'));
                }
                elseif($data['order']['all_dx'] === 'AN') { // Отсутствует во всех группах
                    $this->having['icount'] = 0;
                    $this->having['gcount__gt'] = 0;
                }
                elseif($data['order']['all_dx'] === 'NA') { // Отсутствует в одной или всех группах
                    $this->having['icount__gte'] = 0;
                    $this->having[] = new QAnd(new Expression('gcount > icount'));
                }
                else { // Присутствует в одной или всех группах
                    $this->having['icount__gte'] = 1;
                }
            }

            if (!empty($data['order']['has_memo'])) {
                $qs->join('left join', 'xcart_order_group_memos', ['orderid' => 'l_memo.orderid'], 'l_memo');
                $this->where['l_memo.orderid__isnull'] = ($data['order']['has_dx'] === 'N');
            }

            if (!empty($data['order']['has_icx'])) {
                $qs->join('left join', 'xcart_order_cx_invoices', ['orderid' => 'l_icx.orderid'], 'l_icx');
                $this->where['l_icx.orderid__isnull'] = ($data['order']['has_icx'] === 'N');
            }

            if (!empty($data['order']['po'])) {
                $this->getQ(['po_number' => $data['order']['po']], 'order.po');
            }
            if (!empty($data['order']['amazon_order'])) {
                $this->getQ(['amazonorderid' => $data['order']['amazon_order']], 'order.amazon_order');
            }

        }

        if (!empty($data["voided_reasons"])) {
            $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
            $this->getQ(['group.voided_reason_id__in' => $data["voided_reasons"]], 'group.voided_reasons');
        }

        if (!empty($data["features"])) {
            $tmp = [];
            foreach ($data["features"] as $feature)
            {
                switch ($feature) {
                    case 'gc_applied': {
                        $tmp['giftcert_discount__gt'] = 0; break;
                    }
                    case 'discount_applied': {
                        $tmp['discount__gt'] = 0; break;
                    }
                    case 'coupon_applied' : {
                        $tmp['coupon'] = ''; break;
                    }
                    case 'free_ship' : {
                        $tmp['shipping_cost'] = 0; break;
                    }
                    case 'free_tax' : {
                        $tmp['tax'] = 0; break;
                    }
                    case 'notes' : {
                        $tmp['notes'] = ''; break;
                    }
                    case 'gc_ordered' : {
                        if (empty($this->form_data['not']['features'])) {
                            $qs->join('inner join', 'xcart_giftcerts', ['orderid' => 'sert.orderid'], 'sert');
                        }
                        else {
                            $qs->join('left join', 'xcart_giftcerts', ['orderid' => 'l_sert.orderid'], 'l_sert');
                            $tmp['l_sert.orderid__isnull'] = true;
                        }
                        break;
                    }
                }
            }
            $this->getQ($tmp, 'features');
        }

        if (!empty($data['product']) || $this->checkNot('product'))
        {
            if (!empty($data['product']['name'])) {
                $qs->join('inner join', 'xcart_order_details', ['orderid' => 'details.orderid'], 'details');

                $tmp = [new QAnd(array_map(function ($word) {
                    return new QOr([
                                       'details.product__contains'         => $word,
                                       'details.product_options__contains' => $word,
                                   ]);
                }, explode(' ', $data['product']['name'])))];

                $this->getQ($tmp, 'product.name');
            }

            if (!empty($data['product']['sku'])) {
                $qs->join('inner join', 'xcart_order_details', ['orderid' => 'details.orderid'], 'details');
                $tmp = ['details.productcode__startswith' => $data['product']['sku']];

                $this->getQ($tmp, 'product.sku');
            }

            if (!empty($data['product']['id'])) {
                $qs->join('inner join', 'xcart_order_details', ['orderid' => 'details.orderid'], 'details');
                $tmp = ['details.productid' => $data['product']['id']];

                $this->getQ($tmp, 'product.id');
            }

            if (!empty($data['product']['question_status']) || $this->checkNot('product.question_status')) {
                $qs->join('inner join', 'xcart_order_details', ['orderid' => 'details.orderid'], 'details');
                $qs->join('inner join', 'xcart_product_question', ['details.productid' => 'question.productid'], 'question');

                $val = ($data['product']['question_status']) ? $data['product']['question_status'] : [''];

                $this->getQ(['question.status__in' => $val], 'product.question_status');
            }
        }

        if (!empty($data['customer']) || $this->checkNot('customer'))
        {
            if (!empty($data['customer']['company']) || $this->checkNot('customer.company'))
            {
                $tmp = [];
                $val = ($data['customer']['company']) ? $data['customer']['company'] : [''];

                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_company__in'] = $val;
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_company__in'] = $val;
                }
                $tmp['company__in'] = $val;

                $this->getQ([new QOr($tmp)], 'customer.company');
            }

            if (!empty($data['customer']['city']) || $this->checkNot('customer.city'))
            {
                $tmp = [];
                $val = ($data['customer']['city']) ? $data['customer']['city'] : [''];

                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_city__in'] = $val;
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_city__in'] = $val;
                }

                $this->getQ([new QOr($tmp)], 'customer.city');
            }

            if (!empty($data['customer']['state']) || $this->checkNot('customer.state'))
            {
                $tmp = [];
                $val = SearchHelper::explodeStateCode($data['customer']['state']);
                $val = ($val) ? $val : [''];

                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_state__in'] = $val;
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_state__in'] = $val;
                }

                $this->getQ([new QOr($tmp)], 'customer.state');
            }

            if (!empty($data['customer']['country']) || $this->checkNot('customer.country'))
            {
                $tmp = [];
                $val = ($data['customer']['country']) ? $data['customer']['country'] : [''];

                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                    $tmp['b_country__in'] = $val;
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    $tmp['s_country__in'] = $val;
                }

                $this->getQ([new QOr($tmp)], 'customer.country');
            }

            if (!empty($data['customer']['address']) || $this->checkNot('customer.address'))
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
                    if (empty($in) && empty($like)) {
                        $tmp['b_address'] = '';
                    }
                }
                if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                    if (!empty($in)) {
                        $tmp['s_address__in'] = $in;
                    }
                    if (!empty($like)) {
                        $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 's_address'));
                    }
                    if (empty($in) && empty($like)) {
                        $tmp['s_address'] = '';
                    }
                }

                $this->getQ([new QOr($tmp)], 'customer.address');
            }

            if (!empty($data['customer']['zip_code']) || $this->checkNot('customer.zip_code'))
            {
                $tmp = [];

                if (is_array($data['customer']['zip_code']))
                {
                    list($in, $like) = $this->explodeInOrLike($data['customer']['zip_code']);

                    if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                        if (!empty($in)) {
                            $tmp['b_zipcode__in'] = $in;
                        }
                        if (!empty($like)) {
                            $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 'b_zipcode'));
                        }
                        if (empty($in) && empty($like)) {
                            $tmp['b_zipcode'] = '';
                        }
                    }
                    if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                        if (!empty($in)) {
                            $tmp['s_zipcode__in'] = $in;
                        }
                        if (!empty($like)) {
                            $tmp = array_merge($tmp, $this->arrLikeToLookup($like, 's_zipcode'));
                        }
                        if (empty($in) && empty($like)) {
                            $tmp['s_zipcode'] = '';
                        }
                    }

                    $this->getQ([new QOr($tmp)], 'customer.zip_code');
                }
                else {
                    if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'billing'])) {
                        $tmp['b_zipcode__raw'] = "RLIKE '" . SearchHelper::getZipCodeRegex($data['customer']['zip_code']). "'";
                    }
                    if (empty($data['customer']['in_address']) || in_array($data['customer']['in_address'], ['both', 'shipping'])) {
                        $tmp['s_zipcode__raw'] = "RLIKE '" . SearchHelper::getZipCodeRegex($data['customer']['zip_code']). "'";
                    }

                    $this->getQ([new QOr($tmp)], 'customer.zip_code');
                }
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

                $this->getQ([new QOr($tmp)], 'customer.email');
            }
            elseif($this->checkNot('customer.email')) {
                $this->getQ(['email' => ''], 'customer.email');
            }

            if (!empty($data['customer']['phone']))
            {
                $tmp = [];
                list($in, $like) = $this->explodeInOrLike($data['customer']['phone']);

                if (!empty($in)) {
                    $tmp[] = new QOr(['phone__in'=>$in, 'fax__in'=>$in]);
                }
                if (!empty($like)) {
                    foreach ($like as $t)
                    {
                        $t = SearchHelper::getNumberOnlyRegexp($t);
                        $tmp[] = new Qor(['phone__raw' => "RLIKE '" . $t ."'", 'fax__raw' => "RLIKE '" . $t ."'"]);
                    }
                }

                $this->getQ([new QOr($tmp)], 'customer.phone');
            }
            elseif($this->checkNot('customer.phone')) {
                $this->getQ([new QOr(['phone' => '', 'fax' => ''])], 'customer.phone');
            }

            if (!empty($data['customer']['name'])) {
                list($in, $like) = $this->explodeInOrLike($data['customer']['name']);
                $tmp = [];

                if (!empty($in)) {
                    $tmp = [new QOr(['firstname__in'   => $in,
                                     'b_firstname__in' => $in,
                                     's_firstname__in' => $in,
                                    ]),
                    ];
                }
                if (!empty($like)) {
                    $tmp = array_merge($tmp, ($this->arrLikeToLookup($like, ['firstname', 'b_firstname', 's_firstname'])));
                }

                $this->getQ($tmp, 'customer.name');
            }
            elseif ($this->checkNot('customer.name')) {
                $this->getQ([new QOr(['firstname'   => '', 'b_firstname' => '', 's_firstname' => '', ]), ], 'customer.name');
            }
        }

        $qs->filter($this->where)->addGroup(['orderid'])->having($this->having);
        $this->qs = $qs;
    }

    private function arrLikeToLookup($data, $fields)
    {
        foreach ($data as $k => $v)
        {
            $t = [];

            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $t[$field . '__contains'] = $v;
                }
            }
            else {
                $t[$fields . '__contains'] = $v;
            }

            $data[$k] = new QOr($t);
        }

        return $data;
    }

    public static function getManager(): Manager
    {
        return OrderModel::objects();
    }

    public function setSorting($sorting, $qs)
    {
        if ($sorting) {

            switch ($sorting) {
                case 10:
                    {
                        $qs->order(['date']);
                        break;
                    }
                case 11:
                    {
                        $qs->order(['-date']);
                        break;
                    }
                case 1:
                default:
                    {
                        $user = Xcart::app()->user;
                        $joins = $qs->getQueryBuilder()->getJoins();
                        $joins = array_keys($joins);
                        if (!in_array('group', $joins, true)) {
                            $qs->join('left join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
                        }
                        $qs->join('left join', 'xcart_shipping', ['shipping.shippingid' => 'group.shippingid'], 'shipping');
                        if ($user->show_events) {
                            /** @var QuerySet $qs */
                            $e_qs = OrderHelper::getCountEventsQS($user->id, ($user->show_events_min_date) ? (new DateTime($user->show_events_min_date)) : null);
                            $qs->join('left join', $e_qs->select(['order_id', 'count' => new Expression('count(*)')])->group(['order_id'])->allSql(), ['events.order_id' => 'orderid'], 'events');
                            $qs->order(['-shipping.important', '-events.count', '-date', '-orderid']);
                        } else {
                            $qs->order(['-shipping.important', '-date', '-orderid']);
                        }
                    }
            }
        }
        return $qs;
    }

    public function getQSWithSorting()
    {
        $qs = clone $this->qs;

        if (!$this->sort && $this->model instanceof DashboardFilter) {
            $this->sort = $this->model->sorting;
        }

        $qs = $this->sort ? $this->setSorting($this->sort, $qs) : $this->setSorting(11, $qs);

        if ($this->order) {
            $qs->order($this->order);
        }

        return $qs;
    }

    public function getPriorityShippingCount()
    {
        $qs = clone $this->qs;
        $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
        $qs->join('inner join', 'xcart_shipping', ['shipping.shippingid' => 'group.shippingid'], 'shipping');
        $qs->filter(['shipping.important' => 1, new QAndNot(['group.shippingid' => ''])]);
        $qs->addSelect(['shipping.important']);

        return (int)Connection::getInstance()->fetchAssociative("select COUNT(`order`.`important`) from ({$qs->allSql()}) as `order`")['order'];
    }

    public function getCachedPriorityShippingCount():? int
    {
        $count = null;

        $key = $this->getCacheCountKey(self::CONST_CACHE_KEY_PRIORITY);
        $count = Xcart::app()->cache->get($key);

        if (is_null($count))
        {
            $count = $this->getPriorityShippingCount();

            Xcart::app()->cache->set($key, $count, $this->getCacheLifeTime());
        }

        return $count;
    }

    protected function getCacheCountKey($prefix = 'order_search_store_count_', array $params = [])
    {
        if ($this->model) {
            $id = $this->model::classNameShort() . $this->model->pk;
        }
        else {
            $md5 = json_encode($this->where);
            $id = md5($md5);
        }

        if ($params) {
            $id.= '_';
            $id.= md5(serialize($params));
        }

        return $prefix.$id;
    }

    public function getCacheKeyPriority()
    {
        return $this->getCacheCountKey(self::CONST_CACHE_KEY_PRIORITY);
    }

    public function getCacheKeyEvent()
    {
        return $this->getCacheCountKey(self::CONST_CACHE_KEY_EVENT, ['user_id' => Xcart::app()->user->login]);
    }

    public function clearCache()
    {
        Xcart::app()->cache->set($this->getCacheKeyCount(), null);
        Xcart::app()->cache->set($this->getCacheKeyPriority(), null);
        Xcart::app()->cache->set($this->getCacheKeyEvent(), null);
    }

    public function getEventsCount(array $ids = [])
    {
        $user = Xcart::app()->user;

        if ($user->show_events)
        {
            $o_qs = clone $this->qs;

            /** @var QuerySet $qs */
            $qs = OrderHelper::getCountEventsQS($user->id, ($user->show_events_min_date) ? (new DateTime($user->show_events_min_date)) : null);

            if ($ids) {
                $qs->filter(['order_id__in' => $ids]);
            }
            else {
                $qs->join('join', $o_qs->order([])->allSql(), ['orders.orderid' => 'order_id'], 'orders');
            }

            return $qs->count();
        }

        return 0;
    }

    public function getCachedEventsCount():? int
    {
        $user = Xcart::app()->user;

        if (!$user->show_events) {
            return null;
        }

        $count = null;

        $key = $this->getCacheKeyEvent();
        $count = Xcart::app()->cache->get($key);

        if (is_null($count))
        {
            $count = $this->getEventsCount();

            Xcart::app()->cache->set($key, $count, $this->getCacheLifeTime());
        }

        return $count;
    }

    public function prepareModels($models)
    {
        if (!$models) {
            return [];
        }

        $connection = Connection::getInstance();

        $order_ids = array_map(function ($model) { return $model->orderid; }, $models);
        $order_ids = array_filter($order_ids);

        if (empty($order_ids)) {
            return [];
        }

        $lom_sql     = QueryBuilder::getInstance($connection)->from('xcart_order_logs')->order(['-date'])->where(['orderid__in' => $order_ids, 'type__in' => ['S', 'EL']])->toSQL();
        $lo_messages = $connection->fetchAllAssociative($lom_sql);

        $loa_sql     = QueryBuilder::getInstance($connection)->select(['orderid', 'date' => new Max('date')])->from('xcart_order_logs')->group(['orderid'])->order(['-date'])->where(['orderid__in' => $order_ids])->toSQL();
        $lo_activity = $connection->fetchAllAssociative($loa_sql);

        OrderHelper::getMaxEtaTimeByOrder($order_ids);
        OrderHelper::getCountEvents($order_ids);

        foreach ($models as $model) {

            foreach ($lo_activity as $activity) {
                if ($activity['orderid'] == $model->orderid) {
                    $model->last_activity = $activity['date'];
                    break;
                }
            }

            foreach ($lo_messages as $message) {
                if ($model->orderid == $message['orderid']) {
                    $model->last_message = $message;
                    break;
                }
            }
        }

        return $models;
    }
}