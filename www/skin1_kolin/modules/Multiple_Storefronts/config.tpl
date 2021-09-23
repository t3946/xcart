{* $Id: sf_config_manufacturers.tpl,v 1.0 2010/12/20 14:40:34 kate Exp $ *}

<select name="update[manufacturers][]" multiple="multiple">
	{foreach from=$manufacturers item=v}
		<option value='{$v.manufacturerid}'{if $v.selected eq 'Y'} selected="selected"{/if}>{$v.manufacturer}</option>
	{/foreach}
</select>
