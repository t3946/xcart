<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Sites\Models\CurrencyModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;

class DistributorPriceForm extends DistributorForm
{

    public function getFields()
    {
        $choices = [];

        foreach (CurrencyModel::objects() as $curr) {
            $choices[$curr->currency_id] = $curr->currency_code;
        }

        return [
            'd_product_catalog' => [
                'class' => CharField::class,
                'label' => 'Product catalog URL',
                'hint' => 'Hint',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_price_list' => [
                'class' => CharField::class,
                'label' => 'Price-list URL',
                'hint' => 'Hint',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_currency' => [
                'class' => DropDownField::class,
                'label' => 'Distributor currency',
                'hint' => 'Hint',
                'choices' => $choices,
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'cost_to_us_coef_x' => [
                'class' => CharField::class,
                'label' => 'Cost to us =',
                'hint' => 'Hint',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => [
                    'size' => 9,
                    'style' => 'width: 75px;',
                ],
            ],
            'price_label' => [
                'class' => CharField::class,
                'label' => 'Price =',
                'hint' => 'Hint',
                'html' => [
                    'style' => 'border: none;'
                ],
                'value' => 'calculated by our algorithm',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_map_policy' => [
                'class' => DropDownField::class,
                'label' => 'MAP policy',
                'hint' => 'Hint',
                'choices' => [
                    '' => 'N/A',
                    'applies_to_selected_products' => 'applies to selected products',
                    'applies_to_all_products' => 'applies to all products',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'new_map_price_coef_x' => [
                'class' => CharField::class,
                'label' => 'MAP price =',
                'hint' => 'Hint',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => [
                    'size' => 9,
                    'style' => 'width: 75px;',
                ],
            ],
            'supplier_products_price_multiplier' => [
                'class' => CharField::class,
                'label' => 'Distributor product price multiplier',
                'hint' => 'Hint',
                'html' => [
                    'size' => 9,
                    'style' => 'width: 75px;',
                ],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],

        ];
    }
}