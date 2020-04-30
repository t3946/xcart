<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\TextAreaField;

class DistributorQuickLinksForm extends DistributorForm
{

    public function getFields()
    {
        return [
            'd_website_search_for_sku_url' => [
                'class' => CharField::class,
                'label' => 'Link to product on distributor website (use {{mpn}}):',
                'hint' => 'Hint',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_link_to_order_distributors_website' => [
                'class' => CharField::class,
                'hint' => 'Hint',
                'label' => 'Link to order on distributor website (use {{orderid}}):',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
        ];
    }
}