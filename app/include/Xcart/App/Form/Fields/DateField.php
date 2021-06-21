<?php

namespace Xcart\App\Form\Fields;

use DateTime;

/**
 * Class DateField
 * @package Mindy\Form
 */
class DateField extends CharField
{
    public string $format = 'Y-m-d H:i:s';

    public function render($fieldExtension = null)
    {
        return parent::render() . $this->getJsCode($this->getHtmlId(), json_encode($this->getAirDPOptions()));
    }

    protected function getJsCode($id, $airOptions): string
    {
        return "
<script>
    (function(){ 
      //TODO: убрал airdate
//        $('#$id').airdate({$airOptions}).data('airdate').selectDate({$this->getJSDate()}) 
    })()
</script>";
    }

    public function getRenderValue()
    {
        $date = $this->getDateFromValue();

        return ($date) ? $date->format('Y-m-d H:i:s'):'';
    }


    public function getJSDate(): string
    {
        if ($this->getValue() && $date = $this->getDateFromValue()) {
            return "new Date({$date->format('Y')}, {$date->format('m')}-1, {$date->format('d')}, {$date->format('H')}, {$date->format('i')})";
        }
        return '';
    }

    public function getAirDPOptions(): array
    {
        return [
            'language' => 'en',
            'position' => 'top left',
        ];
    }

    public function getDateFromValue():?DateTime
    {
        $value = $this->getValue();

        if (is_string($value)) {
            $time = strtotime($value);
        }
        else if (is_int($value)) {
            $time = $value;
        }
        else if ($value instanceof DateTime) {
            $date = $value;
        }

        if (isset($time)) {
            $date = new DateTime();
            $date->setTimestamp($time);
        }

        return $date ?? null;
    }
}
