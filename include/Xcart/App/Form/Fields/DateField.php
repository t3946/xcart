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
        $airOptions = $this->getAirDPOptions();
        $airOptions = json_encode($airOptions);

        $js = "<script type='text/javascript'>(function(){
    $('#$id')
        .airdate({$airOptions})
        .data('airdate')
        .selectDate({$this->getJSDate()});
})()</script>";
        return parent::render() . $js;
    }

    public function getJSDate()
    {
        if ($this->getValue()) {
            $date = $this->getDateFromValue();
            return "new Date({$date->format('Y')}, {$date->format('m')}-1, {$date->format('d')}, {$date->format('H')}, {$date->format('i')})";
        }

        return;
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
