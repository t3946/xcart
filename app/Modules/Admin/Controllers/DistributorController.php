<?php


namespace Modules\Admin\Controllers;


use Modules\Admin\Admin\DxContactAdmin;
use Modules\Admin\AdminModule;
use Modules\Admin\Forms\Dx\DistributorContactForm;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Distributor\Models\DistributorContactsModel;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;

class DistributorController extends BackendController
{

    public function index($mid, $section)
    {


        $dx = DistributorModel::objects()->get(['manufacturerid' => $mid]);
        /** @var DistributorForm $form */
        if ($section == 3) {
            $admin = new DxContactAdmin();
            $admin->dxModel = $dx;
            $admin->section = $section;
            $admin->all();
            exit;
        }

        $distributor_sections = DistributorForm::getSections();

        if (!$distributor_sections[$section]['form']) {
            $this->redirect("/admin/manufacturers.php?manufacturerid={$dx->manufacturerid}&distributor_section={$section}");
        }

        $form = new $distributor_sections[$section]['form'];
        $form->setInstance($dx);

        if (Xcart::app()->request->getIsPost()) {
            $form->populate(Xcart::app()->request->post, $_FILES);
            if ($form->isValid()) {
                $form->save();
                $this->redirect($this->getRequest()->getUrl());
            }
        }

        Xcart::app()->breadcrumbs->add($pageTitle = AdminModule::t('Distributors'));

        $section = $section ?? 1;

        echo $this->renderInSmarty("admin/distributor/dx_{$section}.tpl", [
            'page_title' => $pageTitle,
            'form' => $form,
            'section' => $section,
        ]);
    }
}