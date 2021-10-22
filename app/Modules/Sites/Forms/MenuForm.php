<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\SiteMenuModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class MenuForm extends ModelForm
{
    public array $exclude = ['pos'];

    public function getModel(): SiteMenuModel
    {
        return new SiteMenuModel();
    }

    public function getFields(): array
    {
        $model = $this->getInstance();
        if ($model->isLeaf()) {
            return [
                'url' => [
                    'class' => CharField::class,
                    'label' => 'Url',
                    'html' => [
                        'style' => 'width: 300px'
                    ]
                ],
            ];
        }
        return [];
    }

    public function getName(): string
    {
        return 'Site menu';
    }
}