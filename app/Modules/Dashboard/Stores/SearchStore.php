<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.2017
 * Time: 11:26
 */

namespace Modules\Dashboard\Stores;

use Mindy\QueryBuilder\QueryBuilder;
use Xcart\App\Store\BaseStore;

class SearchStore extends BaseStore
{
    /** @var  QueryBuilder */
    private $_qb;

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

    public function populate($data)
    {

    }


    public function findBy(array $get)
    {
        if (!empty($data["features"])) {
            # Search for orders that payed by Gift Certificates
            if (!empty($data["features"]["gc_applied"]))
                $search_condition .= " AND $sql_tbl[orders].giftcert_discount>0";

            # Search for orders with global discount applied
            if (!empty($data["features"]["discount_applied"]))
                $search_condition .= " AND $sql_tbl[orders].discount>0";

            # Sea4rch for orders with discount coupon applied
            if (!empty($data["features"]["coupon_applied"]))
                $search_condition .= " AND $sql_tbl[orders].coupon!=''";

            # Search for orders with free shipping (shipping cost = 0)
            if (!empty($data["features"]["free_ship"]))
                $search_condition .= " AND $sql_tbl[orders].shipping_cost=0";

            # Search for orders with free taxes
            if (!empty($data["features"]["free_tax"]))
                $search_condition .= " AND $sql_tbl[orders].tax=0 ";

            # Search for orders with notes assigned
            if (!empty($data["features"]["notes"]))
                $search_condition .= " AND $sql_tbl[orders].notes!=''";

            # Search for orders with Gift Certificates ordered
            if (!empty($data["features"]["gc_ordered"])) {
                $search_from[] = $sql_tbl["giftcerts"];
                $search_links[] = "$sql_tbl[orders].orderid=$sql_tbl[giftcerts].orderid";
            }



            # Search by product title
            if (!empty($data["by_title"])) {
                $search_in_products = true;
                $condition[] = "$sql_tbl[products].product LIKE '%".$data["product_substring"]."%'";
            }

            # Search by product options
            if (!empty($data["by_options"])) {
                $search_in_order_details = true;
                $condition[] = "$sql_tbl[order_details].product_options LIKE '%".$data["product_substring"]."%'";
            }
        }
    }
}