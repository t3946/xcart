<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Controller\AdminController;
use Xcart\App\Main\Xcart;

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
        $models     = $orderStore->getModels();
        $pager      = $orderStore->getPager();

        if (empty($models)) {
            $form_collapse = false;
        }

        $content = $this->render('dashboard/search_form.tpl', array_merge(
            SearchHelper::getFormAndListData(),
            [
                'pager'         => $pager,
                'models'        => $models,
                'form_data'     => SearchHelper::prepareFormDataForTemplate($form_data),
                'new_template'  => $session->get('search_new_template', 1),
                'form_collapse' => $form_collapse,
            ])
        );

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
            $data = SearchHelper::getAjaxSuggestion($_GET['q'], $_GET['from']);
            $data = SearchHelper::autoCompleteClearNewLines($data);
        }

        $this->jsonResponse($data);
    }
}