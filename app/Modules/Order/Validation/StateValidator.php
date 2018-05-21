<?php

namespace Modules\Order\Validation;


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
        if ($filter = $this->getCountryFilter()) {

            $state = StateModel::objects()->get(array_merge(['code' => $value], $filter));

            if (!$state) {
                $state = StateModel::objects()->get(array_merge(['state' => trim($value)], $filter));
            }

            if (!$state) {
                $this->addError(Translate::getInstance()->t('validation', 'Is not a valid state', []));
            }
        }

        return $this->hasErrors() === false;
    }

    public function getCountryFilter(): array
    {
        $country_code = $this->getForm()->getField($this->depends['country'])->getValue();

        if (!\in_array($country_code, ['US', 'CA'])) {
            return [];
        }
        return ['country_code' => $country_code];
    }
}