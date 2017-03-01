<?php

namespace Modules\Dashboard\Controllers;

use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Models\GroupModel;
use Modules\Dashboard\Models\UserFiltersLinkModel;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\User\Models\UserModel;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\ModelInterface;

class DashboardController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $models = DashboardFilter::objects()->filter(['enabled' => true])->all();

        if ($this->getRequest()->getIsAjax()) {
            $data = [];

            /** @var DashboardFilter $model */
            foreach ($models as $model) {
                $data[$model->id] = [
                    'count' => $model->getSearchStorage()->getCashedCount(),
                ];
            }

            $this->jsonResponse($data);
        }
        else {
            echo $this->renderInternal('dashboard/index.tpl',
                [
                    'models'  => $models,
                    'row_col' => DashboardFilter::getMaxRowCol(),
                    'myModels' => DashboardFilter::objects()->filter(['enabled' => true, 'users__login' => Xcart::app()->request->session->get('login')])->order(['position_row', 'position_column'])->all(),
                    'groups'  => GroupModel::objects()->filter(['filters__name__isnull' => false])->group(['id'])->all(),
                ]
            );
        }


    }

    public function filter($id)
    {
        /** @var DashboardFilter $model */
        if ($model = DashboardFilter::objects()->get(['id' => $id])) {
            $session = Xcart::app()->request->session;
            $orderStore = $model->getSearchStorage();
            $models = $orderStore->getModels();
            $pager = $orderStore->getPager();

            echo $this->renderInternal('dashboard/filter_view.tpl',
                array_merge(
                    SearchHelper::getFormAndListData(),
                    [
                        'model'         => $model,
                        'pager'         => $pager,
                        'models'        => $models,
                        'form_data'     => SearchHelper::prepareFormDataForTemplate($model->form_data),
                        'new_template'  => $session->get('search_new_template', 1),
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
        if (isset($_POST['id']) && $model = DashboardFilter::objects()->get(['id' => $_POST['id']]))
        {

            $user = UserModel::objects()->get(['login' => Xcart::app()->request->session->get('login')]);
            $model = UserFiltersLinkModel::objects()->getOrCreate(['filter_id' => $model->id, 'user_id' => $user->id]);

            unset($_POST['id']);
            $model->setAttributes($_POST);

            if ($model->isValid() && $model->save(['position_row', 'position_column'])) {

                $this->jsonResponse(['message' => "Filter '{$model}' saved on position {$model->position_row}x{$model->position_column}"]);
            }
        }
    }

    public function subscription($id)
    {
        $class = UserModel::classNameShort();
        $model = UserModel::objects()->get(['login' => Xcart::app()->request->session->get('login')]);

        if ($this->getRequest()->getIsPost()) {
            if ($_POST[$class]) {
                UserFiltersLinkModel::objects()->getOrCreate(['user_id' => $model->id, 'filter_id' => $id]);
            }
            else {
                UserFiltersLinkModel::objects()->filter(['user_id' => $model->id, 'filter_id' => $id])->delete();
            }
        }

        $users = [];
        $u_ids = UserFiltersLinkModel::objects()->filter(['filter_id' => $id])->valuesList(['user_id'], true);

        if ($u_ids) {
            $users = UserModel::objects()->filter(['id__in' => $u_ids])->all();
//            $users = UserModel::objects()->filter(['id__in' => []])->all();
        }

        echo $this->render('dashboard/subscription.tpl', [
            'id' => $id,
            'class' => $class,
            'ids' => $u_ids,
            'users' => $users,
            'model' => $model,
        ]);
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

        $this->redirect('dashboard:admin_filters');
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
                    'groups'    => GroupModel::objects()->asArray()->all(),
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