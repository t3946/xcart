<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 12:46
 */

namespace Modules\Core\Forms;


use Modules\Core\Behaviours\FrontendFormBehavior;
use Xcart\App\Form\BaseForm;
use Xcart\App\Traits\RenderTrait;

class FrontendForm extends BaseForm
{
    use RenderTrait;

    public function renderInternal($template, array $params)
    {
        return self::renderTemplate($template, $params);
    }

    /**
     * Default Behaviour
     * @return array
     */
    protected function behaviours()
    {
        return [
            'customFields' => [
                'class' => FrontendFormBehavior::class
            ]
        ];
    }
}