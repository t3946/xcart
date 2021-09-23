<?php
function smarty_modifier_lookup($value='', $from=array())
{
    if (array_key_exists($value, $from)) {
        return $from[$value];
    }
    return '';
}