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

        if (!empty($_REQUEST['search'])) {
            $form_collapse = true;
            $form_data = OrderSearchStore::getClearedData($_REQUEST['search']);
        }

        if (!is_array($form_data)) {
            $form_data = [
                'order'    => [
                    'date' => SearchHelper::getDefaultSearchDate(),
                ],
            ];
        }

        $orderStore = new OrderSearchStore($form_data);
        $orderStore->setOrder(['-date', '-orderid']);

        $models = $orderStore->getModels();
        $pager = $orderStore->getPager();

        if ( $orderStore->getQuerySet()->count() == 1 && !empty($_REQUEST['fast_search'])) {
            /** @var \Modules\Order\Models\OrderModel $model */
            $model = $models[0];
            $this->redirect( $model->getAdminUrl() );
        }
        else {
            if ($this->getRequest()->getIsPost()) {
                $url = Xcart::app()->router->url('dashboard:search', [], ['search' => $form_data]);
                $this->getRequest()->redirect($url);
//                $this->getRequest()->redirect('dashboard:search', ['search' => $form_data]);
            }
        }

        if (empty($models)) {
            $form_collapse = false;
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