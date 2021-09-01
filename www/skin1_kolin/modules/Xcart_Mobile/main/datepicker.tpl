{*
$Id: datepicker.tpl 63 2012-10-30 11:56:13Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
<input id="{$id|default:$name|escape}" class="datepicker-formatted" name="{$name|escape}" type="date" max="{$smarty.now|date_format:$config.Appearance.date_format}" value="{$date|default:$smarty.now|date_format:$config.Appearance.date_format}" />