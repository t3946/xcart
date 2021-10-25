<?php


namespace Modules\Admin\Admin;


use Modules\Admin\AdminModule;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Admin\Forms\Dx\DistributorPriceForm;
use Modules\Distributor\Models\DistributorModel;
use Modules\Forms\Admin\EmailAdmin;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Modules\Admin\Contrib\Admin;
use Xcart\App\Traits\Configurator;

class DxPriceFileAdmin extends Admin
{
    public $section;
    public $ownerField = 'manufacturerid';
    public string $allTemplate = 'admin/distributor/dx-price.tpl';
    public $ownerPk;

    public function getForm() : DistributorPriceForm
    {
        return new DistributorPriceForm();
    }

    public function getSearchColumns()
    {
        return [];
    }

    public static function getName()
    {
        return 'Communication with distributor';
    }


    public function all($pk = null)
    {
        $this->ownerPk = $pk;
        $this->setBreadcrumbs();
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $form = $this->getForm();
        $dx = DistributorModel::objects()->get(['pk' => $this->ownerPk]);
        $form->setInstance($dx);

        $this->renderInternal($this->allTemplate, [
            'form' => $form,
            'section' => $this->section,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
        ]);
    }

    public function getBreadcrumbs(): array
    {
        return [[AdminModule::t('Distributors'), '/admin/manufacturers.php?&word=num'],
            [static::getName(), Xcart::app()->router->url('admin:section', [
                'mid' => $this->ownerPk,
                'section' => 50,
            ])]];
    }

    public function getInfoUrl($pk = null)
    {
        return Xcart::app()->router->url('admin:info_dx', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?: $this->getModelPk(),
            'dx' => $this->ownerPk,
        ]);
    }

/*    public function info($pk, $dx)
    {
        $this->ownerPk = $dx;
        parent::info($pk);
    }*/

}