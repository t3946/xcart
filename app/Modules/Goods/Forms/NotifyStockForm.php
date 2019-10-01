<?php

namespace Modules\Goods\Forms;

use Modules\Core\Forms\FrontendModelForm;
use Modules\Goods\GoodsModule;
use Modules\Goods\Models\NotifyStockModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\EmailField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class NotifyStockForm extends FrontendModelForm
{
    public $exclude  = ['site', 'sent', 'date'];

    public function getModel()
    {
        return new NotifyStockModel();
    }

    public function getFields()
    {
        return [

            'product' => [
                'class' => HiddenField::class,
            ],

            'first_name' => [
                'class' => CharField::class,
                'label' => GoodsModule::t('Your first name'),
                'html' => [
                    'placeholder' => GoodsModule::t('Albert')
                ],
                'required' => true,
            ],

            'email' => [
                'class' => EmailField::class,
                'label' => GoodsModule::t('Your email'),
                'html' => [
                    'placeholder' => GoodsModule::t('albert.einstein@gmail.com')
                ],
                'required' => true,
                'validators' => [
                    new EmailValidator(),
                ],
            ],

        ];
    }

    public function beforeInstanceSave($instance)
    {
        /** @var SiteModel $site_model */
        $site_model = Xcart::app()->getModule('Sites')->getSite();
        $instance->storefrontid = $site_model->storefrontid;
    }
}