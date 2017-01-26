<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Sqls\SearchSql;
use Modules\Dashboard\Stores\SearchStore;
use Xcart\App\Controller\AdminController;
use Xcart\Connection;

class DashboardController extends AdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        func_dump($this->getRequest());
    }

    public function search()
    {
        $render = '';

        if (isset($_GET['search']))
        {
            func_dump($_GET['search']);
            $render = $this->render('dashboard/search_form.tpl');
        }
        else {
            $attention_tags = Connection::getInstance()->fetchAll("select * from xcart_attention_tags_values ORDER BY orderby ASC");
            $fraud_statuses = Connection::getInstance()->fetchAll("select * from xcart_order_fraud_statuses ORDER BY order_by ASC");
            $raw_statuses = Connection::getInstance()->fetchAll("select * from xcart_order_statuses ORDER BY type ASC, orderby ASC");
            $shipping_methods = Connection::getInstance()->fetchAll("select * from xcart_shipping");
            $payment_methods  = Connection::getInstance()->fetchAll("select * from xcart_payment_methods");

            $order_statuses = [];
            foreach ($raw_statuses as $status) {
                if (!isset($order_statuses[$status['type']])) {
                    $order_statuses[$status['type']] = [];
                }

                $order_statuses[$status['type']][] = $status;
            }

            $render = $this->render('dashboard/search_form.tpl', [
                'fraud_statuses' => $fraud_statuses,
                'order_statuses' => $order_statuses,
                'attention_tags' => $attention_tags,
                'shipping_methods' => $shipping_methods,
                'payment_methods' => $payment_methods,
                'features' => SearchStore::getFeatures(),
                'sources' => SearchStore::getSources(),
                'question_statuses' => SearchStore::getQuestionStatuses(),
            ]);
        }

        echo $this->renderSmarty("admin/home.tpl",[
            'single_mode' => true,
            'main' => 'raw_html',
            'content' => $render,
        ]);
    }

    public function search_ajax_suggestion()
    {
        $data = [];

        if (isset($_GET['from']) && !empty($_GET['q']))
        {
            $query = "%{$_GET['q']}%";

            switch ($_GET['from']) {
                case 'distributor' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getDistributorSql(), ['like' => $query]);
                    break;
                case 'operator' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getCustomerSql(), ['like' => $query]);
                    break;
                case 'company' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getCompanySql(), ['like' => $query]);
                    break;
                case 'search_city' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getCitySql(), ['like' => $query]);
                    break;
                case 'search_state' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getStateOrderSql(), ['like' => $query]);
                    break;
                case 'search_country' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getCountryOrderSql(), ['like' => $query]);
                    break;
                case 'search_street' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getStreetSql(), ['like' => $query]);
                    break;
                case 'search_phone' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getPhoneFaxOrderSql(), ['like' => $query]);
                    break;
                case 'search_email' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getEmailOrderSql(), ['like' => $query]);
                    break;
                case 'search_zip' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getZipOrderSql(), ['like' => $query]);
                    break;
            }
        }

        $this->jsonResponse($data);
    }
}