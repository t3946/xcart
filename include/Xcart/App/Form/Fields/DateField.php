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
        $date = $this->getDateFromValue();
        $airOptions = $this->getAirDPOptions();
        $airOptions = json_encode($airOptions);

        $js = "<script type='text/javascript'>(function(){
    $('#$id')
        .datepicker({$airOptions})
        .data('datepicker')
        .selectDate(new Date({$date->format('Y')}, {$date->format('m')}-1, {$date->format('d')}, {$date->format('H')}, {$date->format('i')}));
})()</script>";
        return parent::render() . $js;
    }

    public function getAirDPOptions()
    {
        return [
            'language' => 'en',
            'position' => 'top left',
        ];
    }

    public function getDateFromValue()
    {
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
        return $date;
    }
}
