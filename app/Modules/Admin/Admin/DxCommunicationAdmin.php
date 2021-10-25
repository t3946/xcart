<?php


namespace Modules\Admin\Admin;


use Modules\Admin\AdminModule;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Distributor\Models\DistributorModel;
use Modules\Forms\Admin\EmailAdmin;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class DxCommunicationAdmin extends EmailAdmin
{
    public $section;
    public $ownerField = 'manufacturerid';
    public $ownerPk;
    public string $allTemplate = 'admin/distributor/dx_emails.tpl';

    public function getForm() : DistributorForm
    {
        return new DistributorForm();
    }

    public function getSearchColumns() : array
    {
        return [];
    }

    public static function getName() : string
    {
        return 'Communication with distributor';
    }


    public function all($pk = null)
    {
        $this->ownerPk = $pk;
        $this->setBreadcrumbs();
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $qs = $this->getAllQuerySet($search);
        $qs->filter(["dx_models__{$this->ownerField}" => $this->ownerPk]);

        $pagination = new Pagination($qs, [
            'pageSize' => $this->getConfig()->page_size ?: $this->pageSize,
            'pageSizes' => $this->pageSizes
        ], new QuerySetDataSource());

        if (Xcart::app()->request->get->has($pagination->getPageSizeKey())) {
            $this->getConfig()->page_size = Xcart::app()->request->get->get($pagination->getPageSizeKey());
            $this->getConfig()->save();
        }
        $form = $this->getForm();
        $dx = DistributorModel::objects()->get(['pk' => $this->ownerPk]);
        $form->setInstance($dx);

        $this->renderInternal($this->allTemplate, [
            'form' => $form,
            'section' => $this->section,
            'objects' => $pagination->paginate(),
            'pagination' => $pagination,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
            'canSort' => $this->getCanSort($qs),
        ]);
    }

    public function getBreadcrumbs(): array
    {
        return
        [
            [
                AdminModule::t('Distributors'),
                Xcart::app()->router->url('admin:list', [
                'module' => 'Distributor',
                'admin' => 'DistributorAdmin'])
            ],
            [
                static::getName(), Xcart::app()->router->url('admin:section', [
                    'mid' => $this->ownerPk,
                    'section' => 50,
                ])
            ]
        ];
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

    public function info($pk)
    {
        $this->ownerPk = $pk;
        parent::info($pk);
    }

}