<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 27.06.2018
 * Time: 16:00
 */

namespace Modules\Goods\Forms;

use Modules\Core\Components\GlobalConfig;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\ProductQuestionModel;
use Modules\Main\Models\DepartmentsModel;
use Modules\Order\Validation\PhoneValidator;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\TextField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class ProductQuestionForm extends ModelForm
{

    /**
     * @return ProductQuestionModel
     * @throws \Exception
     */
    public function getModel()
    {
        return new ProductQuestionModel();
    }

    public function getFields()
    {
        return [
            'productid' => [
                'class' => NumberField::class,
                'required' => true,
            ],
            'name' => [
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
            'phone' => [
                'class' => CharField::class,
                'label' => 'Your phone',
                'html' => [
                    'placeholder' => '(609) 734-8000',
                    'class' => 'phone'
                ],
                'required' => true,
                'validators' => [
                    new PhoneValidator(),
                ],
            ],
            'phone_ext' => [
                'class' => NumberField::class,
                'label' => 'ext',
                'html' => [
                    'class' => 'phone_ext',
                ]
            ],
            'question' => [
                'class' => TextField::class,
                'label' => 'Product question',
                'hint' => 'Please don\'t mention your email and your phone in this field.',
                'html' => [
                    'placeholder' => 'Please type your product question here'
                ],
                'required' => true,
            ],

        ];
    }


    /**
     * @param $instance
     */
    public function afterInstanceSave($instance): void
    {
        parent::afterInstanceSave($instance);

        $config = GlobalConfig::getInstance()->getAllData();

        $this->sendMessageToSupplier($instance, $config);
        $this->sendMessageToCustomer($instance, $config);
    }

    /**
     * @param $storefront
     * @param $config
     * @return mixed
     */
    private function getSignature($storefront, $config): string
    {

        $params['storefrontid'] = $storefront->storefrontid;
        $phones = GeoipHelper::getPhones($params);

        $search = [
            "{{storefront-url}}" => "https://" . $storefront->domain,
            "{{customer_service_local_phone_number}}" => $phones
        ];

        $signature = str_replace(array_keys($search), array_values($search), $config["Company"]["signature"]);

        return $signature;
    }

    /**
     * @param $instance
     * @param $config
     */
    private function sendMessageToSupplier($instance, $config): void
    {
        Xcart::app()->mail->template(
            $config["product_question_bc_email"],
            // create subject
            $this->applyInfoToText($instance, $config, $config["product_question_subject_line"]),
            'mail/blank.tpl',
            [
                // create message
                'content' => $this->applyInfoToText($instance, $config, $config["product_question_message_body_to_brand"])
            ],
            [
                'from' => $instance->email
            ]
        );
    }

    /**
     * @param $instance
     * @param $config
     */
    private function sendMessageToCustomer($instance, $config): void
    {
        Xcart::app()->mail->template(
            $instance->email,
            // create subject
            $this->applyInfoToText($instance, $config, $config["product_question_subject_line"]),
            'mail/blank.tpl',
            [
                // create message
                'content' => $this->applyInfoToText($instance, $config, $config["product_question_message_body_to_customer"])
            ],
            [
                'from' => DepartmentsModel::objects()->getModel()->getDepartmentByName('Product question')->email
            ]
        );
    }

    /**
     * @param $instance
     * @param $config
     * @param $text
     * @return mixed
     */
    private function applyInfoToText($instance, $config, $text): string
    {
        $product = $instance->product;
        $brand = $product->brand;

        $text = str_replace(['{{mpn}}', '{{supplier_internal_id}}'], [
            $product->getMPN(),
            $product->supplier_internal_id
        ], $text);
        $text = str_replace("{{productname}}", $product->product, $text);
        $text = str_replace("{{brand_email}}", $brand->customer_service_email, $text);
        $text = str_replace("{{brand_phone}}", $brand->customer_service_phone, $text);
        $text = str_replace("{{question}}", $instance->question, $text);
        $text = str_replace("{{customer_phone}}", $instance->createPhone(), $text);
        $text = str_replace("{{product_link}}", $product->getAbsoluteUrl(true), $text);
        $text = str_replace("{{customer_email}}", $instance->email, $text);
        $text = str_replace("{{prqnid}}", $this->prefixProductQuestionId($instance->id), $text);
        $text = str_replace("{{signature}}", $this->getSignature($product->sites->limit(1)->get(), $config), $text);
        $text = str_replace("{{customer_name}}", $instance->name, $text);

        //var_dump($message);exit();

        return $text;
    }

    /**
     * @param $id
     * @return string
     */
    private function prefixProductQuestionId($id): string
    {
        return "PRQN-" . sprintf('%1$05d', $id);
    }


}