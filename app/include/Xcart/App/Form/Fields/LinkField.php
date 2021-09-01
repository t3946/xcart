<?php

namespace Xcart\App\Form\Fields;

class LinkField extends CharField
{
    public $inputTemplate = 'forms/field/link/input.tpl';
    public string $link_content = 'Call';

    public function getCommonData()
    {
        return ['link_content' => $this->link_content];
    }
}
