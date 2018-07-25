<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 10.07.2018
 * Time: 14:39
 */

namespace Xcart\App\Form;


use Xcart\App\Form\Traits\FormBehaviourRenderTrait;

abstract class EventsModelForm extends ModelForm
{
    use FormBehaviourRenderTrait;

    public function __construct(array $config = [])
    {
        $this->beforeConstruct();
        parent::__construct($config);
    }

    abstract protected function beforeConstruct();
}