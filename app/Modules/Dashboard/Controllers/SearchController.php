<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;

class SearchController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $session       = Xcart::app()->request->session;
        $form_collapse = false;

        if (!empty($_REQUEST['search'])) {
            $form_collapse = true;
            $form_data = OrderSearchStore::getClearedData($_REQUEST['search']);
        }

        if (!is_array($form_data)) {
            $form_data = [
                'order' => [
                    'date' => SearchHelper::getDefaultSearchDate(),
                ],
            ];
        }


        if (!empty($_REQUEST['fast_search'])) {

            if ( !empty($form_data['order']['id']['from']) ) {

                $oid = $form_data['order']['id']['from'];

                if (strpos($oid, '-') !== false) {
                    if (preg_match('/^([a-zA-Z]{2,4}-).++/i', $oid)) {
                        $oid = substr($oid, 3);
                        $form_data['order']['id']['from'] = $oid;
                    }
                    else {
                        $form_data['order']['id']['from'] = null;
                        $form_data['order']['amazon_order'] = $oid;
                    }
                }
            }
        }

        $orderStore = new OrderSearchStore($form_data);
        $orderStore->setOrder(['-date', '-orderid']);

        if (!empty($_REQUEST['fast_search'])) {

            $qs = $orderStore->getQuerySet();

            if ($qs->count() == 1) {
                $model = $qs->limit(1)->get();
                $this->redirect( $model->getAdminUrl() );
            }
        }

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
                'pager'         => $pager,
                'models'        => $models,
                'form_data'     => SearchHelper::prepareFormDataForTemplate($form_data),
                'form_collapse' => $form_collapse,
            ])
        );
    }

    public function search_ajax_suggestion()
    {
        $data = [];

        if (isset($_GET['from']) && !empty($_GET['q'])) {
            $data = SearchHelper::getAjaxSuggestion($_GET['q'], $_GET['from']);
            $data = SearchHelper::autoCompleteClearNewLines($data);
        }

        $this->jsonResponse($data);
    }
}