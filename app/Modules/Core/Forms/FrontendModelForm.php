<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 12:52
 */

namespace Modules\Core\Forms;


use Modules\Core\Behaviours\ClientValidationBehavior;
use Modules\Core\Behaviours\FormClearInputBehavior;
use Modules\Core\Behaviours\FrontendFormDisplayBehavior;
use RuntimeException;
use Xcart\App\Form\DecoratedModelForm;
use Xcart\App\Form\ModelForm;
use Xcart\App\Traits\RenderTrait;

abstract class FrontendModelForm extends DecoratedModelForm
{
    use RenderTrait;

    protected $userFields = [];

    public $include = [];

    public function init(){
        parent::init();
        $this->userFields = array_keys($this->getFields());
    }

    public function renderInternal($template, array $params)
    {
        return self::renderTemplate($template, $params);
    }

    /**
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

    public function setRenderFields(array $fields = [])
    {
        if (empty($fields)) {
            $fields = array_keys($this->getFieldsInit());
        }
        $this->_renderFields = [];
        $initFields = $this->getFieldsInit();

        foreach ($fields as $name) {
            if (\in_array($name, $this->exclude, true) || !\in_array($name, $this->userFields, true)) {
                continue;
            }
            if (array_key_exists($name, $initFields)) {
                $this->_renderFields[] = $name;
            } else {
                throw new RuntimeException("Field $name not found");
            }
        }
        return $this;
    }

    /**
     * Initialize fields
     */
    public function initFields()
    {
        $fields = $this->getFields();
        $includeFields = array_merge($this->userFields, $this->include);
        foreach ($fields as $name => $config) {
            if (\in_array($name, $this->getExclude(), true)
                || !\in_array($name, $includeFields, true)) {
                continue;
            }

            if (!\is_array($config)) {
                $config = ['class' => $config];
            }

            $this->_fields[$name] = $this->createField($name, $config);
            //dd($includeFields);
        }
    }
}