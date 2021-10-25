<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\SiteSocialsModel;
use Modules\Sites\Models\SocialModel;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class SiteSocialForm extends ModelForm
{
    public array $exclude = ['site', 'order_by'];

    public function getFields(): array
    {
        return [
            'social' => [
                'class' => Select2Field::class,
                'choices' => static function () {
                    /** @var SocialModel $social_model */
                    foreach (SocialModel::objects()->all() as $social_model) {
                        $items[$social_model->pk] = "$social_model->type, $social_model->url";
                    }
                    return $items ?? [];
                }
            ],
            'is_active' => [
                'class' => CheckboxField::class,
                'label' => 'Is active',
            ]
        ];
    }

    public function getModel(): SiteSocialsModel
    {
        return new SiteSocialsModel();
    }
}