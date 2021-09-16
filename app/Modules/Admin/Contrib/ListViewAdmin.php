<?php

namespace Modules\Admin\Contrib;

use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\ManyToManyField;

abstract class ListViewAdmin extends Admin
{
    public $ownerPk = null;
    public string $owner_model_field;
    public $ownerField = null;
    public $ownerAdmin = null;
    public $manyToMany = false;
    public $ownerModel = null;
    public string $related_field;
    public string $through_field;
    public $instance;

    public function getInstance()
    {
        if ($this->instance) {
            return $this->instance;
        }

        return $this->getModel();
    }

    public function all($pk = null)
    {
        $this->ownerPk = $pk;
        parent::all();
    }

    public function create($pk = null)
    {
        $this->ownerPk = $pk;
        $new = true;
        $model = $this->newModel();
        $form = $this->getForm();
        $this->trySaveModel($model);
    }

    public function trySaveModel($model)
    {
        $form = $this->getForm();
        if ($this->ownerPk) {
            $model->{$this->ownerField} = $this->ownerPk;
        }
        $form->setInstance($model);
        $request = Xcart::app()->request;
        if ($request->getIsPost() && $form->populate($_POST, $_FILES)) {
            if ($form->isValid() && $form->save()) {
                $this->isManyToManyModel();
                if ($this->manyToMany) {
                    $this->ownerModel->{$this->related_field} = $form->getInstance()->pk;
                    $this->ownerModel->save();
                }
                if ($request->getIsAjax()) {
                    $this->jsonResponse(['status' => 'success', 'close' => true]);
                    return;
                } else {
                    Xcart::app()->flash->success('Changes have been successfully applied.');

                    $next = $_POST['save'] ?? 'save';
                    if ($next === 'save-stay') {
                        $request->redirect($this->getUpdateUrl($model->pk));
                    } else {
                        if (isset($_POST['popup'])) {
                            echo $this->render('admin/popup_close.tpl');
                            Xcart::app()->end();
                        }
                        if ($next === 'save') {
                            $request->redirect($this->getAllUrl());
                        }
                    }
                }
            } else {
                if (!$request->getIsAjax()) {
                    Xcart::app()->flash->error('Please, fix errors');
                }
            }
        }
        $breadName = ($this->ownerPk) ? $model : 'Adding a ' . strtolower($model);
        $this->setBreadcrumbs($breadName);
        $template = $this->createTemplate;
        $this->renderInternal($template, [
            'form' => $form,
            'model' => $model,
            'new' => true
        ]);
    }

    public function update($pk = null, $owner_id = null)
    {
        /** @var \Xcart\App\Orm\TreeModel $model */
        $new = false;
        $model = $this->getModelOr404($pk);
        $this->instance = $model;
        $form = $this->getUpdateForm();
        $this->ownerPk = $model->{$this->ownerField};
        if ($this->ownerPk) {
            $model->{$this->ownerField} = $this->ownerPk;
        }

        $form->setInstance($model);

        $request = Xcart::app()->request;
        if ($request->getIsPost() && $form->populate($_POST, $_FILES)) {
            if ($form->isValid() && $form->save()) {
                if ($request->getIsAjax()) {
                    $this->jsonResponse(['status' => 'success', 'close' => true]);
                    return;
                } else {
                    Xcart::app()->flash->success('Changes have been successfully applied.');

                    $next = $_POST['save'] ?? 'save';
                    if ($next === 'save-stay') {
                        $request->redirect($this->getUpdateUrl($model->pk));
                    } else {
                        if (isset($_POST['popup'])) {
                            echo $this->render('admin/popup_close.tpl');
                            Xcart::app()->end();
                        }
                        if ($next === 'save') {
                            $request->redirect($this->getAllUrl());
                        }
                    }
                }
            } else {
                if (!$request->getIsAjax()) {
                    Xcart::app()->flash->error('Please, fix errors');
                }
            }
        }
        $breadName = ($pk) ? $model : 'Adding a ' . strtolower($model);
        $this->setBreadcrumbs($breadName);
        $template = $new ? $this->createTemplate : $this->updateTemplate;
        $this->renderInternal($template, [
            'form' => $form,
            'model' => $model,
            'new' => $new
        ]);
    }

    public function isManyToManyModel(): void
    {
        if ($this->ownerModel) {
            $name_owner = $this->owner_model_field;
            $model = $this->ownerModel::objects()->get(['pk' => $this->ownerPk]);
            $owner_field = $model->getField($name_owner);
            if ($owner_field instanceof ManyToManyField) {
                /** @var ManyToManyField$ $owner */
                $owner_column_name = $owner_field->getModelColumn();
                $owner_model = new $owner_field->through();
                $owner_model->$owner_column_name = $this->ownerPk;
/*                if (!empty($owner_model->getField('order_by'))) {
                    $owner_model->order_by = $owner_field->through::objects()->filter([$owner_column_name => $this->ownerPk])->max('order_by') + 10;
                }*/
                $this->manyToMany = true;
                $this->ownerModel = $owner_model;
                $this->related_field = $owner_field->getRelatedModelColumn();
            }
        }
    }

    public function getCreateUrl(): string
    {
        return Xcart::app()->router->url('admin:create_owned', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'id' => $this->ownerPk,
            'test' => true,
        ]);
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }
}