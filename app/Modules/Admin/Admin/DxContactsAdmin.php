<?php


namespace Modules\Admin\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Forms\Dx\DistributorContactsForm;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Distributor\Models\DistributorContactsModel;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class DxContactsAdmin extends Admin
{
    public DistributorModel $dxModel;

    public string $allTemplate = 'admin/distributor/dx_3.tpl';
    public string $listItemActionsTemplate = 'admin/distributor/form/list/_item_actions.tpl';
    public ?string $sort = 'position';

    public function getForm() : DistributorContactsForm
    {
        return new DistributorContactsForm();
    }

    public function getModel()
    {
        return new DistributorContactsModel();
    }

    public function getQuerySet()
    {
        if ($this->dxModel) {
            return parent::getQuerySet()->filter(['manufacturerid' => $this->dxModel->manufacturerid]);
        }
        return parent::getQuerySet();
    }

    public function getListColumns() : array
    {
        return [
            'contact_name',
            'distributor_field_name',
            'email',
            'phone',
            'fax',
            'utility'
        ];
    }

    public function getAvailableListColumns()
    {
        return [
            'phone' => [
                'class' => 'nowrap',
            ],
            'fax' => [
                'class' => 'nowrap',
            ],
            'utility' => [
                'class' => 'nowrap',
            ],
        ];
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function renderInternal($view, $params)
    {
        $params = array_merge($params, [
            'distributorModel' => $this->dxModel ?? null,
            'section' => $this->section,
        ]);

        if (($this->dxModel ?? null) && empty($params['form'])) {
            $form = new DistributorForm();
            $form->setInstance($this->dxModel);
            $params['form'] = $form;
        }
        parent::renderInternal($view, $params);
    }

    public function getSortUrl()
    {
        return Xcart::app()->router->url('admin:dx_contact_sort', [
            'mid' => $this->dxModel->pk
        ]);
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'utility':
                return implode("", array_map(static fn($u) => "<div style='text-align: center; padding: 0 5px; margin-right: 2px; margin-top: 3px; background-color: #e4e4e4; border: 1px solid #aaa; border-radius: 4px;'>{$u}</div>",
                    $item->utility->all()));
            case 'phone':
                return $item->phone . ($item->ext ? " ext. " . $item->ext : '');
        }

        return parent::getItemProperty($item, $property);
    }

    public function getCreateUrl(): string
    {
        return Xcart::app()->router->url('admin:dx_contact_create', [
            'mid' => $this->dxModel->pk
        ]);
    }

    public function create($pk = null)
    {
        $new = true;
        $model = $this->newModel();
        $model->setAttribute('distributor', $this->dxModel);
        $form = $this->getForm();
        $this->model = $model;
        $form->setInstance($model);
        if ((string)$model) {
            $bread = $new ? sprintf("Adding a new %s", strtolower($model)) : (string)$model;
            $this->setBreadcrumbs($bread);
        }
        $request = Xcart::app()->request;
        if ($request->getIsGet()) {
            $form->populate($_GET, $_FILES);
        }
        if ($request->getIsPost() && $form->populate($_POST, $_FILES)) {
            if ($form->isValid() && $form->save()) {
                if ($request->getIsAjax()) {
                    $this->jsonResponse(['status' => 'success', 'close' => true]);
                    return;
                }
                Xcart::app()->flash->success('Changes have been successfully applied.');

                $this->redirectAfterSave($model, $_POST['save'] ?? 'save');

            } elseif (!$request->getIsAjax()) {
                Xcart::app()->flash->error('Please, fix errors');
            }
        }
        $template = $new ? $this->createTemplate : $this->updateTemplate;
        $this->renderInternal($template, [
            'form' => $form,
            'model' => $model,
            'new' => $new
        ]);
    }

    public function getListItemActions()
    {
        return array_merge( ['call'], parent::getListItemActions());
    }


}