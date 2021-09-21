<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Admin\ProductOptionsAdmin;
use Modules\Goods\Admin\ProductOptionVariantsAdmin;
use Modules\Goods\Forms\ProductImageForm;
use Modules\Goods\Models\ImageProductModel;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductImagesModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class ProductImagesAdmin extends ListViewAdmin
{
    public $ownerModel = ProductModel::class;
    public string $owner_model_field = 'detail_images';
    public $ownerField = 'image_id';
    public string $related_field = 'image_id';
    public string $through_field = 'product_id';
    public bool $autoFixSort = true;
    public ?string $sort = 'products_images__order_by';

    public function getSuggestionColumns()
    {
        return [
            'products_images__is_active' => [
                'class' => Select2Field::class,
            ],
        ];
    }

    public function getAvailableListColumns()
    {
        return [
            'products_images__is_active' => [
                'title' => 'Is active',
            ],
        ];
    }

    public function getListColumns()
    {
        return [
            'image',
            'hash',
            'width',
            'height',
            'products_images__is_active',
        ];
    }

    public function getModel()
    {
        return new ProductImageModel();
    }

    public function getForm()
    {
        $form = new ProductImageForm();
        $form->admin = $this;
        return $form;
    }

    public function trySaveModel($model)
    {
        $form = $this->getForm();
        $this->isManyToManyModel();
        if ($this->ownerPk && !$this->manyToMany) {
            $model->{$this->ownerField} = $this->ownerPk;
        }
        $form->setInstance($model);
        $request = Xcart::app()->request;
        if ($request->getIsPost() && $form->populate($_POST, $_FILES)) {
            /** @var ProductImageModel $arImage */
            $imageModel = ProductImageModel::objects()->get(['hash' => md5(file_get_contents($_FILES[$form::classNameShort()]['tmp_name']['path']))]);
            if ($imageModel) {
                $this->ownerModel->{$this->related_field} = $imageModel->pk;
            } else {
                /** @var ProductModel $product_model */
                $product_model = ProductModel::objects()->get(['pk' => $this->ownerPk]);
                $model->path->uploadTo = "images/{$product_model->distributor->code}";
                if ($form->isValid() && $form->save()) {
                    $this->ownerModel->{$this->related_field} = $form->getInstance()->pk;
                }
            }
            if ($this->ownerModel->save()) {
                Xcart::app()->queue->send('images_action', json_encode([], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), true);
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
            }
        } else {
            if (!$request->getIsAjax()) {
                Xcart::app()->flash->error('Please, fix errors');
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
        if ($_POST[$this->getForm()::classNameShort()]['path'] && empty($_FILES)) {
            $model = $this->getModelOr404($pk);
            if ($model->delete()) {
                $this->jsonResponse(['status' => 'success', 'close' => true]);
                return;
            }
        }
        return parent::update($pk, $owner_id);
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'image':
                return "<div style='text-align: center'><img src=\"{$item->getCdnURL('preview')}\" title=\"{$item}\" width='60' /></div>";
        }
        return parent::getItemProperty($item, $property);
    }
}