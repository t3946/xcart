<?php


namespace Modules\Admin\Controllers;


use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;

class FieldController extends BackendController
{
    public function field_reload(): void
    {
        if ($form_class = Xcart::app()->request->post->get('form_class')) {
            /** @var ModelForm $form */
            $form = new $form_class();
            $form->populate(Xcart::app()->request->post->all());
            $model = $form->getModel();
            $model->setIsNewRecord(false);
            $model->setAttributes($form->getAttributes());
            $form_render = new $form_class();
            $form_render->setInstance($model);
            $this->jsonResponse(['html' => $form_render->render()]);
        }
    }
}