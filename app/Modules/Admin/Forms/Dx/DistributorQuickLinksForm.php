<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\LanguageModel;
use Xcart\App\Form\Fields\CharField;

class DistributorQuickLinksForm extends DistributorForm
{
    public function getFieldsets()
    {
        return [[
            'd_website_search_for_sku_url',
            'd_link_to_order_distributors_website',
        ]];
    }

    public function getFields()
    {
        return [
            'd_website_search_for_sku_url' => [
                'class' => CharField::class,
                'label' => 'Link to product on distributor website (use {{mpn}}):',
                'hint' => LanguageModel::translate('help_dx_search_for_sku_url_text') ?? 'help_dx_search_for_sku_url_text',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_link_to_order_distributors_website' => [
                'class' => CharField::class,
                'hint' => LanguageModel::translate('help_dx_link_to_order_text') ?? 'help_dx_link_to_order_text',
                'label' => 'Link to order on distributor website (use {{orderid}}):',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
        ];
    }
}