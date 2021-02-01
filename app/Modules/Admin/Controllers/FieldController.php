<?php


namespace Modules\Admin\Controllers;


use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;

class FieldController extends BackendController
{
    public function field_reload(): void
    {
        $post = Xcart::app()->request->post;
        if (($form_class = $post->get('form_class'))
            && $depend_field = $post->get('depend_field')) {
            /** @var ModelForm $form */
            $form = new $form_class();
            $form->populate(Xcart::app()->request->post->all());
            $model = $form->getModel();
            $model->setIsNewRecord(false);
            $model->setAttributes($form->getAttributes());
            $form_render = new $form_class();
            $form_render->setInstance($model);

            $this->jsonResponse([$depend_field => $form_render->getField($depend_field)->render()]);
        }
    }
}