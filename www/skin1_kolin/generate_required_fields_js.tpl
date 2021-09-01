{* $Id: generate_required_fields_js.tpl,v 1.8.2.1 2006/11/03 07:31:53 max Exp $ *}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var requiredFields = [
{foreach from=$default_fields item=v key=k}
{if $v.required eq 'Y' && $v.avail eq 'Y' && !$v.js_required_block}
	["{$k}", "{$v.title|strip|replace:'"':'\"'}"],
{/if}
{/foreach}
{foreach from=$additional_fields item=v key=k}
{if $v.required eq 'Y' && $v.type eq 'T'  && $v.avail eq 'Y'} 
	["additional_values_{$v.fieldid}", "{$v.title|strip|replace:'"':'\"'}"],
{/if} 
{/foreach}
{if $anonymous eq "" or $config.General.disable_anonymous_checkout eq "Y"}
	["uname", "{$lng.lbl_username|strip|replace:'"':'\"'}"],
	["passwd1", "{$lng.lbl_password|strip|replace:'"':'\"'}"],
	["passwd2", "{$lng.lbl_confirm_password|strip|replace:'"':'\"'}"],
{/if}
];
-->
</script>
