<?php

namespace Modules\Dashboard\Controllers;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Dashboard\DashboardModule;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Controller\AdminController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\Pagination;
use Xcart\Connection;
use Xcart\Manufacturer;
use Xcart\Order;
use Xcart\OrderGroups;
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
        $connection = Connection::getInstance();
        $session = Xcart::app()->request->session;
        $form_collapse = false;

        if (!empty($_GET['search']['reset']) == 'reset') {
            $session->remove('search_order_form');
            $this->refresh();
        }

        if (!empty($_GET['search'])) {
            $session->add('search_order_form', OrderSearchStore::getClearedData($_GET['search']));
            $form_collapse = true;
        }

        $form_data = $session->get('search_order_form', ['order'=> ['date' => DashboardModule::getDefaultSearchDate()]]);

        if (!is_array($form_data)) {
            $form_data = [];
        }

        $qs = (new OrderSearchStore())
            ->populate($form_data)
            ->order(['-t.orderid']);

        $pager = new Pagination($qs, ['pageSize' => 20]);
        $models = $pager->paginate();

        if (!empty($models)) {
            $order_ids = array_map(function($model){return $model->orderid;}, $models);
            $groups = OrderGroups::objects()->filter(['orderid__in' => $order_ids])->all();
            $group_ids = array_map(function($model){return $model->manufacturerid;}, $groups);

            $manufacturers = Manufacturer::objects()->filter(['manufacturerid__in' => $group_ids])->all();
            foreach ($groups as $group) {
                foreach ($manufacturers as $manufacturer) {
                    if ($group->manufacturerid == $manufacturer->manufacturerid) {
                        $group->manufacturer = $manufacturer;
                    }
                }
            }


            $lom_sql = QueryBuilder::getInstance($connection)->from('xcart_order_logs')->group(['orderid'])->order(['-date'])->where(['orderid__in'=>$order_ids, 'type__in'=>['S']])->toSQL();
            $last_order_messages = $connection->fetchAll($lom_sql);

            $loa_sql = QueryBuilder::getInstance($connection)->from('xcart_order_logs')->group(['orderid'])->order(['-date'])->where(['orderid__in'=>$order_ids])->toSQL();
            $last_order_activity = $connection->fetchAll($lom_sql);

            $tag_sql = QueryBuilder::getInstance($connection)->from('xcart_orders_additional_tags')
                ->select(['t.orderid','t.status_id', 'tval.description', 'tval.status'])
                ->setAlias('t')
                ->join('inner join', 'xcart_attention_tags_values', ['t.status_id' => 'tval.status_id'], 'tval')
                ->where(['orderid__in'=>$order_ids])->toSQL();
            $orders_tags = $connection->fetchAll($tag_sql);

            $max_eta_sql = QueryBuilder::getInstance($connection)->from('xcart_products')
                ->select(['max_eta' => new Expression('MAX(t.eta_date_mm_dd_yyyy)'),'details.orderid'])
                ->setAlias('t')
                ->join('inner join', 'xcart_order_details', ['t.productid' => 'details.productid'], 'details')
                ->where(['details.orderid__in'=>$order_ids, 'eta_date_mm_dd_yyyy__gt' => 0])
                ->group(['details.orderid'])->toSQL();
            $orders_max_eta = $connection->fetchAll($max_eta_sql);

            foreach ($models as $model) {
                foreach ($groups as $group) {
                    if ($group->orderid == $model->orderid)
                        $model->orderGroup = $group;
                }

                foreach ($orders_max_eta as $item) {
                    if ($item['orderid'] == $model->orderid) {
                        $model->max_eta = $item['max_eta'];
                    }
                }
                foreach ($last_order_activity as $item) {
                    if ($item['orderid'] == $model->orderid) {
                        $model->last_activity = $item['date'];
                    }
                }
            }

        }
        else {
            $form_collapse = false;
        }


        $attention_tags = Connection::getInstance()->fetchAll("select * from xcart_attention_tags_values ORDER BY orderby ASC");
        $fraud_statuses = Connection::getInstance()->fetchAll("select * from xcart_order_fraud_statuses ORDER BY order_by ASC");
        $raw_statuses = Connection::getInstance()->fetchAll("select * from xcart_order_statuses ORDER BY type ASC, orderby ASC");
        $shipping_methods = Connection::getInstance()->fetchAll("select * from xcart_shipping");
        $payment_methods  = Connection::getInstance()->fetchAll("select * from xcart_payment_methods");
//        $payment_methods  = Connection::getInstance()->fetchAll("SELECT paymentid, payment_method, acc_per_trans, acc_percent FROM xcart_payment_methods WHERE acc_proc='Y' ORDER BY paymentid ASC");

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
            'form_data' => $form_data,
            'form_collapse' => $form_collapse,
            'models' => $models,
            'orders_last_messages' => isset($last_order_messages) ? $last_order_messages : [],
            'last_order_activity' => isset($last_order_activity) ? $last_order_activity : [],
            'orders_tags' => isset($orders_tags) ? $orders_tags : [],
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
            $data = OrderSearchStore::getAjaxSuggestion($_GET['q'], $_GET['from']);
            $data = OrderSearchStore::autoCompleteClearNewLines($data);
        }

        $this->jsonResponse($data);
    }
}