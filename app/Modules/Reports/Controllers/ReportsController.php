<?php

namespace Modules\Reports\Controllers;

use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Aggregation\Sum;
use Mindy\QueryBuilder\Expression;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Reports\Models\ReportModel;
use Modules\Reports\Stores\ReportsStore;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\ModelInterface;
use Xcart\Connection;

class ReportsController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $form_collapse = false;

        if (!empty($_GET['search']['reset']) == 'reset') {
            $this->refresh();
        }

        if (!empty($_GET['search'])) {
            $form_collapse = true;
            $form_data = OrderSearchStore::getClearedData($_GET['search']);
        } else {
            $form_data = [
                'order' => [
                    'date' => SearchHelper::getDefaultSearchDate(),
                ],
            ];
        }

        /*$orderStore = new OrderSearchStore($form_data)->getQuerySet();
        $orderStore->setOrder(['-date', '-orderid']);

        $models = $orderStore->getModels();
        $pager = $orderStore->getPager();*/

        if (empty($models)) {
            $form_collapse = false;
        }

        echo $this->renderInternal('reports/search.tpl', array_merge(
                SearchHelper::getFormAndListData(),
                [
                    'pager' => $pager,
                    'models' => $models,
                    'form_data' => SearchHelper::prepareFormDataForTemplate($form_data),
                    'form_collapse' => $form_collapse,
                ])
        );
    }

    public function view()
    {
        if (!empty($_GET['search'])) {
            $form_data = OrderSearchStore::getClearedData($_GET['search']);
        } else {
            $form_data = [
                'order' => [
                    'date' => SearchHelper::getDefaultSearchDate(),
                ],
            ];
        }
        $reportStore = new ReportsStore($form_data);

        $orderModels = $reportStore->getModels();
        $pager = $reportStore->getPager();
        $totals = $reportStore->getTotals();

        echo $this->render('reports/view.tpl', array_merge(
                SearchHelper::getFormAndListData(),
                [
                    'pager' => $pager,
                    'models' => $orderModels,
                    'totals' => $totals,
                    'form_data' => SearchHelper::prepareFormDataForTemplate($form_data),
                ])
        );
    }

    public function create()
    {
        $this->createOrUpdate(new ReportModel());
    }

    public function update($id = null)
    {
        if (!is_null($id) && $model = ReportModel::objects()->get(['id' => $id])) {
            $this->createOrUpdate($model);
        }

        $this->redirect('reports:index');
    }

    /** @param Model|ModelInterface $model */
    private function createOrUpdate($model)
    {
        $class = ReportModel::classNameShort();
        if (isset($_POST['delete'])) {
            if ($model->delete()) {
                $this->autoRedirect($model);
            }
        }

        if ($_POST[$class] && $_POST['search']) {
            $model->setAttributes($_POST[$class]);
            $model->form_data = OrderSearchStore::getClearedData($_POST['search']);

            if ($model->isValid() && $model->save()) {
                $this->autoRedirect($model);
            }
        }

        echo $this->renderInternal('reports/admin/report_edit.tpl',
            array_merge(
                SearchHelper::getFormAndListData(),
                [
                    'model' => $model,
                    'form_data' => SearchHelper::prepareFormDataForTemplate($model->form_data),
                ]
            )
        );
    }

    private function autoRedirect($model)
    {
        list($url, $params) = $this->autoActions($model);
        $this->redirect($url, $params, 303);
    }

    private function autoActions($model)
    {
        if (array_key_exists('save_continue', $_POST)) {
            return ['reports:update_report', ['id' => $model->id]];
        } else if (array_key_exists('save_create', $_POST)) {
            return ['reports:create_report', []];
        } else {
            return ['reports:index', []];
        }
    }
}