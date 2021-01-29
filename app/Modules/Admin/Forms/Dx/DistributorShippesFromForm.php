<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\LanguageModel;
use Modules\Core\Models\StateModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;

class DistributorShippesFromForm extends DistributorForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'sites', 'country_model', 'state_model', 'disabled_marketplaces'];

    public function getFieldsets()
    {
        return [[
            'm_address',
            'm_address_2',
            'm_country',
            'm_state',
            'm_city',
            'm_zipcode',
        ]];
    }

    public function getFields()
    {
        $dx = $this->getInstance();
        return [
            'm_address' => [
                'class' => CharField::class,
                'label' => 'Address',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:200px;'],
            ],
            'm_address_2' => [
                'class' => CharField::class,
                'label' => 'Address (line 2)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:200px;'],
            ],
            'm_city' => [
                'class' => CharField::class,
                'label' => 'City',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'html' => ['style' => 'width:200px;'],
            ],
            'm_country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () {
                    foreach (CountryModel::objects()->order(['name']) as $country) {
                        $result[$country->code] = (string)$country;
                    }
                    return $result ?? [];
                },
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
                'depends' => ['m_state']
            ],
            'm_state' => [
                'class' => DropDownField::class,
                'label' => 'State/Province',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () use ($dx) {
                    foreach (StateModel::objects()->filter(['country_code__in' => [$dx->m_country ?? 'US']]) as $state) {
                        $result[$state->code] = "{$state->country_code}: {$state}";
                    }
                    return $result ?? [];
                },
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'm_zipcode' => [
                'class' => CharField::class,
                'label' => 'Zip/Postal Code',
                'html' => ['style' => 'width:200px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
        ];
    }
}