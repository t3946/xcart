<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Editor\Fields\EditorField;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;

class DistributorGeneralForm extends DistributorForm
{
    public function getFields()
    {
        return [
            'manufacturer' => [
                'class' => CharField::class,
                'label' => 'Distributor company name',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'code' => [
                'class' => CharField::class,
                'label' => 'Distributor prefix',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'url' => [
                'class' => CharField::class,
                'label' => 'Distributor website URL (main page)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'logo' => [
                'class' => ImageField::class,
                'label' => 'Logo',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_sites' => [
                'class' => Select2Field::class,
                'label' => 'Main SF',
                'multiple' => true,
                'choices' => static function () {
                    foreach (SiteModel::objects()->all() as $site) {
                        $result[$site->pk] = (string)$site;
                    }
                    return $result ?? [];
                },
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'd_specific_instructions' => [
                'class' => EditorField::class,
                'label' => 'Distributor notes for dispatcher (Dx notes)',
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'dx_eta_date' => [
                'class' => DateField::class,
                'label' => 'Dx warehouse is closed until',
                'html' => ['style' =>'width:100px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ],
            'avail' => [
                'class' => CheckboxField::class,
                'html' => ['style' =>'width:16px;'],
                'fieldTemplate' => $this->fieldTemplate,
                'hintTemplate' => $this->hintTemplate,
            ]
        ];
    }
}