<?php


namespace Modules\Admin\Controllers;


use Modules\Admin\Admin\DxCommunicationAdmin;
use Modules\Admin\Admin\DxContactAdmin;
use Modules\Admin\Admin\DxContactsAdmin;
use Modules\Admin\Admin\DxPriceFileAdmin;
use Modules\Admin\Admin\DxProductsAdmin;
use Modules\Admin\AdminModule;
use Modules\Admin\Forms\Dx\DistributorContactForm;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Admin\Forms\Dx\DistributorGeneralForm;
use Modules\Core\Models\LanguageModel;
use Modules\Distributor\Models\DistributorContactsModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Admin\ProductAdmin;
use Modules\Sites\Helpers\StorageHelper;
use Modules\User\Models\RoleModel;
use Throwable;
use Xcart\App\Exceptions\Exception;
use Xcart\App\Main\Xcart;

class DistributorController extends BackendController
{

    public function index($mid = null, $section = 1)
    {
        $user = Xcart::app()->user;
        if ($mid !== null) {
            $dx = DistributorModel::objects()->get(['manufacturerid' => $mid]);
        }

        $distributor_section = DistributorForm::getSection($section);
        if ($distributor_section['form']){
            //инициализация формы
            $form = new $distributor_section['form']();

            if ($dx) {
                $form->setInstance($dx);
            }
            $general_form = new DistributorGeneralForm($dx);

            //данные для клиентской части
            if ($dx) {
                $distributor_base_data = [
                    'reference' => [
                        'mainInfoTitle' => "$dx ({$dx->code})",
                        'description' => (string)LanguageModel::translate('txt_manufacturers_top_text'),
                        'time' => $dx->getDistributorTime()->format('H:i'),
                        'phone' => $dx->getPhoneNormalized(),
                        'isGoodTimeToSendEmail' => $dx->isGoodTimeToSendEmail(),
                        'normalizedPhone' => $dx->getPhoneNormalized(),
                        'lastOrderHistoryLink' => $dx->getAdminOrdersUrl(6),
                        'distributorsLink' => Xcart::app()->router->url('admin:list', [
                            'module' => 'Distributor',
                            'admin' => 'DistributorAdmin'
                        ]),
                        'currentSectionKey' => (int)$section,
                    ],

                    'sections' => $general_form::getSectionsArray(function (&$sub_section) use($form) {
                        $sub_section['url'] = $form->getInstance()->getAdminUrl($sub_section['key']);
                    }),
                ];
            }

            StorageHelper::push($distributor_base_data, null, 'distributor');
        }

        /** @var DistributorModel $dx */
        if ($mid && $dx && $dx->provider !== $user->login) {
            if (
                $user->hasRole('vrs')
                || Xcart::app()->user->hasRole('vrv')
                && $user->childs->filter(['login' => $dx->provider])->count() === 0
            ) {
                Xcart::app()->request->redirect('/admin/error_message.php?access_denied&id=25');
            }
        }



        /** @var DistributorForm $form */
        if ($section == 3) {
            $admin = new DxContactsAdmin();
            $admin->dxModel = $dx;
            $admin->section = $section;
            $admin->all();
            exit;
        } elseif ($section == 50) {
            $admin = new DxCommunicationAdmin();
            //$admin->dxModel = $dx;
            $admin->section = $section;
            $admin->all($dx->pk);
            exit;
        } elseif ($section == 15) {
            $admin = new DxPriceFileAdmin();
            $admin->all($dx->pk);
            exit;
        }elseif ($section == 52) {
            $admin = new DxProductsAdmin();
            $admin->dxModel = $dx;
            $admin->section = $section;
            $admin->all($dx->pk);
            exit;
        }

        if (!$distributor_section['form']) {
            $this->redirect("/admin/manufacturers.php?manufacturerid={$dx->manufacturerid}&distributor_section={$section}");
        }

        //сохранение формы
        if (Xcart::app()->request->getIsPost()) {

            if ($user->hasRole(RoleModel::ROLE_FEED_QUALITY_SLUG)) {
                Xcart::app()->request->redirect('/admin/error_message.php?access_denied&id=25');
            }

            $form->populate(Xcart::app()->request->post, $_FILES);
            if (!$dx) {
                if (!DistributorModel::objects()->filter(['code' => $form->code->getValue()])->count()) {
                    $dx = new DistributorModel(array_merge($form->getAttributes(), ['provider' => Xcart::app()->user->login]));
                    $dx->save();
                    $form->setInstance($dx);
                } else {
                    Xcart::app()->flash->error('Distributor code you entered already exists in the database.');
                }
            }
            if ($dx) {
                try {
                    if ($form->isValid()) {
                        $form->save();
                        $this->redirect( Xcart::app()->router->url('admin:section', [
                            'mid' => $dx->manufacturerid,
                            'section' => $section,
                        ]));
                    }
                }
                catch (Throwable $e) {
                    Xcart::app()->flash->error($e->getMessage());
                }
            }
        }

        //хлебные крошки
        Xcart::app()->breadcrumbs->add($pageTitle = AdminModule::t('Distributors'),
            Xcart::app()->router->url('admin:list', [
                'module' => 'Distributor',
                'admin' => 'DistributorAdmin'
            ])
        );

        if (!$dx) {
            Xcart::app()->breadcrumbs->add($pageTitle = AdminModule::t('Add Distributor'));
        } else {
            Xcart::app()->breadcrumbs->add($distributor_section['title']);
        }

        echo $this->renderInSmarty("admin/distributor/dx_{$section}.tpl", [
            'page_title' => $pageTitle,
            'section_title' => $distributor_section['title'] ?? '',
            'form' => $form,
            'section' => $section,
        ]);
    }

    public function info($module, $admin, $pk, $dx)
    {
        $class = "Modules\\{$module}\\Admin\\{$admin}";
        if (class_exists($class)) {
            $admin = new $class();
        } else {
            $this->error(404);
        }
        $admin->info($pk, $dx);
    }

    public function contact_sort ($mid): void
    {
        /** @var DistributorModel $dx */
        $dx = DistributorModel::objects()->get(['pk' => $mid]);
        $admin = new DxContactsAdmin();
        $admin->dxModel = $dx;
        $pkList = isset($_POST['pk_list']) && is_array($_POST['pk_list']) ? $_POST['pk_list'] : [];
        $to = $_POST['to'] ?? null;
        $prev = $_POST['prev'] ?? null;
        $next = $_POST['next'] ?? null;

        $admin->sort($pkList, $to , $prev, $next);
    }

    public function contact_create ($mid): void
    {
        /** @var DistributorModel $dx */
        $dx = DistributorModel::objects()->get(['pk' => $mid]);
        $admin = new DxContactsAdmin();
        $admin->dxModel = $dx;
        $admin->create();
    }
}