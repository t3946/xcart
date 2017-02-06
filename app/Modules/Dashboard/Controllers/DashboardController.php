<?php

namespace Modules\Dashboard\Controllers;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Dashboard\DashboardModule;
use Modules\Dashboard\Helpers\SearchAutoCompleteHelper;
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
        $session       = Xcart::app()->request->session;
        $form_collapse = false;

        if (!empty($_GET['search']['reset']) == 'reset') {
            $session->remove('search_order_form');
            $this->refresh();
        }

        if (!empty($_GET['search'])) {
            $session->add('search_order_form', OrderSearchStore::getClearedData($_GET['search']));
            $session->add('search_new_template', $_GET['search']['new_list']);
            $form_collapse = true;
        }

        $form_data = $session->get('search_order_form', [
            'new_list' => $session->get('search_new_template', 1),
            'order'    => [
                'date' => DashboardModule::getDefaultSearchDate(),
            ],
        ]);

        if (!is_array($form_data)) {
            $form_data = [];
        }

        $orderStore = new OrderSearchStore($form_data);
        $models     = $orderStore->getModels();
        $pager      = $orderStore->getPager();

        if (empty($models)) {
            $form_collapse = false;
        }

        $attention_tags   = Connection::getInstance()->fetchAll("SELECT * FROM xcart_attention_tags_values ORDER BY orderby ASC");
        $fraud_statuses   = Connection::getInstance()->fetchAll("SELECT * FROM xcart_order_fraud_statuses ORDER BY order_by ASC");
        $raw_statuses     = Connection::getInstance()->fetchAll("SELECT * FROM xcart_order_statuses ORDER BY type ASC, orderby ASC");
        $shipping_methods = Connection::getInstance()->fetchAll("SELECT * FROM xcart_shipping");
        $payment_methods  = Connection::getInstance()->fetchAll("SELECT * FROM xcart_payment_methods");

        $order_statuses = [];
        foreach ($raw_statuses as $status) {
            if (!isset($order_statuses[$status['type']])) {
                $order_statuses[$status['type']] = [];
            }

            $order_statuses[$status['type']][] = $status;
        }

        $content = $this->render('dashboard/search_form.tpl', [
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
            'pager'                => $pager,
            'form_data'            => SearchAutoCompleteHelper::prepareFormDataForTemplate($form_data),
            'form_collapse'        => $form_collapse,
            'models'               => $models,
            'new_template'         => $session->get('search_new_template', 1),
        ]);

        echo $this->renderSmarty("admin/home.tpl", [
            'single_mode' => true,
            'main'        => 'raw_html',
            'content'     => $content,
        ]);
    }

    public function search_ajax_suggestion()
    {
        $data = [];

        if (isset($_GET['from']) && !empty($_GET['q'])) {
            $data = SearchAutoCompleteHelper::getAjaxSuggestion($_GET['q'], $_GET['from']);
            $data = SearchAutoCompleteHelper::autoCompleteClearNewLines($data);
        }

        $this->jsonResponse($data);
    }
}