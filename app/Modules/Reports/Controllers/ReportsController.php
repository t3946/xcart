<?php

namespace Modules\Reports\Controllers;

use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Aggregation\Sum;
use Mindy\QueryBuilder\Expression;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Reports\Models\ReportModel;
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
        $filter = $distributorCodes = [];
        if (!empty($_GET['search'])) {
            $form_data = OrderSearchStore::getClearedData($_GET['search']);
        } else {
            $form_data = [
                'order' => [
                    'date' => SearchHelper::getDefaultSearchDate(),
                ],
            ];
        }
        $orderStore = (new OrderSearchStore($form_data));

        switch ($form_data['order']['profit_margin']) {
            case "profit" :
                $filter = ['group.profit_margin__lt' => 100];
                break;
            case "profit15" :
                $filter = ['group.profit_margin__lte' => $form_data['order']['profit_margin_profit15_edit']];
                break;
            case "profit_between" :
                $filter = [
                    'group.profit_margin__gte' => $form_data['order']['profit_margin_profitbetween_start'],
                    'group.profit_margin__lt' => $form_data['order']['profit_margin_profitbetween_end'],
                ];
                break;
        }
        $qs = $orderStore->getQuerySet();

        $qsum = clone $qs;
        $qsum->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
        $qsum->join('inner join', 'xcart_manufacturers', ['m.manufacturerid' => 'group.manufacturerid'], 'm');
        $qsum->group([]);
        $qsum->select([
            new Sum('group.total_gross', 'total_gross'),
            new Sum('group.total_net', 'total_net'),
            new Sum('group.total_gst', 'total_gst'),
            new Sum('group.total_pst', 'total_pst'),
            new Sum('group.accounting_net_0', 'accounting_net_0'),
            new Sum('group.accounting_gst_0', 'accounting_gst_0'),
            new Sum('group.accounting_pst_0', 'accounting_pst_0'),
            new Sum('group.accounting_gross_0', 'accounting_gross_0'),
            new Sum('group.accounting_net_1_cost_to_us', 'accounting_net_1_cost_to_us'),
            new Sum('group.accounting_gst_1_cost_to_us', 'accounting_gst_1_cost_to_us'),
            new Sum('group.accounting_pst_1_cost_to_us', 'accounting_pst_1_cost_to_us'),
            new Sum('group.accounting_gross_1_cost_to_us', 'accounting_gross_1_cost_to_us'),
            new Sum('group.accounting_net_2_shipping', 'accounting_net_2_shipping'),
            new Sum('group.accounting_gst_2_shipping', 'accounting_gst_2_shipping'),
            new Sum('group.accounting_pst_2_shipping', 'accounting_pst_2_shipping'),
            new Sum('group.accounting_gross_2_shipping', 'accounting_gross_2_shipping'),
            new Sum('group.accounting_net_3_ref_to_cust', 'accounting_net_3_ref_to_cust'),
            new Sum('group.accounting_gst_3_ref_to_cust', 'accounting_gst_3_ref_to_cust'),
            new Sum('group.accounting_pst_3_ref_to_cust', 'accounting_pst_3_ref_to_cust'),
            new Sum('group.accounting_gross_3_ref_to_cust', 'accounting_gross_3_ref_to_cust'),
            new Sum('group.accounting_net_4_ref_to_us', 'accounting_net_4_ref_to_us'),
            new Sum('group.accounting_gst_4_ref_to_us', 'accounting_gst_4_ref_to_us'),
            new Sum('group.accounting_pst_4_ref_to_us', 'accounting_pst_4_ref_to_us'),
            new Sum('group.accounting_gross_4_ref_to_us', 'accounting_gross_4_ref_to_us'),
            new Sum('group.accounting_net_5_profit', 'accounting_net_5_profit'),
            new Sum('group.accounting_gst_5_profit', 'accounting_gst_5_profit'),
            new Sum('group.accounting_pst_5_profit', 'accounting_pst_5_profit'),
            new Sum('group.accounting_gross_5_profit', 'accounting_gross_5_profit'),
            'codes' => new Expression("GROUP_CONCAT(DISTINCT m.code ORDER BY m.code)")
        ]);

        $totals = Connection::getInstance()->fetchAssoc($qsum->getSQL());
        if ($totals) {
            if (floatval($totals['accounting_net_0']) != 0) {
                $totals['total_margin'] = round($totals['accounting_net_5_profit'] / $totals['accounting_net_0'] * 100, 2);
            }
            if (floatval($totals['accounting_gross_0']) != 0) {
                $totals['real_pm'] = round($totals['accounting_gross_5_profit'] / $totals['accounting_gross_0'] * 100, 2);
            }
            $totals["real_net"] = $totals['accounting_net_0'] + $totals['accounting_net_4_ref_to_us'] - $totals['accounting_gross_3_ref_to_cust'];
        }

        $orderModels = $qs->filter($filter)->paginate();
        $pager = $orderStore->getPager();

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