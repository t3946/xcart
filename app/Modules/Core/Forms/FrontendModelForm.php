<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 12:52
 */

namespace Modules\Core\Forms;


use Modules\Core\Behaviours\FrontendFormBehavior;
use RuntimeException;
use Xcart\App\Form\DecoratedModelForm;
use Xcart\App\Form\ModelForm;
use Xcart\App\Traits\RenderTrait;

class FrontendModelForm extends DecoratedModelForm
{
    use RenderTrait;

    protected $userFields = [];

    public function init(){
        parent::init();
        $this->userFields = array_keys($this->getFields());
    }

    public function renderInternal($template, array $params)
    {
        return self::renderTemplate($template, $params);
    }

    protected function behaviours()
    {
        return [
            'decor' => [
                'class' => FrontendFormBehavior::class
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
}