<?php
namespace Modules\Dashboard\Stores;

use DateTime;
use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\Q\QOr;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Modules\Product\Models\ProductQuestionModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\App\Store\BaseStore;
use Xcart\Connection;

class OrderSearchStore extends BaseStore
{
    const CONST_MANUAL_STRING      = '=> ';
    const CONST_MANUAL_VIEW_STTINR = '-> ';

    const CONST_CACHE_KEY_EVENT = 'order_search_store_events_count_';
    const CONST_CACHE_KEY_PRIORITY = 'order_search_store_priority_count_';


    private $form_data = [];
    private $where = [];
    /** @var QuerySet */
    private $qs;
    /** @var Pagination */
    private $pager;
    private $order;
    private $fid = null;

    public $defaultPagerPageSize = 25;

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
        return ProductQuestionModel::getFields()['status']['choices'];
    }

    public function __construct($data, $fid = null)
    {
        $this->form_data = $data;
        $this->fid = $fid;
        $this->populate($data);
    }

    public function __clone()
    {
        $clone = clone $this;
        $clone->qs = clone $this->qs;
        return $clone;
    }

    public function setOrder(array $order = [])
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function checkNot($type)
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
                $date = explode(' - ', $data['order']['date']);

                $tmp = [];
                if (isset($date[1])) {
                    $tmp['date__gte'] = strtotime($date[0]);
                    $tmp['date__lte'] = strtotime($date[1]) + 86400; //24 * 60 * 60;;
                }
                else {
                    $tmp['date__gte'] = strtotime($date[0]);
                }

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
                            $tmp['amazonorderid'] = ''; break;
                        }
                        case 'amazon_orders_only': {
                            $tmp[] = new QAndNot(['amazonorderid' => '']); break;
                        }
                        case 'amazon_orders_MFN' : {
                            $tmp['amazon_fulfillment_channel__in'][] = 'MFN'; break;
                        }
                        case 'amazon_orders_FBA' : {
                            $tmp['amazon_fulfillment_channel__in'][] = 'AFN'; break;
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
                $qs->join('inner join', 'xcart_orders_additional_tags', ['orderid' => 'tagl.orderid'], 'tagl');

                $val = ($data['order']['tag']) ? $data['order']['tag'] : [''];

                $this->getQ(['tagl.status_id__in' => $val], 'order.tag');
            }

            if (!empty($data['order']['distributor']) || $this->checkNot('order.distributor')) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');

                $val = ($data['order']['distributor']) ? $data['order']['distributor'] : [''];

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

            if (!empty($data['order']['all_dx'])) {
                $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
                $qs->join('left join', 'xcart_order_group_invoices', ['orderid' => 'invoice.orderid'], 'invoice');
                $qs->addSelect(['*', new Count('invoice.orderid', 'icount'), new Count('group.orderid', 'gcount') ]);

                if ($data['order']['all_dx'] == 'Y') { // Присутствует во всех группах
                    $qs->having(['gcount__gte' => 1 , new QAnd(new Expression('gcount = icount'))]);
                }
                elseif($data['order']['all_dx'] == 'AN') { // Отсутствует во всех группах
                    $qs->having(['icount' => 0, 'gcount__gt' => 0]);
                }
                elseif($data['order']['all_dx'] == 'NA') { // Отсутствует в одной или всех группах
                    $qs->having(['icount__gte' => 0 , new QAnd(new Expression('gcount > icount'))]);
                }
                else { // Присутствует в одной или всех группах
                    $qs->having(['icount__gte' => 1]);
                }
            }

            if (!empty($data['order']['has_memo'])) {
                $qs->join('left join', 'xcart_order_group_memos', ['orderid' => 'l_memo.orderid'], 'l_memo');
                $this->where['l_memo.orderid__isnull'] = ($data['order']['has_dx'] == 'N');
            }

            if (!empty($data['order']['has_icx'])) {
                $qs->join('left join', 'xcart_order_cx_invoices', ['orderid' => 'l_icx.orderid'], 'l_icx');
                $this->where['l_icx.orderid__isnull'] = ($data['order']['has_icx'] == 'N');
            }

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
                $tmp = ['details.productcode__contains' => $data['product']['sku']];

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

        $qs->filter($this->where)->addGroup(['orderid']);
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

    public static function explodeInOrLike($data, $clean = true)
    {
        $tmp_like = [];
        $tmp_in = [];
        $len_prefix = strlen(self::CONST_MANUAL_STRING);

        if (!empty($data))
        {
            if (is_array($data))
            {
                foreach ($data as $v) {
                    $v = html_entity_decode($v);

                    if (strpos($v, self::CONST_MANUAL_STRING) === 0) {
                        $tmp_like[] = $clean ? substr($v, $len_prefix) : $v;
                    }
                    else {
                        $tmp_in[] = $v;
                    }
                }
            }
            else {
                if (strpos($data, self::CONST_MANUAL_STRING) === 0) {
                    $tmp_like[] = $clean ? substr($data, $len_prefix) : $data;
                }
                else {
                    $tmp_like[] = $data;
                }
            }
        }

        return [$tmp_in, $tmp_like];
    }

    public static function getClearedData($data)
    {
        return self::clearRecursive($data);
    }

    private static function clearRecursive($data)
    {
        if (is_array($data) )
        {
            if (!empty($data))
            {
                $ta = [];
                foreach ($data as $k=>$v)
                {
                    $t = self::clearRecursive($v);

                    if (!is_null($t)) {
                        $ta[$k] = $t;
                    }
                }

                if ($ta) {
                    return $ta;
                }
            }
        }
        elseif (is_string($data)) {
            if ($data === '0' || !empty($data)) {
                return str_replace(['\\n', '\\r'], ["\n", "\r"], $data);
            }
        }
        elseif (is_numeric($data)) {
            return $data;
        }
        elseif ($data === 0 || !empty($data)) {
            return $data;
        }

        return null;
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = OrderModel::objects()->getQuerySet();
        }
        return $this->qs;
    }

    public function getQSWithSorting()
    {
        $qs = clone $this->qs;
        $joins = $qs->getQueryBuilder()->getJoins();
        $joins = array_keys($joins);

        if (!in_array('group', $joins)) {
            $qs->join('left join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
        }

        $qs->join('left join', 'xcart_shipping', ['shipping.shippingid' => 'group.shippingid'], 'shipping');

        $user = Xcart::app()->user;
        if ($user->show_events)
        {
            /** @var QuerySet $qs */
            $e_qs = OrderHelper::getEventCountQS($user->id, ($user->show_events_min_date) ? (new DateTime($user->show_events_min_date)) : null);
            $qs->join('left join', $e_qs->select(['order_id', 'count' => new Expression('count(*)')])->group(['order_id'])->allSql(), ['events.order_id' => 'orderid'], 'events');
            $qs->order(['-shipping.important', '-events.count','-date', '-orderid']);
        }
        else {
            $qs->order(['-shipping.important', '-date', '-orderid']);
        }

        if ($this->order) {
            $qs->order($this->order);
        }

        return $qs;
    }

    public function getPager()
    {
        if (!$this->pager) {
            $this->pager = new Pagination($this->getQSWithSorting(), ['pageSize' => $this->defaultPagerPageSize], new QuerySetDataSource());
        }

        return $this->pager;
    }

    public function getModels()
    {
        return $this->prepareModels($this->getPager()->paginate());
    }

    public function getPriorityShippingCount()
    {
        $qs = clone $this->qs;
        $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
        $qs->join('inner join', 'xcart_shipping', ['shipping.shippingid' => 'group.shippingid'], 'shipping');
        $qs->filter(['shipping.important' => 1, new QAndNot(['group.shippingid' => ''])]);
        $qs->addSelect(['shipping.important']);

        return Connection::getInstance()->fetchColumn("select COUNT(`order`.`important`) from ({$qs->allSql()}) as `order`");
    }

    public function getCachedPriorityShippingCount()
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

    public function getCount()
    {
        return $this->qs->count();
    }

    public function getCacheCountKey($prefix = 'order_search_store_count_', array $params = [])
    {
        if ($this->fid) {
            $id = $this->fid;
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

    public function clearCache()
    {
        Xcart::app()->cache->set($this->getCacheCountKey(), null);
        Xcart::app()->cache->set($this->getCacheCountKey(self::CONST_CACHE_KEY_PRIORITY), null);
        Xcart::app()->cache->set($this->getCacheCountKey(self::CONST_CACHE_KEY_EVENT, ['user_id' => Xcart::app()->user->login]), null);
    }

    public function getCashedCount()
    {
        $key = $this->getCacheCountKey();
        $count = Xcart::app()->cache->get($key);

        if (is_null($count))
        {
            $count = $this->getCount();
            Xcart::app()->cache->set($key, $count, $this->getCacheLifeTime());
        }

        return $count;
    }

    public function getCacheLifeTime($min = 40)
    {
        return $min + rand(1, $min * rand(1, round($min/2)));
    }

    public function getEventsCount(array $ids = [])
    {
        $user = Xcart::app()->user;

        if ($user->show_events)
        {
            $o_qs = clone $this->qs;

            /** @var QuerySet $qs */
            $qs = OrderHelper::getEventCountQS($user->id, ($user->show_events_min_date) ? (new DateTime($user->show_events_min_date)) : null);

            if ($ids) {
                $qs->filter(['order_id__in' => $ids]);
            }
            else {
                $qs->join('join', $o_qs->order([])->allSql(), ['orders.orderid' => 'order_id'], 'orders');
            }

            return $qs->count();
        }

        return null;
    }

    public function getCachedEventsCount()
    {
        $user = Xcart::app()->user;

        if (!$user->show_events) {
            return null;
        }

        $count = null;

        $key = $this->getCacheCountKey(self::CONST_CACHE_KEY_EVENT, ['user_id' => $user->login]);
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

        $lom_sql     = QueryBuilder::getInstance($connection)->from('xcart_order_logs')->order(['-date'])->where(['orderid__in' => $order_ids, 'type__in' => ['S']])->toSQL();
        $lo_messages = $connection->fetchAll($lom_sql);

        $loa_sql     = QueryBuilder::getInstance($connection)->select(['orderid', 'date' => new Expression('max(date)')])->from('xcart_order_logs')->group(['orderid'])->order(['-date'])->where(['orderid__in' => $order_ids])->toSQL();
        $lo_activity = $connection->fetchAll($loa_sql);

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