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

    //$brandid = $product_info->brandid;
    // $storefront_domain = $storefront->domain;
    //$distributor = $product_info->distributor;
    //$distributor_email = $distributor->d_product_questions_send_to_email;
    //$productid = $this->productid;

    private function prefixProductQuestionId($id)
    {
        return "PRQN-".sprintf('%1$05d', $id);
    }


    public function afterInstanceSave($instance)
    {
        //parent::afterOwnerSave(afterInstanceSave);

        $config = GlobalConfig::getInstance();

        //----

        $messageToSupplier = $config["product_question_email"]["product_question_message_body_to_brand"];
        $messageToSupplier = str_replace(['{{mpn}}','{{supplier_internal_id}}'], [
            $instance->product->mpn,
            $instance->product->supplier_internal_id
        ], $messageToSupplier);
        $messageToSupplier = str_replace("{{productname}}", $instance->product->product, $messageToSupplier);
        $messageToSupplier = str_replace("{{brand_email}}", $instance->product->brand->customer_service_email, $messageToSupplier);
        $messageToSupplier = str_replace("{{brand_phone}}", $instance->product->brand->customer_service_phone, $messageToSupplier);
        $messageToSupplier = str_replace("{{question}}", $this->question, $messageToSupplier);
        $messageToSupplier = str_replace("{{customer_phone}}", $this->phone . ' x ' . $this->phone_ext, $messageToSupplier);
        $messageToSupplier = str_replace("{{product_link}}", $instance->product->getAbsoluteUrl(true), $messageToSupplier);
        $messageToSupplier = str_replace("{{customer_email}}", $this->email, $messageToSupplier);
        $messageToSupplier = str_replace("{{prqnid}}", $this->prefixProductQuestionId($instance->product->id), $messageToSupplier);
        $messageToSupplier = str_replace("{{signature}}", $this->getSignature($instance->product->sites->limit(1)->get(), $config), $messageToSupplier);
        $messageToSupplier = str_replace("{{customer_name}}", $this->name, $messageToSupplier);


        Xcart::app()->mail->template(
            $config["product_question_email"]["product_question_bc_email"],
            $config["product_question_email"]["product_question_subject_line"],
            'mail/base_template.tpl',
            [
                'message' => $messageToSupplier,
                'from' => $this->email
            ]
        );

        // -------

        $messageToCustomer = $config["product_question_email"]["product_question_message_body_to_customer"];
        $messageToCustomer = str_replace(['{{mpn}}','{{supplier_internal_id}}'], [
            $instance->product->mpn,
            $instance->product->supplier_internal_id
        ], $messageToCustomer);
        $messageToCustomer = str_replace("{{productname}}", $instance->product->product, $messageToCustomer);
        $messageToCustomer = str_replace("{{brand_email}}", $instance->product->brand->customer_service_email, $messageToCustomer);
        $messageToCustomer = str_replace("{{brand_phone}}", $instance->product->brand->customer_service_phone, $messageToCustomer);
        $messageToCustomer = str_replace("{{question}}", $this->question, $messageToCustomer);
        $messageToCustomer = str_replace("{{customer_phone}}", $this->phone . ' x ' . $this->phone_ext, $messageToCustomer);
        $messageToCustomer = str_replace("{{product_link}}", $instance->product->getAbsoluteUrl(true), $messageToCustomer);
        $messageToCustomer = str_replace("{{customer_email}}", $this->email, $messageToCustomer);
        $messageToCustomer = str_replace("{{prqnid}}", $this->prefixProductQuestionId($instance->product->id), $messageToCustomer);
        $messageToCustomer = str_replace("{{signature}}", $this->getSignature($instance->product->sites->limit(1)->get(), $config), $messageToCustomer);
        $messageToCustomer = str_replace("{{customer_name}}", $this->name, $messageToCustomer);



        Xcart::app()->mail->template(
            $this->email,
            $config["product_question_email"]["product_question_subject_line"],
            'mail/base_template.tpl',
            [
                'message' => $messageToCustomer,
                'from' => DepartmentsModel::objects()->getModel()->getDepartmentByName('Product question')->email
            ]
        );
        // ---


    }

    private function getSignature($storefront, $config)
    {

        $params['storefrontid'] =  $storefront->storefrontid;
        $phones = GeoipHelper::getPhones($params);

        $search = [
            "{{storefront-url}}" => "https://" . $storefront->domain,
            "{{customer_service_local_phone_number}}" => $phones
        ];

        $signature = str_replace (array_keys($search), array_values($search), $config["Company"]["signature"]);

        return $signature;
    }
}