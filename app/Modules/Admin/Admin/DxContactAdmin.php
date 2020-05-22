<?php


namespace Modules\Admin\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Forms\Dx\DistributorContactForm;
use Modules\Core\Models\LanguageModel;
use Modules\Distributor\Models\DistributorContactsModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class DxContactAdmin extends Admin
{
    public $dxModel;
    public $section;
    public $distributor_sections;
    public $editable = true;

    public $allTemplate = 'admin/distributor/dx_3.tpl';
    public $listRowTemplate =  'admin/distributor/form/list/_tr.tpl';
    public $listItemActionsTemplate = 'admin/distributor/form/list/_item_actions.tpl';
    public $columnDefaultTemplate = 'admin/distributor/form/list/columns/default.tpl';
    public $createTemplate = 'admin/distributor/form/create.tpl';
    public $updateTemplate = 'admin/distributor/form/update.tpl';

    public $sort = 'position';

    public function getModel()
    {
        return new DistributorContactsModel();
    }

    public function getForm()
    {
        return new DistributorContactForm();
    }

    public function getListItemActions()
    {
        if ($this->editable) {
            return [
                'view',
                'remove'
            ];
        }
        return [];
    }

    public function getQuerySet()
    {
        if ($this->dxModel) {
            return parent::getQuerySet()->filter(['manufacturerid' => $this->dxModel->manufacturerid]);
        }
        return parent::getQuerySet();
    }

    public function renderInternal($view, $params)
    {
        parent::renderInternal($view, array_merge($params, [
            'distributorModel' => $this->dxModel,
            'section' => $this->section,
            'distributor_sections' => $this->distributor_sections,
        ]));
    }

    public function getListColumns()
    {
        $columns = [];
        if ($this->editable) {
            $columns[] = 'pq';
        }
        return array_merge($columns, ['contact_name', 'email', 'phone', 'ext']);
    }

    public function getAvailableListColumns()
    {
        return [
            'distributor_field_name' => [
                'title' => 'Position',
                'template' => $this->columnDefaultTemplate,
            ],
            'pq' => [
                'title' => 'PQ',
                'template' => 'admin/distributor/form/list/columns/boolean.tpl',
                'hint' => LanguageModel::translate('help_dx_pq_title') ?? 'help_dx_pq_title',
            ],
            'contact_name' => [
                'title' => 'Contact name',
                'title_inline' => true,
                'template' => $this->columnDefaultTemplate,
                'extend' => 'distributor_field_name'
            ],
            'email' => [
                'title' => 'Email',
                'template' => $this->columnDefaultTemplate,
                'title_inline' => true,
            ],
            'phone' => [
                'title' => 'Phone',
                'template' => $this->columnDefaultTemplate,
                'hint' => LanguageModel::translate('help_dx_phone_title') ?? 'help_dx_phone_title',
                'title_inline' => true,
                'extend' => 'fax'
            ],
            'ext' => [
                'title' => 'Ext',
                'template' => $this->columnDefaultTemplate,
                'title_inline' => true,
            ],
            'fax' => [
                'title' => 'Fax',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getAllUrl()
    {
        if (!$this->model) {
            return parent::getAllUrl();
        }
        return Xcart::app()->router->url('admin:section', [
            'mid' => $this->model->distributor->manufacturerid,
            'section' => 3,
        ]);
    }

    public function getItemEditProperty(Model $item, $property)
    {
        $form = $this->getForm();
        $form->setInstance($item);
        $field = $form->getField($property);
        return $field->renderInput();
    }

    public function getUpdateAllUrl($pk = null)
    {
        $query = [];
        return Xcart::app()->router->url('admin:updateall', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
        ], $query);
    }

    public function getCreateUrl()
    {
        return Xcart::app()->router->url('admin:create_nested', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'id' => $this->dxModel->manufacturerid,
        ]);
    }

    public function create($pk = null)
    {
       if ($last = $this->getModel()::objects()->limit(1)->order(['-position'])->get(['manufacturerid' => $pk])) {
           $position = $last->position;
       }
       $model = $this->getModel();
       $model->manufacturerid = $pk;
       $model->position = $position ?? 0;
       $model->save();
       $this->model = $model;
       Xcart::app()->request->redirect($this->getAllUrl());
    }

    public function updateall()
    {
        $form = $this->getForm();
        if (Xcart::app()->request->getIsPost()) {
            $values =Xcart::app()->request->post->get($form::classNameShort());
            $forms = [];
            foreach ($form->getFields() as $f => $field) {
                if (is_array($values[$f])) {
                    foreach ($values[$f] as $id => $value) {
                        $forms[$id] = array_merge($forms[$id] ?? [], [$f => $value]);
                    }
                } else {
                    $forms[$values[$f]] = array_merge($forms[$values[$f]] ?? [], [$f => true]);
                }
            }
            foreach ($forms as $id => $fData) {
                /** @var DistributorContactForm $vForm */
                $dc = $this->getModelOr404($id);
                $this->model = $dc;
                $vForm = $this->getForm();
                $vForm->setInstance($dc);
                $fData['pq'] = $fData['pq'] ?? false;
                $vForm->populate([$vForm::classNameShort() => $fData]);
                if ($vForm->isValid()) {
                    $vForm->save();
                }
            }
            Xcart::app()->request->redirect($this->getAllUrl());
        }
    }
}