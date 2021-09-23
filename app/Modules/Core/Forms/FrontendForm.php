<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 12:46
 */

namespace Modules\Core\Forms;


use Modules\Core\Behaviours\ClientValidationBehavior;
use Modules\Core\Behaviours\FormClearInputBehavior;
use Modules\Core\Behaviours\FrontendFormDisplayBehavior;
use Xcart\App\Form\EventsForm;
use Xcart\App\Traits\RenderTrait;

abstract class FrontendForm extends EventsForm
{
    use RenderTrait;

    public function renderInternal($template, array $params)
    {
        return self::renderTemplate($template, $params);
    }

    /**
     * Default Behaviour
     * The higher the position, the higher the priority
     * @return array
     */
    protected function behaviours()
    {
        return [
            'validation' => [
                'class' => ClientValidationBehavior::class,
                'enabled' => true
            ],
            'clear' => [
                'class' => FormClearInputBehavior::class,
                'enabled' => true
            ],
            'decor' => [
                'class' => FrontendFormDisplayBehavior::class,
                'enabled' => true
            ],
        ];
    }

    protected function beforeConstruct()
    {
        // Implement beforeConstruct() method.
    }
}