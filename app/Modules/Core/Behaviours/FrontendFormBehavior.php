<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 13:04
 */

namespace Modules\Core\Behaviours;


use Exception;
use Xcart\App\Behaviours\BaseBehavior;
use Xcart\App\Form\FormView\FormViewBehavior;

class FrontendFormBehavior extends FormViewBehavior
{
    protected $templates = [
        'frontend' => 'forms/frontend.tpl'
    ];

    /**
     * @var string
     */
    protected $defaultTemplateType = 'frontend';


    protected $fieldCompoundTemplate = 'forms/field/default/custom/field_compound.tpl';
    protected $fieldTemplate = 'forms/field/default/custom/field_custom.tpl';




    public function __construct()
    {
        var_dump('construct FrontendFormBehavior');
    }

    public function init()
    {
        var_dump('init FrontendFormBehavior');

        parent::init();

        //$this->owner->templates = array_merge($this->owner->templates, $this->templates);
        //var_dump($this->owner->templates);
        //$this->owner->defaultTemplateType = $this->defaultTemplateType;

    }

}