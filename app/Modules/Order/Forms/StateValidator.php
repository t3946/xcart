<?php

namespace Modules\Order\Forms;


use Modules\Core\Models\StateModel;
use Xcart\App\Translate\Translate;
use Xcart\App\Validation\Validator;

class StateValidator extends Validator
{

    private $depends;

    public function __construct($depends)
    {
        $this->depends = $depends;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function validate($value)
    {
        $state = StateModel::objects()->get(['code' => $value, 'country_code' => $this->getCountry()]);

        if (!$state) {
            $state = StateModel::objects()->get(['state' => $value, 'country_code' => $this->getCountry()]);
        }

        if (!$state) {
            $this->addError(Translate::getInstance()->t('validation', 'Is not a valid state', []));
        }

        return $this->hasErrors() === false;
    }

    public function getCountry()
    {
        return $this->getForm()->getField($this->depends['country'])->getValue();
    }
}