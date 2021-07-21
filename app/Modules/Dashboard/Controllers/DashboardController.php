<?php

namespace Modules\Dashboard\Controllers;

use Exception;
use Xcart\App\QueryBuilder\Expression;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Models\GroupModel;
use Modules\Dashboard\Models\InquiryAttentionTagModel;
use Modules\Dashboard\Models\InquiryTypeModel;
use Modules\Dashboard\Models\UserFiltersLinkModel;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Forms\Admin\EmailAdmin;
use Modules\Forms\Models\EmailModel;
use Modules\Goods\Models\ProductQuestionModel;
use Modules\Order\Models\OrderModel;
use Modules\PBX\Admin\PBXAdmin;
use Modules\PBX\Models\PbxAnveoCallModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\UserModel;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\ModelInterface;
use Xcart\App\Store\BaseStore;

class DashboardController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index(): void
    {
        [$models, $myModels, $questionModels, $inquiries, $inquiries_tags] = $this->prepare();

        if ($this->getRequest()->getIsAjax()) {
            $mIds = array_map(static fn($model) => $model->pk, $myModels);
            $data = ['filters' => [], 'groups' => []];

            /** @var DashboardFilter $model */
            foreach ($models as $model) {
                $storage = $model->getSearchStorage();
                $data['filters'][$model->id] = [
                    'count' => [
                        'orders' => $storage->getCashedCount(),
                        'priority' => $storage->getCachedPriorityShippingCount(),
                        'events' => in_array($model->pk, $mIds) ? $storage->getCachedEventsCount() : null,
                    ]
                ];
            }
            $data['questions'] = $this->render('dashboard/_product_question.tpl', ['questions' => $questionModels,]);

            $this->jsonResponse($data);
        } else {
            $add_tabs = GlobalConfigModel::objects()->filter(['category' => 'Order_Dashboard_Tabs'])->all();

            echo $this->renderInternal('dashboard/index.tpl',
                [
                    'models' => $models,
                    'row_col' => DashboardFilter::getMaxRowCol(),
                    'myModels' => $myModels,
                    'groups' => GroupModel::objects()->filter(['filters__name__isnull' => false])->group(['id'])->all(),
                    'questions' => $questionModels,
                    'inquiries' => $inquiries,
                    'inquiries_tags' => $inquiries_tags,
                    'user' => Xcart::app()->user,
                    'site' => SiteModel::objects()->get(['storefrontid' => Xcart::app()->request->session->get('current_storefront')]),
                    'add_tabs' => $add_tabs ?? []
                ]
            );
        }
    }

    public function assignments(): void
    {
        [$models, $myModels, $questionModels, $inquiries, $inquiries_tags] = $this->prepare();

        echo $this->renderInternal('dashboard/index.tpl',
            [
                'models' => $models,
                'row_col' => DashboardFilter::getMaxRowCol(),
                'myModels' => $myModels,
                'groups' => GroupModel::objects()->filter(['filters__name__isnull' => false])->group(['id'])->all(),
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
            $users = UserModel::objects()->cache(60)->all(['id__in' => array_unique($user_ids)]);
        }

        echo $this->renderInternal('dashboard/index.tpl',
            [
                'models' => $models,
                'row_col' => DashboardFilter::getMaxRowCol(),
                'myModels' => $myModels,
                'groups' => GroupModel::objects()->filter(['filters__name__isnull' => false])->group(['id'])->all(),
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
        $myModels = Xcart::app()->user ? DashboardFilter::objects()->filter(['enabled' => true, 'users__id' => Xcart::app()->user->id])->order(['-position_row', '-position_column'])->all() : [];
        $questionModels = ProductQuestionModel::objects()->select(['status', 'id' => new Expression('count(*)')])->exclude(['status' => ''])->group(['status'])->order(['-status'])->all();
        $inquiries = InquiryTypeModel::objects()->filter(['active' => 'Y'])->order('inquiry_type')->all();
        $inquiries_tags = InquiryAttentionTagModel::objects()->filter(['active' => 'Y'])->order(['inquiry_attn_tag'])->all();
        return [$models, $myModels, $questionModels, $inquiries, $inquiries_tags];
    }

    public function filter($id): void
    {
        /** @var DashboardFilter $model */
        if ($model = DashboardFilter::objects()->get(['id' => $id])) {
            $modify = false;
            $form_data = [];

            if (!empty($_GET['search'])) {
                $form_data = BaseStore::getClearedData($_GET['search']);
                $modify = true;
            }

            $orderStore = $model->getSearchStorage($form_data);

            $models = $orderStore->getModels();
            $pager = $orderStore->getPager();
            $form_data = array_merge_recursive($model->form_data, $form_data);

            if (!$modify && $pager->getTotal() !== $model->getSearchStorage()->getCashedCount()) {
                $model->getSearchStorage()->clearCache();
            }

            if (!$model->entity || $model->entity === OrderModel::class) {
                echo $this->renderInternal($orderStore::VIEW_TEMPLATE,
                    array_merge(
                        SearchHelper::getFormAndListData(),
                        [
                            'modify' => $modify,
                            'model' => $model,
                            'pager' => $pager,
                            'models' => $models,
                            'form_data' => SearchHelper::prepareFormDataForTemplate($form_data),
                            'form_collapse' => true,
                        ]
                    )
                );
            } elseif ($model->entity === EmailModel::class) {
                $admin = new EmailAdmin();
                echo $this->renderInternal($orderStore::VIEW_TEMPLATE, [
                    'objects' => $pager->paginate(),
                    'pagination' => $pager,
                    'admin' => $admin,
                    'columns' => $admin->buildListColumns(),
                ]);
            } elseif ($model->entity === PbxAnveoCallModel::class) {
                $admin = new PBXAdmin();
                echo $this->renderInternal($orderStore::VIEW_TEMPLATE, [
                    'objects' => $pager->paginate(),
                    'pagination' => $pager,
                    'admin' => $admin,
                    'columns' => $admin->buildListColumns(),
                ]);
            }
        } else {
            $this->redirect('dashboard:index');
        }
    }

    public function settings(): void
    {
        $models = DashboardFilter::objects()->all();

        echo $this->renderInternal('dashboard/admin/admin_list.tpl',
            [
                'row_col' => DashboardFilter::getMaxRowCol(),
                'models' => $models,
                'groups' => GroupModel::objects()->all(),
            ]
        );
    }

    public function sort(): void
    {
        /** @var Model|ModelInterface $model */
        if (isset($_POST['id']) && $model = DashboardFilter::objects()->get(['id' => $_POST['id']])) {
            $model->setAttributes($_POST);

            if ($model->isValid() && $model->save(['position_row', 'position_column'])) {
                $this->jsonResponse(['message' => "Filter '{$model}' saved on position {$model->position_row}x{$model->position_column}"]);
            }
        }
    }

    public function mySort(): void
    {
        /** @var Model|ModelInterface $model */
        if (isset($_POST['id']) && $filter_model = DashboardFilter::objects()->get(['id' => $_POST['id']])) {
            $user = Xcart::app()->user;
            [$model] = UserFiltersLinkModel::objects()->getOrCreate(['filter_id' => $filter_model->id, 'user_id' => $user->id]);

            unset($_POST['id']);
            $model->setAttributes($_POST);

            if ($model->isValid() && $model->save(['position_row', 'position_column'])) {
                $this->jsonResponse(['message' => "Filter '{$filter_model}' saved on position {$model->position_row}x{$model->position_column}"]);
            }
        }
    }

    public function subscription($id): void
    {
        $user = Xcart::app()->user;
        $super_user = ['pavel','sergey2', 'roman_n'];
        $is_super_user = in_array($user->login, $super_user, true);
        $class = UserModel::classNameShort();

        if (!$user->getIsGuest()) {
            if ($this->getRequest()->getIsPost()) {
                $user_ids = $_POST[$class]['id'];
                foreach ($user_ids as $user_id) {
                    $params = ['user_id' => $user_id, 'filter_id' => $id];
                    if ($_POST[$class] && $user_id) {
                        UserFiltersLinkModel::objects()->getOrCreate($params);
                    }
                }
                if ($user_ids && $is_super_user) {
                    UserFiltersLinkModel::objects()
                        ->exclude(['user_id__in' => $user_ids])
                        ->filter(['filter_id' => $id])
                        ->delete();
                }
            }

            $users = [];
            $u_ids = UserFiltersLinkModel::objects()->filter(['filter_id' => $id])->valuesList(['user_id'], true);

            if ($u_ids) {
                $users = UserModel::objects()->filter(['id__in' => $u_ids, 'status' => 'Y'])->all();
            }

            echo $this->render('dashboard/subscription.tpl', [
                'id' => $id,
                'filter' => DashboardFilter::objects()->get(['id' => $id]),
                'class' => $class,
                'ids' => $u_ids,
                'users' => $users,
                'model' => $user,
                'is_super_user' => $is_super_user,
                'all_users' => $is_super_user
                    ? UserModel::objects()
                        ->exclude([
                            'id__in' => array_merge($u_ids, [$user->id])
                        ])
                        ->exclude(['position__in' => ['VRS', 'programmer']])
                        ->filter(['status' => 'Y', 'usertype' => 'A'])
                        ->order(['firstname'])
                    : []
            ]);
        }
    }


    public function create(): void
    {
        $this->createOrUpdate(new DashboardFilter());
    }

    public function update($id = null): void
    {
        if (!is_null($id) && $model = DashboardFilter::objects()->get(['id' => $id])) {
            $this->createOrUpdate($model);
        } else {
            $this->redirect('dashboard:admin_filters');
        }
    }

    /**
     * @param Model|ModelInterface $model
     * @throws Exception
     */
    private function createOrUpdate($model): void
    {
        $class = DashboardFilter::classNameShort();
        if (isset($_POST['delete']) && $model->delete()) {
            $this->autoRedirect($model);
        }

        if ($_POST[$class]) {
            if ($_POST['search']) {
                $model->form_data = OrderSearchStore::getClearedData($_POST['search']);
            }
            $model->setAttributes($_POST[$class]);
            if ($model->isValid() && $model->save()) {
                $this->autoRedirect($model);
            }
        }

        echo $this->renderInternal('dashboard/admin/filter_edit.tpl',
            array_merge(
                SearchHelper::getFormAndListData(),
                [
                    'model' => $model,
                    'groups' => GroupModel::objects()->all(),
                    'form_data' => SearchHelper::prepareFormDataForTemplate($model->form_data),
                ]
            )
        );
    }

    private function autoRedirect($model): void
    {
        [$url, $params] = $this->autoActions($model);
        $this->redirect($url, $params, 303);
    }

    private function autoActions($model): array
    {
        if (array_key_exists('save_continue', $_POST)) {
            return ['dashboard:update_filter', ['id' => $model->id]];
        }

        if (array_key_exists('save_create', $_POST)) {
            return ['dashboard:create_filter', []];
        }

        return ['dashboard:admin_filters', []];
    }
}