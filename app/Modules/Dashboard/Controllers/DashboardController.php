<?php

namespace Modules\Dashboard\Controllers;

use Mindy\QueryBuilder\Q\QAndNot;
use Modules\Dashboard\DashboardModule;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Controller\AdminController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\Pagination;
use Xcart\Connection;
use Xcart\Order;
use Xcart\POPipeline;

class DashboardController extends AdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        func_dump($this->getRequest());
    }

    public function search()
    {
        if (!empty($_GET['search'])) {
            Xcart::app()->request->session->add('search_order_form', $_GET['search']);
        }

        $form_data = Xcart::app()->request->session->get('search_order_form', ['order'=> ['date' => DashboardModule::getDefaultSearchDate()]]);

        if (!is_array($form_data)) {
            $form_data = [];
        }

        $qs = (new OrderSearchStore())
            ->populate($form_data)
            ->order(['-t.orderid']);

        $pager = new Pagination($qs);


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

        $content = $this->render('dashboard/search_form.tpl', [
            'fraud_statuses' => $fraud_statuses,
            'order_statuses' => $order_statuses,
            'attention_tags' => $attention_tags,
            'shipping_methods' => $shipping_methods,
            'payment_methods' => $payment_methods,
            'po_statuses' => POPipeline::getPOStatuses(),
            'features' => OrderSearchStore::getFeatures(),
            'sources' => OrderSearchStore::getSources(),
            'question_statuses' => OrderSearchStore::getQuestionStatuses(),
            'manual_string' => OrderSearchStore::CONST_MANUAL_STRING,
            'pager' => $pager,
        ]);

        echo $this->renderSmarty("admin/home.tpl",[
            'single_mode' => true,
            'main' => 'raw_html',
            'content' => $content,
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
                    $query = OrderSearchStore::getPhoneRegexp($_GET['q']);
                    $data = Connection::getInstance()->fetchAll(SearchSql::getPhoneFaxOrderSql(), ['like' => $query]);
                    break;
                case 'search_email' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getEmailOrderSql(), ['like' => $query]);
                    break;
                case 'search_zip' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getZipOrderSql(), ['like' => $query]);
                    break;
                case 'search_customer_name' :
                    $data = Connection::getInstance()->fetchAll(SearchSql::getCustomerNameSql(), ['like' => $query]);
                    break;
            }

//            if (!empty($_GET['combobox'])) {
//                array_unshift($data, ['id' => OrderSearchStore::CONST_MANUAL_STRING . $_GET['q'], 'text' => '-> '.$_GET['q']]);
//            }
        }

        foreach ($data as $k => $v)
        {
            $id = OrderSearchStore::replaceNewLine($v['id']);
            $text = str_replace(["\n", "\r"], " ", $v['text']);

            $data[$k] = ['id' => $id, 'text' => $text];
        }

        $this->jsonResponse($data);
    }
}