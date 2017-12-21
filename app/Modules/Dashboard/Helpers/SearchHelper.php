<?php
namespace Modules\Dashboard\Helpers;

use Modules\Brand\Models\BrandModel;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\DefaultConnection;
use Xcart\Connection;
use Xcart\POPipeline;

class SearchHelper
{

    public static function getFormAndListData()
    {
        $key = 'FormAndListData_properties';

        $properties = Xcart::app()->cache->get($key, null);

        if (!$properties)
        {
            $attention_tags   = Connection::getInstance()->fetchAll("SELECT * FROM xcart_attention_tags_values ORDER BY orderby ASC");
            $fraud_statuses   = Connection::getInstance()->fetchAll("SELECT * FROM xcart_order_fraud_statuses ORDER BY order_by ASC");
            $raw_statuses     = Connection::getInstance()->fetchAll("SELECT * FROM xcart_order_statuses ORDER BY type ASC, orderby ASC");
            $shipping_methods = Connection::getInstance()->fetchAll("SELECT * FROM xcart_shipping");
            $payment_methods  = Connection::getInstance()->fetchAll("SELECT * FROM xcart_payment_methods");
            $countries        = Connection::getInstance()->fetchAll(SearchSql::getAllCountryOrderSql());
            $domains          = SiteModel::objects()->all();

            $order_statuses = [];
            foreach ($raw_statuses as $status) {
                if (!isset($order_statuses[$status['type']])) {
                    $order_statuses[$status['type']] = [];
                }

                $order_statuses[$status['type']][] = $status;
            }

            /** @var SiteModel $domain */
            foreach ($domains as $domain) {
                $storefronts[$domain->storefrontid] = $domain->__toString();
            }

            $properties = [
                'storefronts'          => $storefronts,
                'countries'            => $countries,
                'fraud_statuses'       => $fraud_statuses,
                'order_statuses'       => $order_statuses,
                'attention_tags'       => $attention_tags,
                'shipping_methods'     => $shipping_methods,
                'payment_methods'      => $payment_methods,
                'po_statuses'          => POPipeline::getPOStatuses(),
                'features'             => OrderSearchStore::getFeatures(),
                'sources'              => OrderSearchStore::getSources(),
                'question_statuses'    => OrderSearchStore::getQuestionStatuses(),
                'manual_string'        => OrderSearchStore::CONST_MANUAL_STRING,
                'reconciliation_status'=> OrderSearchStore::getReconciliationStatuses(),
                'transaction_status'   => OrderTransactionModel::getFields()['transaction_status']['choices'],
            ];

            Xcart::app()->cache->set($key, $properties,120 + rand(0, 120));
        }

        return $properties;
    }

    public static function getNumberOnlyRegexp($numbers)
    {
        $t = ['.*'];
        foreach (str_split($numbers) as $char)
        {
            if (is_numeric($char)) {
                $t[] = $char;
                $t[] = '[^0-9]*';
            }
        }
        $t[] = '.*';

        return implode('',$t);
    }
    public static function getZipCodeRegex($code)
    {
        $t = ['.*'];
        foreach (str_split($code) as $char)
        {
            $t[] = $char;
            $t[] = '[^\W_]*';
        }
        $t[] = '.*';

        return implode('',$t);
    }

    public static function getDecoratedAutoCompleteData($data, $type)
    {
        if (empty($data)) {
            return [];
        }

        switch ($type) {
            case 'customer.country': {
                return Connection::getInstance()->fetchAll(SearchSql::getInCountryOrderSql($data));
            }
            case 'customer.state': {
                list($in, $like) = OrderSearchStore::explodeInOrLike($data, false);
                $data = $like;

                if (!empty($in)) {
                    $founded = Connection::getInstance()->fetchAll(SearchSql::getInStateOrderSql($in));
                    $not_founded = [];

                    $in_founded = array_map(function($el){ return $el['id'];}, $founded);
                    foreach ($in as $item) {
                        if (!in_array($item, $in_founded)) {
                            $tmp[] = $item;
                        }
                    }
                    $data = array_merge($data, $founded, $not_founded);
                }

                return $data;
            }

            case 'order.operator': {
                return Connection::getInstance()->fetchAll(SearchSql::getInOperatorSql($data));
            }
            case 'order.distributor': {
                return Connection::getInstance()->fetchAll(SearchSql::getInDistributorSql($data));
            }
        }

        return $data;
    }

    public static function prepareFormDataForTemplate($data)
    {
        return self::getDecoratedFormData($data);
    }

    public static function getDecoratedFormData($data)
    {
//        if (!empty($data['customer']['country'])) {
//            $data['customer']['country'] = self::getDecoratedAutoCompleteData($data['customer']['country'], 'customer.country');
//            $data['customer']['country'] = self::clearAutoCompleteData($data['customer']['country']);
//        }
        if (!empty($data['customer']['address'])) {
            $data['customer']['address'] = self::clearAutoCompleteData($data['customer']['address']);
        }
        if (!empty($data['customer']['city'])) {
            $data['customer']['city'] = self::clearAutoCompleteData($data['customer']['city']);
        }
        if (!empty($data['customer']['state'])) {
            $data['customer']['state'] = self::getDecoratedAutoCompleteData($data['customer']['state'], 'customer.state');
            $data['customer']['state'] = self::clearAutoCompleteData($data['customer']['state']);
        }
        if (!empty($data['order']['operator'])) {
            $data['order']['operator'] = self::getDecoratedAutoCompleteData($data['order']['operator'], 'order.operator');
            $data['order']['operator'] = self::clearAutoCompleteData($data['order']['operator']);
        }
        if (!empty($data['order']['distributor'])) {
            $data['order']['distributor'] = self::getDecoratedAutoCompleteData($data['order']['distributor'], 'order.distributor');
            $data['order']['distributor'] = self::clearAutoCompleteData($data['order']['distributor']);
        }

        return $data;
    }

    public static function clearAutoCompleteData($data)
    {
        if (empty($data) || !is_array($data)) {
            return $data;
        }

        return self::autoCompleteClearNewLines(array_map(function($v){
            return !is_array($v) ? ['id' => $v, 'text' => $v] : $v;
        }, $data));
    }

    public static function explodeStateCode($data)
    {
        if (is_array($data)) {
            return array_map(function($state){ return explode('__', $state)[0]; },$data);
        }
        return null;
    }


    public static function replaceNewLine($text)
    {
        return str_replace(["\n", "\r"], ['\\n', '\\r'], $text);
    }

    public static function autoCompleteClearNewLines($data)
    {
        foreach ($data as $k => $v)
        {
            $id = self::replaceNewLine($v['id']);
            $text = str_replace(["\n", "\r"], " ", $v['text']);

            $data[$k] = ['id' => $id, 'text' => $text];
        }

        return $data;
    }

    public static function getAjaxSuggestion($query, $from)
    {
        $stmt = null;
        $data = [];
        $like = "%{$query}%";
        $connection = Connection::getInstance();

        if ($connection instanceof DefaultConnection) {
            $connection = $connection->setIgnoreErrors(true);
        }

        switch ($from) {
            case 'distributor' :
                $stmt = $connection->executeQuery(SearchSql::getDistributorSql(), ['like' => $like]);
                break;
            case 'brand' :
                $data = BrandModel::objects()
                    ->select(['id' => 'brandid', 'text' => 'brand'])
                    ->filter(['brand__contains' => $query, 'parent_brand_id__isnull' => true])
                    ->asArray()
                    ->all();
                break;
            case 'operator' :
                $stmt = $connection->executeQuery(SearchSql::getOperatorSql(), ['like' => $like]);
                break;
            case 'company' :
                $stmt = $connection->executeQuery(SearchSql::getCompanySql(), ['like' => $like]);
                break;
            case 'search_city' :
                $stmt = $connection->executeQuery(SearchSql::getCitySql(), ['like' => $like]);
                break;
            case 'search_state' :
                $stmt = $connection->executeQuery(SearchSql::getStateOrderSql(), ['like' => $like]);
                break;
            case 'search_country' :
                $stmt = $connection->executeQuery(SearchSql::getCountryOrderSql(), ['like' => $like]);
                break;
            case 'search_street' :
                $stmt = $connection->executeQuery(SearchSql::getStreetSql(), ['like' => $like]);
                break;
            case 'search_phone' :
                $query = self::getNumberOnlyRegexp($query);
                $stmt = $connection->executeQuery(SearchSql::getPhoneFaxOrderSql(), ['like' => $query]);
                break;
            case 'search_email' :
                $stmt = $connection->executeQuery(SearchSql::getEmailOrderSql(), ['like' => $like]);
                break;
            case 'search_zip' :
                $query = self::getNumberOnlyRegexp($query);
                $stmt = $connection->executeQuery(SearchSql::getZipOrderSql(), ['like' => $query]);
                break;
            case 'search_customer_name' :
                $stmt = $connection->executeQuery(SearchSql::getCustomerNameSql(), ['like' => $like]);
                break;
        }

        if ($stmt) {
            $data = $stmt->fetchAll();
        }

        if ($connection instanceof DefaultConnection) {
            $connection->setIgnoreErrors(false);
        }

        return $data;
    }


    public static function getDefaultSearchDate()
    {
        $date = new \DateTime();
        $str_now = $date->format('m/d/Y');

        $date->setTimestamp(strtotime('-31 day'));
        $str_from = $date->format('m/d/Y');

        return "{$str_from} - {$str_now}";
    }
}