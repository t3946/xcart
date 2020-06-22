<?php


namespace Modules\Admin\Controllers;


use Modules\Admin\Admin\DxCommunicationAdmin;
use Modules\Admin\Admin\DxContactAdmin;
use Modules\Admin\AdminModule;
use Modules\Admin\Forms\Dx\DistributorContactForm;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Distributor\Models\DistributorContactsModel;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;

class DistributorController extends BackendController
{

    public function index($mid = null, $section = 1)
    {
        if ($mid) {
            $dx = DistributorModel::objects()->get(['manufacturerid' => $mid]);
        }

        /** @var DistributorForm $form */
        if ($section == 3) {
            $admin = new DxContactAdmin();
            $admin->dxModel = $dx;
            $admin->section = $section;
            $admin->all();
            exit;
        }
        if ($section ==50) {
            $admin = new DxCommunicationAdmin();
            //$admin->dxModel = $dx;
            $admin->section = $section;
            $admin->all($dx->pk);
            exit;
        }

        $distributor_section = DistributorForm::getSection($section);

        if (!$distributor_section['form']) {
            $this->redirect("/admin/manufacturers.php?manufacturerid={$dx->manufacturerid}&distributor_section={$section}");
        }

        $form = new $distributor_section['form'];
        if ($dx) {
            $form->setInstance($dx);
        }
        if (Xcart::app()->request->getIsPost()) {
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
            if ($dx && $form->isValid()) {
                $form->save();
                $this->redirect( Xcart::app()->router->url('admin:section', [
                    'mid' => $dx->manufacturerid,
                    'section' => $section,
                ]));
            }
        }

        Xcart::app()->breadcrumbs->add($pageTitle = AdminModule::t('Distributors'),  '/admin/manufacturers.php?&word=num');
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
}