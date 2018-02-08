{* $Id: capitalize_js.tpl,v 1.0 2011/07/18 9:55:13 kate Exp $ *}

<script type="text/javascript">
<!--

var reps = Array();
{foreach from=$replacements item=r key=key}
	reps['{$key}'] = ['{$r.what|escape:javascript}', '{$r.by|escape:javascript}'];
{/foreach}

-->
</script>
{include file="main/include_js.tpl" src="capitalize_do.js"}
<input type="button" value=" {$lng.lbl_capitalize|strip_tags:false|escape} " onclick="javascript: capitalize('{$id}');" />
