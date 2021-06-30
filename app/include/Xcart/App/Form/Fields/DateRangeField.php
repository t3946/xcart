<?php

namespace Xcart\App\Form\Fields;


class DateRangeField extends DateField
{
    public bool $range = false;

    public function getAirDPOptions(): array
    {
        return array_merge(parent::getAirDPOptions(), [
            'range' => $this->range,
            'multipleDatesSeparator' => ' - '
        ]);
    }

    public function getRenderValue()
    {
        return $this->getValue();
    }

    protected function getJsCode($id, $airOptions): string
    {
        return "
<script>
    (function(){ 
      //TODO: убрал airdate
//        $('#$id').airdate({$airOptions}).data('airdate').selectDate({$this->getRenderValue()}) 
    })()
</script>";
    }
}
