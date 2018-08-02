<?php
/**
 * Форма Contact Us ('Свяжитесь с нами')
 *
 * Created by PhpStorm.
 * User: anna
 * Date: 26.04.2018
 * Time: 15:54
 *
 * @param $sendTo string email, куда отправить письмо
 * @param $fields array Список полей формы с параметрами
 * @param $departments array Список причин запроса
 */

namespace Modules\Main\Forms;


use Modules\Core\Forms\FrontendForm;
use Modules\Main\MainModule;
use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\TextField;
use Xcart\App\Form\Form;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;


class ContactUsForm extends FrontendForm
{
    /**
     * @var string email, куда отправить письмо
     */
    public $sendTo = 'helpdesk@s3stores.com';

    /**
     * Список полей формы
     * @return array Поля формы
     */
    public function getFields() : array
    {
        return [

            'full_name' => [
                'class' => CharField::class,
                'required' => true,
                'label' => MainModule::t('Full name'),
                'hint' => MainModule::t('Your first and last name'),
                'html' => [
                    'placeholder' => MainModule::t('Albert H. Einstein'),
                ],

            ],
            'company_name' => [
                'class' => CharField::class,
                'label' => MainModule::t('Your company name'),
                'html' => [
                    'placeholder' => MainModule::t('Eureka Inc.'),
                ],
            ],
            'zip_postal_code' => [
                'class' => NumberField::class,
                'label' => MainModule::t('Your zip/postal code'),
            ],
            'phone_number' => [
                'class' => CharField::class,
                'label' => MainModule::t('Your phone number'),
                'hint' => MainModule::t('Phone number you can be reached at'),
                'type' => 'tel',
                'html' => [
                    'placeholder' => '(609) 734-8000',
                ],
            ],
            'email' => [
                'class' => CharField::class,
                'label' => MainModule::t('Your email address'),
                'hint' => MainModule::t('Valid email address is a must'),
                'type' => 'email',
                'required' => true,

                'validators' => [
                    new EmailValidator()
                ],
                'html' => [
                    'placeholder' => 'albert.einstein@gmail.com',
                ],
            ],
            'department' => [
                'class' => DropDownField::class,
                'label' => MainModule::t('Department'),
                'choices' => $this->getDepartments(),
                'hint' => MainModule::t('Your message will be routed to this department'),
                'required' => true,
            ],
            'product_sku' => [
                'class' => CharField::class,
                'required' => true,
                'label' => MainModule::t('Product SKU or your order #'),
                'hint' => MainModule::t('SKU of product you are interested in or your order #'),
                'html' => [
                    'placeholder' => MainModule::t('EDR-T-A63127 or AR-54321'),
                ],
            ],
            'subject_line' => [
                'class' => CharField::class,
                'label' => MainModule::t('Subject line'),
                'required' => true,
                'html' => [
                    'placeholder' => 'Is gravitation responsible for people falling in love?',
                ],
                'className' => 'wide'
            ],
            'q_messsage' => [
                'class' => TextField::class,
                'label' => MainModule::t('Your message'),
                'required' => true,
                'className' => 'wide'
            ],
        ];
    }

    /**
     * Отправка сообщения пользователю
     * @return bool Сообщение отправлено успешно
     */
    public function send(): bool
    {
        return (bool)Xcart::app()->mail->template(
            $this->sendTo,
            $this->getField('subject_line')->getValue(),
            'mail/form_auto.tpl',
            ['form' => $this]
        );
    }

    /**
     * Отправка сообщения от пользователя если форма прошла валидацию
     * @param $owner
     * @param $isValid bool Валидация пройдена успешно
     */
    public function afterValidate($owner, $isValid)
    {
        if ($isValid) {
            $this->send();
        }
    }

    /**
     * Функция список причин обращения пользователя
     * @return array Массив с причинами обращения
     */
    private function getDepartments(): array
    {
        return [
            '' => '',
            'Product questions' => 'Product questions',
            'Shipping quote' => 'Shipping quote',
            'Order status' => 'Order status',
            'Tracking number request' => 'Tracking number request',
            'Product replacement request' => 'Product replacement request',
            'Product return request' => 'Product return request',
            'W-9 from request' => 'W-9 from request',
            'Other requests ' => 'Other requests ',
        ];
    }

}