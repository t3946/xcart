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
use Modules\Main\Validation\ProductOrOrderValidator;
use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharCleanField;
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
                'class' => CharCleanField::class,
                'required' => true,
                'label' => MainModule::t('Full name'),
                'hint' => MainModule::t('Your first and last name'),
                'html' => [
                    'class' => 'contact-form-input',
                    'placeholder' => MainModule::t('Albert H. Einstein'),
                ],

            ],
            'company_name' => [
                'class' => CharCleanField::class,
                'label' => MainModule::t('Your company name'),
                'html' => [
                    'class' => 'contact-form-input',
                    'placeholder' => MainModule::t('Eureka Inc.'),
                ],
            ],
            'zip_postal_code' => [
                'class' => NumberField::class,
                'label' => MainModule::t('Your zip/postal code'),
                'html' => [
                    'class' => 'contact-form-input',
                ]
            ],
            'phone_number' => [
                'class' => CharCleanField::class,
                'label' => MainModule::t('Your phone number'),
                'hint' => MainModule::t('Phone number you can be reached at'),
                'type' => 'tel',
                'html' => [
                    'class' => 'contact-form-input',
                    'placeholder' => MainModule::t('(609) 734-8000'),
                ],
            ],
            'email' => [
                'class' => CharCleanField::class,
                'label' => MainModule::t('Your email address'),
                'hint' => MainModule::t('Valid email address is a must'),
                'type' => 'email',
                'required' => true,

                'validators' => [
                    new EmailValidator()
                ],
                'html' => [
                    'class' => 'contact-form-input',
                    'placeholder' => MainModule::t('albert.einstein@gmail.com'),
                ],
            ],
            'department' => [
                'class' => DropDownField::class,
                'label' => MainModule::t('Department'),
                'choices' => $this->getDepartments(),
                'hint' => MainModule::t('Your message will be routed to this department'),
                'required' => true,
                'html' => [
                    'class' => 'contact-form-input',
                ],
            ],
            'product_sku' => [
                'class' => CharCleanField::class,
                'required' => true,
                'label' => MainModule::t('Product SKU or your order #'),
                'hint' => MainModule::t('SKU of product you are interested in or your order #'),
                'html' => [
                    'class' => 'contact-form-input',
                    'placeholder' => MainModule::t('EDR-T-A63127 or AR-54321'),
                ],
                'validators' => [
                    new ProductOrOrderValidator()
                ],
            ],
            'subject_line' => [
                'class' => CharCleanField::class,
                'label' => MainModule::t('Subject line'),
                'required' => true,
                'html' => [
                    'class' => 'contact-form-input',
                    'placeholder' => MainModule::t('Is gravitation responsible for people falling in love?'),
                ],
                'className' => 'wide'
            ],
            'q_messsage' => [
                'class' => TextField::class,
                'label' => MainModule::t('Your message'),
                'required' => true,
                'className' => 'wide',
                'html' => [
                    'class' => 'contact-form-input',
                ],
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
        $departments = [
            '' => '',
            'Product questions' => MainModule::t('Product questions'),
            'Shipping quote' => MainModule::t('Shipping quote'),
            'Order status' => MainModule::t('Order status'),
            'Tracking number request' => MainModule::t('Tracking number request'),
            'Product replacement request' => MainModule::t('Product replacement request'),
            'Product return request' => MainModule::t('Product return request'),
            'W-9 from request' => MainModule::t('W-9 from request'),
            'Other requests ' => MainModule::t('Other requests '),
        ];
        $site = Xcart::app()->getModule('Sites')->getSite();
        if (in_array($site->country, ['RU'])) {
            unset($departments['W-9 from request']);
        }
        return $departments;
    }

}