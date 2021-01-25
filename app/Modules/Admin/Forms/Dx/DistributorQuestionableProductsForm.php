<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\Fields\CheckboxField;

class DistributorQuestionableProductsForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];
    public $fieldTemplate = 'admin/distributor/form/checkbox_field.tpl';

    public function getFieldsets()
    {
        return [
            'Dx offers the following products <b>prohibited by PayPal</b>' => [
                'd_questionable_1',
                'd_questionable_2',
                'd_questionable_3',
                'd_questionable_4',
                'd_questionable_5',
                'd_questionable_6',
                'd_questionable_7',
                'd_questionable_8',
                'd_questionable_9',
            ],
            'Dx offers the following products requiring <b>approval by PayPal</b>' => [
                'd_questionable_10',
                'd_questionable_11',
                'd_questionable_12',
                'd_questionable_13',
            ]
        ];
    }

    public function getFields()
    {
        return [
            'd_questionable_1' => [
                'class' => CheckboxField::class,
                'label' => '<b>narcotics, steroids,</b> certain controlled substances or other products that present a risk to consumer safety',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_2' => [
                'class' => CheckboxField::class,
                'label' => '<b>drug paraphernalia</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_3' => [
                'class' => CheckboxField::class,
                'label' => '<b>cigarettes</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_4' => [
                'class' => CheckboxField::class,
                'label' => 'the promotion of <b>hate, violence, racial or other forms of intolerance that is discriminatory</b> or the financial exploitation of a crime',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_5' => [
                'class' => CheckboxField::class,
                'label' => 'items that are considered <b>obscene</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_6' => [
                'class' => CheckboxField::class,
                'label' => 'items that <b>infringe or violate any copyright, trademark,</b> right of publicity or privacy or any other proprietary right under the laws of any jurisdiction',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_7' => [
                'class' => CheckboxField::class,
                'label' => '<b>certain sexually oriented materials</b> or services',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_8' => [
                'class' => CheckboxField::class,
                'label' => '<b>ammunition, firearms, or certain firearm parts or accessories</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_9' => [
                'class' => CheckboxField::class,
                'label' => '<b>certain weapons or knives</b> regulated under applicable law',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_10' => [
                'class' => CheckboxField::class,
                'label' => '<b>jewels, precious metals and stones</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_11' => [
                'class' => CheckboxField::class,
                'label' => '<b>alcoholic beverages</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_12' => [
                'class' => CheckboxField::class,
                'label' => '<b>non-cigarette tobacco products, e-cigarettes</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
            'd_questionable_13' => [
                'class' => CheckboxField::class,
                'label' => '<b>prescription drugs/devices</b>',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:50px;'],
            ],
        ];
    }
}