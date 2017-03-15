<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;

class SearchController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $session       = Xcart::app()->request->session;
        $form_collapse = false;

        if (!empty($_GET['search']['reset']) == 'reset') {
            $session->remove('search_order_form');
            $this->refresh();
        }

        if (!empty($_GET['search'])) {
            $form_collapse = true;

            $session->add('search_order_form', OrderSearchStore::getClearedData($_GET['search']));

            if (isset($_GET['search']['new_list'])) {
                $session->add('search_new_template', $_GET['search']['new_list']);
            }
        }

        $form_data = $session->get('search_order_form', [
            'order'    => [
                'date' => SearchHelper::getDefaultSearchDate(),
            ],
        ]);

        if (!is_array($form_data)) {
            $form_data = [];
        }

        $form_data['new_list'] = $session->get('search_new_template', 1);

        $orderStore = new OrderSearchStore($form_data);
        $orderStore->setOrder(['-date', '-orderid']);

        $models = $orderStore->getModels();
        $pager = $orderStore->getPager();

        if (empty($models)) {
            $form_collapse = false;
        }

        echo $this->renderInternal('dashboard/search.tpl', array_merge(
            SearchHelper::getFormAndListData(),
            [
                'pager'         => $pager,
                'models'        => $models,
                'form_data'     => SearchHelper::prepareFormDataForTemplate($form_data),
                'new_template'  => $session->get('search_new_template', 1),
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