<?php
namespace Modules\Landing\Forms;

use Modules\Landing\LandingModule;
use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class OrderForm extends BaseForm
{
    public $sendTo = 'Michael@s3stores.com';

    public function getFields()
    {
        return [
            'name' => [
                'class' => CharField::className(),
                'required' => true,
                'label' => LandingModule::t('Name'),
                'html' => [
                    'placeholder' => LandingModule::t('Name'),
                ]
            ],
            'phone' => [
                'class' => CharField::className(),
                'label' => LandingModule::t('Phone'),
                'type' => 'tel',
                'required' => true,
                'html' => [
                    'placeholder' => 'Phone',
                ],
            ],
            'email' => [
                'class' => CharField::className(),
                'label' => LandingModule::t('Your email address'),
                'type' => 'email',
                'required' => true,

                'validators' => [
                    new EmailValidator()
                ],
                'html' => [
                    'placeholder' => 'E-Mail',
                ],
            ]
        ];
    }

    public function send()
    {
        return (bool)Xcart::app()->mail->template(
            $this->sendTo,
            'Order your WunderWaffle',
            'mail/form_auto.tpl',
            ['form' => $this]
        );
    }
}