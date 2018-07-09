<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 12:52
 */

namespace Modules\Core\Forms;


use Modules\Core\Behaviours\FrontendFormBehavior;
use Xcart\App\Form\ModelForm;
use Xcart\App\Traits\RenderTrait;

class FrontendModelForm extends ModelForm
{
    use RenderTrait;

    public function renderInternal($template, array $params)
    {
        return self::renderTemplate($template, $params);
    }

    protected function behaviours()
    {
        return [
            'customFields' => [
                'class' => FrontendFormBehavior::class
            ]
        ];
    }
}