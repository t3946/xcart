<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 27.06.2018
 * Time: 16:00
 */

namespace Modules\Goods\Forms;

use Modules\Goods\Models\ProductQuestionModel;
use Modules\Order\Validation\PhoneValidator;
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

    public function afterOwnerSave($owner)
    {
        parent::afterOwnerSave($owner);

        $config = GlobalConfig::getInstance();

        $product_question_message_body_to_brand = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_brand"]));
        $product_question_message_body_to_customer = func_eol2br(stripslashes($config["product_question_email"]["product_question_message_body_to_customer"]));

        $product_info = func_select_product($productid, @$user_account['membershipid']);

        $customer_email = $email;
        $brandid = $product_info['brandid'];
        $brand_email = func_query_first_cell("SELECT customer_service_email FROM $sql_tbl[brands] WHERE brandid='$brandid'");
        $distributor_email = func_query_first_cell("SELECT d_product_questions_send_to_email FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");
        $customer_service_phone = func_query_first_cell("SELECT customer_service_phone FROM $sql_tbl[brands] WHERE brandid='$brandid'");
        $product_question_departments_email = func_query_first_cell("SELECT email FROM $sql_tbl[departments] WHERE name='Product question'");

        $product_link = "http://".$storefront_domain."/".$product_info["clean_url"]."/";

        // $product_question_message_body_to_brand = Message body to us
        $product_question_message_body_to_brand = str_replace(['{{mpn}}','{{supplier_internal_id}}'], [$product_info["mpn"],$product_info["supplier_internal_id"]], $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{productname}}", $product_info["product"], $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{brand_email}}", $brand_email, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{brand_phone}}", $customer_service_phone, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{question}}", $question, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{customer_phone}}", $phone, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{product_link}}", $product_link, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{customer_email}}", $customer_email, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{prqnid}}", $prefix_product_question_id, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{signature}}", $signature, $product_question_message_body_to_brand);
        $product_question_message_body_to_brand = str_replace("{{customer_name}}", $name, $product_question_message_body_to_brand);

        $product_question_message_body_to_customer = str_replace(['{{mpn}}','{{supplier_internal_id}}'], [$product_info["mpn"],$product_info["supplier_internal_id"]], $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{productname}}", $product_info["product"], $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{brand_email}}", $brand_email, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{brand_phone}}", $customer_service_phone, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{question}}", $question, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{customer_phone}}", $phone, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{product_link}}", $product_link, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{customer_email}}", $customer_email, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{prqnid}}", $prefix_product_question_id, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{signature}}", $signature, $product_question_message_body_to_customer);
        $product_question_message_body_to_customer = str_replace("{{customer_name}}", $name, $product_question_message_body_to_customer);


        $from = $product_question_departments_email;
        $to = $customer_email;
        $subject = $product_question_subject_line;
        $body = $product_question_message_body_to_customer;
        $mail_smarty->assign('subject', $subject);
        $mail_smarty->assign('body', $body);
        func_send_mail($to, "mail/product_question_email_subj.tpl", "mail/product_question_email.tpl", $from, false, false, false, false,'','N',false,false);


        $body = $product_question_message_body_to_brand; // Message body to us
        $to = $config["product_question_email"]["product_question_bc_email"];
        $from = $customer_email;
        $mail_smarty->assign("subject", $subject);
        $mail_smarty->assign("body", $body);
        func_send_mail($to, 'mail/admin_product_question_subj.tpl', 'mail/admin_product_question.tpl', $from, true, false, false, false,'','N',false,false);

//        Xcart::app()->mail->template(
//            'team@s3stores.com',
//            'Test sending email',
//            'mail/log_template.tpl',
//            ['message' => "Email test: PASS"]
//        );
    }
}