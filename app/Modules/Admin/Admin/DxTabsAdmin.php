<?php


namespace Modules\Admin\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Admin\Forms\Dx\DistributorTabForm;
use Modules\Distributor\Models\DistributorTabModel;

class DxTabsAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'distributor';
    public ?string $sort = 'position';

    public function getListColumns() : array
    {
        return [
            'name',
            'content',
        ];
    }

    public function getForm() : DistributorTabForm
    {
        return new DistributorTabForm();
    }

    public function getModel()
    {
        return new DistributorTabModel();
    }

}