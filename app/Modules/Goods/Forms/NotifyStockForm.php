<?php

namespace Modules\Goods\Forms;

use Modules\Goods\Models\NotifyStockModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\HiddenField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class NotifyStockForm extends ModelForm
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
                'label' => 'Your first name',
                'html' => [
                    'placeholder' => 'Albert'
                ],
                'required' => true,
            ],

            'email' => [
                'class' => CharField::class,
                'label' => 'Your email',
                'html' => [
                    'placeholder' => 'albert.einstein@gmail.com'
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