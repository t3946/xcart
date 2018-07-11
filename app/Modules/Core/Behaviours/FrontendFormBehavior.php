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
use Xcart\App\Helpers\Creator;

class FrontendFormBehavior extends FormViewBehavior
{

    /**
     * Default template
     * @var string
     */
    protected $defaultTemplateType = 'frontend';

    /**
     * Additional templates
     * @var array
     */
    protected $templates = [
        'frontend' => 'forms/frontend.tpl'
    ];

    /**
     * Default params for all fields
     * @var array
     */
    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/field_custom.tpl'
    ];

    /**
     * Default params for compound fields
     * @var array
     */
    protected $fieldsCompoundSettings = [
        'fieldTemplate' => 'forms/field/default/custom/field_compound.tpl'
    ];

    /**
     * Initialize fields
     */
    public function initFields()
    {
        $prefix = $this->owner->getPrefix();
        $fields = $this->owner->getFields();

        foreach ($fields as $name => $config) {

            if (\in_array($name, $this->owner->getExclude(), true)) {
                continue;
            }

            if (!\is_array($config)) {
                $config = ['class' => $config];
            }

            if(empty($config['extend'])) {

                $newField = Creator::createObject(array_merge([
                    'name' => $name,
                    'form' => $this->owner,
                    'prefix' => $prefix,
                ], $this->fieldsSettings, $config));

            } else {

                $newField = Creator::createObject(array_merge([
                    'name' => $name,
                    'form' => $this->owner,
                    'prefix' => $prefix,
                ], $this->fieldsSettings, $this->fieldsCompoundSettings, $config));
            }

            $this->owner->addInitField($name, $newField);

        }
    }

}