<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Q\QOr;

class SearchController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    //набор шаблонов для функции быстрого поиска
    private const PATTERNS = [
        "sku" => "/^[a-zA-Z]{3,}-[\.\w\d_-]+$/i",
        "order_id_with_prefix" => "/^([a-z]{2}-)?(\d{6})$/i",
        "order_amazon_id" => "/\d+-\d+-\d+/",
    ];

    public function index(): void
    {
        $form_data = [];
        $form_collapse = false;

        $request = Xcart::app()->request->request->all();

        if (!empty($request['search'])) {
            $form_collapse = true;
            $form_data = OrderSearchStore::getClearedData($request['search']);
        }

        if (!is_array($form_data)) {
            $form_data = [
                'order' => [
                    'date' => SearchHelper::getDefaultSearchDate(),
                ],
            ];
        }


        if (!empty($form_data['order']['id']['from'])) {

            $oid = $form_data['order']['id']['from'];

            if (strpos($oid, '-') !== false) {
                if (preg_match('/^([a-zA-Z]{2,4}-).++/i', $oid)) {
                    $oid = substr($oid, 3);
                    /** @var array $form_data */
                    $form_data['order']['id']['from'] = $oid;
                }
                else {
                    /** @var array $form_data */
                    $form_data['order']['id']['from'] = null;
                    /** @var array $form_data */
                    $form_data['order']['amazon_order'] = $oid;
                }
            }
        }

        $orderStore = new OrderSearchStore($form_data);

        if ($this->getRequest()->getIsPost()) {
            $url = Xcart::app()->router->url('dashboard:search', [], ['search' => $form_data]);
            $this->getRequest()->redirect($url);
        }

        $models = $orderStore->getModels();
        $pager = $orderStore->getPager();

        if (empty($models)) {
            $form_collapse = false;
        }

        if (isset($_GET['form_collapse'])) {
            $form_collapse = true;
        }

        echo $this->renderInternal('dashboard/search.tpl', array_merge(
                SearchHelper::getFormAndListData(),
                [
                    'pager' => $pager,
                    'models' => $models,
                    'form_data' => SearchHelper::prepareFormDataForTemplate($form_data),
                    'form_data_raw' => $form_data,
                    'form_collapse' => $form_collapse,
                ])
        );
    }

    /**
     * Искать продукты и заказы по Order # / PO # / Zip code / SKU
     * @return void
    */
    public function fastSearch(): void
    {

        $search_type = json_decode($this->getRequest()->get->get('search_type'),true);

        if (!$search_string = trim($this->getRequest()->get->get('search_string'))) {
            $this->getRequest()->redirect('admin:index');
            return;
        }

        if (!$search_type = $search_type['value'])
        {
            // search by PRODUCT SKU
            if (preg_match(self::PATTERNS['sku'], $search_string, $matches) === 1) {
                $product = ProductModel::objects()->get(['productcode' => $matches[0]]);

                //redirect if found
                if ($product) {
                    $this->redirect($product->getAdminUrl());
                    return;
                }
            }

            $this->getRequest()->redirect('admin:index');
        }

        if($search_type === 'id'){
            // search by ORDER ID with prefix
            if (preg_match(self::PATTERNS['order_id_with_prefix'], $search_string, $matches) === 1) {
                $order = OrderModel::objects()->get(['orderid' => $matches[2]]);
            }

            // search by ORDER AMAZON ID
            elseif (preg_match(self::PATTERNS['order_amazon_id'], $search_string, $matches) === 1) {
                $order = OrderModel::objects()->get(['amazonorderid' => $matches[2]]);
            }
            // redirect if ORDER FOUND
            if (isset($order)) {
                $this->redirect($order->getAdminUrl());
                return;
            }
        }

        if($search_type === 'order_po'){
            //search by ORDER PO NUMBER
            $order = OrderModel::objects()->filter(['po_number' => $search_string]);

            if (isset($order) && $order->count()) {
                $url = Xcart::app()->router->url('dashboard:search', [], ['search' => ['order' => ['po' => $search_string]]]);
                $this->getRequest()->redirect($url);
            }
        }

        if($search_type === 'zip'){
            //search by ORDER ZIPCODE
            $order = OrderModel::objects()->filter(new QOr([
                's_zipcode' => $search_string,
                'b_zipcode' => $search_string,
            ]));

            if (isset($order) && $order->count()) {
                $url = Xcart::app()->router->url('dashboard:search', [], ['search' => ['customer' => ['zip_code' => $search_string]]]);
                $this->getRequest()->redirect($url);
            }
        }
        $this->getRequest()->redirect('admin:index');
    }

    public function search_ajax_suggestion()
    {
        $data = [];

        if (isset($_GET['from']) && !empty($_GET['q'])) {
            $data = SearchHelper::getAjaxSuggestion($_GET['q'], $_GET['from']);
            $data = SearchHelper::autoCompleteClearNewLines($data);
        }

        $this->jsonResponse(['items' => $data]);
    }
}