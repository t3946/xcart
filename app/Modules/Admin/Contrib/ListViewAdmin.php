<?php

namespace Modules\Admin\Contrib;

use Xcart\App\Main\Xcart;

abstract class ListViewAdmin extends Admin
{
    public $ownerPk = null;
    public $ownerField = null;
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
        $this->update(null, $pk);
    }

    public function update($pk = null, $owner_id = null)
    {
        /** @var \Xcart\App\Orm\TreeModel $model */
        $new = false;
        if (is_null($pk)) {
            $new = true;
            $model = $this->newModel();
            $form = $this->getForm();
        }
        else {
            $model = $this->getModelOr404($pk);
            $this->instance = $model;
            $form = $this->getUpdateForm();
            $this->ownerPk = $model->{$this->ownerField};
        }


        if ($this->ownerPk) {
            $model->{$this->ownerField} = $this->ownerPk;
        }

//        if (isset($model->parent_id)) {
//            $this->parent_pk = $model->parent_id;
//        }

        $form->setInstance($model);

        $request = Xcart::app()->request;
        if ($request->getIsPost() && $form->populate($_POST, $_FILES)) {
            if ($form->isValid() && $form->save()) {
                if ($request->getIsAjax()) {
                    $this->jsonResponse(['state' => 'success']);
                }
                else {
                    Xcart::app()->flash->success('Изменения сохранены');

                    $next = isset($_POST['save']) ? $_POST['save']: 'save';
                    if ($next == 'save-stay') {
                        $request->redirect($this->getUpdateUrl($model->pk));
                    }
                    else {
                        if (isset($_POST['popup'])) {
                            echo $this->render('admin/popup_close.tpl');
                            Xcart::app()->end();
                        }
                        if ($next == 'save') {
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

        $this->setBreadcrumbs(($pk)? 'Edit' : 'Add');
        $template = $new ? $this->createTemplate : $this->updateTemplate;
        $this->renderInternal($template, [
            'form' => $form,
            'model' => $model,
            'new' => $new
        ]);
    }

    public function getCreateUrl()
    {
        return Xcart::app()->router->url('admin:create_owned', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'id' => $this->ownerPk,
        ]);
    }
}