<?php

namespace Modules\Dashboard\Controllers;

use Mindy\QueryBuilder\Expression;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Models\GroupModel;
use Modules\Dashboard\Models\InquiryAttentionTagModel;
use Modules\Dashboard\Models\InquiryTypeModel;
use Modules\Dashboard\Models\UserFiltersLinkModel;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Goods\Models\ProductQuestionModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\UserModel;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\ModelInterface;

class DashboardController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function beforeAction($action, $params)
    {
        /** check hide_no_orders_test_checkout form */

        if (!empty($_POST['mode']) && $_POST['mode'] == 'hide_no_orders_test_checkout_message') {
            Xcart::app()->request->session->add("no_orders_test_checkout_hide_time", time());

            $log_text = Xcart::app()->user->firstname . " (" . Xcart::app()->user->login . ") clicked 'Done'.";
            func_backprocess_log("Test_checkout", $log_text);
            $this->getRequest()->refresh();
        }
    }

    public function index(): void
    {
        [$models, $myModels, $questionModels, $inquiries, $inquiries_tags] = $this->prepare();

        if ($this->getRequest()->getIsAjax()) {
            $mIds = array_map(function($model){ return $model->pk; }, $myModels);
            $data = ['filters' => [], 'groups' => []];

            /** @var DashboardFilter $model */
            foreach ($models as $model) {
                $data['filters'][$model->id] = [
                    'count' => [
                        'orders' => $model->getSearchStorage()->getCashedCount(),
                        'priority' => $model->getSearchStorage()->getCachedPriorityShippingCount(),
                        'events' => (in_array($model->pk, $mIds)) ? $model->getSearchStorage()->getCachedEventsCount() : null,
                    ]
                ];
            }
            $data['questions'] = $this->render('dashboard/_product_question.tpl', ['questions' => $questionModels,]);

            $this->jsonResponse($data);
        }
        else {
            echo $this->renderInternal('dashboard/index.tpl',
                [
                    'models'  => $models,
                    'row_col' => DashboardFilter::getMaxRowCol(),
                    'myModels' => $myModels,
                    'groups'  => GroupModel::objects()->filter(['filters__name__isnull' => false])->group(['id'])->all(),
                    'questions' => $questionModels,
                    'inquiries' => $inquiries,
                    'inquiries_tags' => $inquiries_tags,
                    'user' => Xcart::app()->user,
                    'site' => SiteModel::objects()->get(['storefrontid' => Xcart::app()->request->session->get('current_storefront')])
                ]
            );
        }
    }

    public function assignments(): void
    {
        [$models, $myModels, $questionModels, $inquiries, $inquiries_tags] = $this->prepare();

        echo $this->renderInternal('dashboard/index.tpl',
            [
                'models'  => $models,
                'row_col' => DashboardFilter::getMaxRowCol(),
                'myModels' => $myModels,
                'groups'  => GroupModel::objects()->filter(['filters__name__isnull' => false])->group(['id'])->all(),
                'questions' => $questionModels,
                'inquiries' => $inquiries,
                'inquiries_tags' => $inquiries_tags,
                'user' => Xcart::app()->user,
                'site' => SiteModel::objects()->get(['storefrontid' => Xcart::app()->request->session->get('current_storefront')]),
                'mode' => 1,
            ]
        );
    }

    public function operators(): void
    {
        [$models, $myModels, $questionModels, $inquiries, $inquiries_tags] = $this->prepare();

        if ($user_ids = UserFiltersLinkModel::objects()->filter(['user__status' => 'Y'])->cache(60)->valuesList(['user_id'], true)) {
            $users = UserModel::objects()->cache(60)->all(['id__in' => \array_unique($user_ids)]);
        }

        echo $this->renderInternal('dashboard/index.tpl',
            [
                'models'  => $models,
                'row_col' => DashboardFilter::getMaxRowCol(),
                'myModels' => $myModels,
                'groups'  => GroupModel::objects()->filter(['filters__name__isnull' => false])->group(['id'])->all(),
                'questions' => $questionModels,
                'inquiries' => $inquiries,
                'inquiries_tags' => $inquiries_tags,
                'user' => Xcart::app()->user,
                'users' => $users ?? [],
                'site' => SiteModel::objects()->get(['storefrontid' => Xcart::app()->request->session->get('current_storefront')]),
                'mode' => 2
            ]
        );
    }

    private function prepare(): array
    {
        $models = DashboardFilter::objects()->filter(['enabled' => true])->cache(60)->all();
        $myModels = DashboardFilter::objects()->filter(['enabled' => true, 'users__id' => Xcart::app()->user->id])->order(['-position_row', '-position_column'])->all();
        $questionModels = ProductQuestionModel::objects()->select(['status', 'id' => new Expression('count(*)')])->exclude(['status' => ''])->group(['status'])->order(['-status'])->all();
        $inquiries = InquiryTypeModel::objects()->filter(['active' => 'Y'])->order('inquiry_type')->all();
        $inquiries_tags = InquiryAttentionTagModel::objects()->filter(['active' => 'Y'])->order(['inquiry_attn_tag'])->all();
        return [$models, $myModels, $questionModels, $inquiries, $inquiries_tags];
    }

    public function filter($id)
    {
        /** @var DashboardFilter $model */
        if ($model = DashboardFilter::objects()->get(['id' => $id])) {
            $modify = false;
            $form_data = [];

            if (!empty($_GET['search'])) {
                $form_data = OrderSearchStore::getClearedData($_GET['search']);
                $modify = true;

            }


            $orderStore = $model->getSearchStorage($form_data);
            $models = $orderStore->getModels();
            $pager = $orderStore->getPager();
            $form_data = array_merge_recursive($model->form_data, $form_data);

            if (!$modify && $pager->getTotal() != $model->getSearchStorage()->getCashedCount()) {
                $model->getSearchStorage()->clearCache();
            }

            echo $this->renderInternal('dashboard/filter_view.tpl',
                array_merge(
                    SearchHelper::getFormAndListData(),
                    [
                        'modify'        => $modify,
                        'model'         => $model,
                        'pager'         => $pager,
                        'models'        => $models,
                        'form_data'     => SearchHelper::prepareFormDataForTemplate($form_data),
                        'form_collapse' => true,
                    ]
                )
            );
        }
        else {
            $this->redirect('dashboard:index');
        }
    }

    public function settings()
    {
        $models = DashboardFilter::objects()->all();

        echo $this->renderInternal('dashboard/admin/admin_list.tpl',
            [
                'row_col' => DashboardFilter::getMaxRowCol(),
                'models'  => $models,
                'groups'  => GroupModel::objects()->all(),
            ]
        );
    }

    public function sort()
    {
        /** @var Model|ModelInterface $model */
        if (isset($_POST['id']) && $model = DashboardFilter::objects()->get(['id' => $_POST['id']])) {

            $model->setAttributes($_POST);

            if ($model->isValid() && $model->save(['position_row', 'position_column'])) {

                $this->jsonResponse(['message' => "Filter '{$model}' saved on position {$model->position_row}x{$model->position_column}"]);
            }
        }
    }

    public function mySort()
    {
        /** @var Model|ModelInterface $model */
        if (isset($_POST['id']) && $filter_model = DashboardFilter::objects()->get(['id' => $_POST['id']]))
        {

            $user = Xcart::app()->user;
            list($model) = UserFiltersLinkModel::objects()->getOrCreate(['filter_id' => $filter_model->id, 'user_id' => $user->id]);

            unset($_POST['id']);
            $model->setAttributes($_POST);

            if ($model->isValid() && $model->save(['position_row', 'position_column'])) {

                $this->jsonResponse(['message' => "Filter '{$filter_model}' saved on position {$model->position_row}x{$model->position_column}"]);
            }
        }
    }

    public function subscription($id)
    {
        $user = Xcart::app()->user;
        $class = UserModel::classNameShort();

        if (!$user->getIsGuest())
        {
            if ($this->getRequest()->getIsPost()) {
                $params = ['user_id' => $user->id, 'filter_id' => $id];

                if ($_POST[$class]) {
                    UserFiltersLinkModel::objects()->getOrCreate($params);
                }
                else {
                    UserFiltersLinkModel::objects()->filter($params)->delete();
                }
            }

            $users = [];
            $u_ids = UserFiltersLinkModel::objects()->filter(['filter_id' => $id])->valuesList(['user_id'], true);

            if ($u_ids) {
                $users = UserModel::objects()->filter(['id__in' => $u_ids, 'status' => 'Y'])->all();
            }

            echo $this->render('dashboard/subscription.tpl', [
                'id' => $id,
                'class' => $class,
                'ids' => $u_ids,
                'users' => $users,
                'model' => $user,
            ]);
        }
    }


    public function create()
    {
        $this->createOrUpdate(new DashboardFilter());
    }

    public function update($id = null)
    {
        if (!is_null($id) && $model = DashboardFilter::objects()->get(['id' => $id])) {
            $this->createOrUpdate($model);
        }
        else {
            $this->redirect('dashboard:admin_filters');
        }
    }

    /** @param Model|ModelInterface $model */
    private function createOrUpdate($model)
    {
        $class = DashboardFilter::classNameShort();
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

        echo $this->renderInternal('dashboard/admin/filter_edit.tpl',
            array_merge(
                SearchHelper::getFormAndListData(),
                [
                    'model'     => $model,
                    'groups'    => GroupModel::objects()->all(),
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
            return ['dashboard:update_filter', ['id' => $model->id]];
        }
        else if (array_key_exists('save_create', $_POST)) {
            return ['dashboard:create_filter', []];
        }
        else {
            return ['dashboard:admin_filters', []];
        }
    }
}