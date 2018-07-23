<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 10.07.2018
 * Time: 14:23
 */

namespace Xcart\App\Form;


use Xcart\App\Form\Traits\FormBehaviourRenderTrait;

abstract class DecoratedForm extends Form
{
    use FormBehaviourRenderTrait;

    public function __construct(array $config = [])
    {
        $this->beforeConstruct();
        parent::__construct($config);
    }

    abstract protected function beforeConstruct();

}