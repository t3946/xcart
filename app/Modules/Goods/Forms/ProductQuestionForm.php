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
use Xcart\App\Form\ModelForm;

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
                    'placeholder' => '(609) 734-8000'
                ],
                'required' => true,
                'validators' => [
                    new PhoneValidator(),
                ],
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
}