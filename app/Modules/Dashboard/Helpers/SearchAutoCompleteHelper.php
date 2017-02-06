<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.02.2017
 * Time: 18:25
 */

namespace Modules\Dashboard\Helpers;

use Modules\Dashboard\Sqls\SearchSql;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\Connection;

class SearchAutoCompleteHelper
{

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
        if (!empty($data['customer']['country'])) {
            $data['customer']['country'] = self::getDecoratedAutoCompleteData($data['customer']['country'], 'customer.country');
            $data['customer']['country'] = self::clearAutoCompleteData($data['customer']['country']);
        }
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
        if (!empty($data['customer']['country'])) {
            $data['customer']['country'] = self::clearAutoCompleteData($data['customer']['country']);
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
        return array_map(function($state){ return explode('__', $state)[0]; },$data);
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
        $data = [];
        $like = "%{$query}%";

        switch ($from) {
            case 'distributor' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getDistributorSql(), ['like' => $like]);
                break;
            case 'operator' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getOperatorSql(), ['like' => $like]);
                break;
            case 'company' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getCompanySql(), ['like' => $like]);
                break;
            case 'search_city' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getCitySql(), ['like' => $like]);
                break;
            case 'search_state' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getStateOrderSql(), ['like' => $like]);
                break;
            case 'search_country' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getCountryOrderSql(), ['like' => $like]);
                break;
            case 'search_street' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getStreetSql(), ['like' => $like]);
                break;
            case 'search_phone' :
                $query = self::getNumberOnlyRegexp($query);
                $data = Connection::getInstance()->fetchAll(SearchSql::getPhoneFaxOrderSql(), ['like' => $query]);
                break;
            case 'search_email' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getEmailOrderSql(), ['like' => $like]);
                break;
            case 'search_zip' :
                $query = self::getNumberOnlyRegexp($query);
                $data = Connection::getInstance()->fetchAll(SearchSql::getZipOrderSql(), ['like' => $query]);
                break;
            case 'search_customer_name' :
                $data = Connection::getInstance()->fetchAll(SearchSql::getCustomerNameSql(), ['like' => $like]);
                break;
        }

        return $data;
    }
}