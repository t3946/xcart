<?php

namespace Xcart\App\Form\Fields;

/**
 * Class DateField
 * @package Mindy\Form
 */
class DateField extends CharField
{
//    public $type = 'hidden';

    public function render()
    {
        $id = $this->getHtmlId();
        $value = $this->getValue();

        if (is_string($value)) {
            $time = strtotime($value);
        }
        else if (is_int($value)) {
            $time = $value;
        }
        else if ($value instanceof \DateTime) {
            $date = $value;
        }

        if (isset($time)) {
            $date = new \DateTime();
            $date->setTimestamp($time);
        }


        $js = "<script type='text/javascript'>(function(){
    let date = new Date();
    date.setDate('{$date->format('d')}');
    date.setFullYear('{$date->format('Y')}');
    date.setMonth('{$date->format('m')}');
    $('#$id').datepicker({language: 'en', startDate: date}).selectDate(date);
})()</script>";
        return parent::render() . $js;
    }
}
