{* $Id: product_page_options.tpl,v 1.0 2011/01/18 12:48:27 kate Exp $ *}
<tr>
	<td colspan="3" class="TableSeparator">
		<br /><br />
		{$lng.lbl_prod_name_replace_rules}
	</td>
</tr>
<tr>
	<td colspan="3">
		{$lng.txt_replacements_prod_page_opts}
		<script type="text/javascript">
		<!--
			var lbl_replace = '{$lng.lbl_replace}';
			var lbl_by = '{$lng.lbl_by}';
			var lbl_add = '{$lng.lbl_add|escape}';
			var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
			var ImagesDir = '{$ImagesDir}';
			{if $qreps}
				var row_max_index = {$qreps};
			{else}
				var row_max_index = 1;
			{/if}
		-->
		</script>
		{include file="main/include_js.tpl" src="admin/main/product_page_options.js"}
		<br /><br />
	</td>
</tr>

{if $replacements}
	{foreach from=$replacements item="replacement" key=key name="repforeach"}
	<tr id="rep_{$key}">
		<td>&nbsp;</td>
		<td colspan="2">
			{$lng.lbl_replace}&nbsp;<input type="text" size="20" name="rep[{$key}][what]" value="{$replacement.what|escape}" />&nbsp;{$lng.lbl_by}&nbsp;<input type="text" size="20" name="rep[{$key}][by]" value="{$replacement.by|escape}" />&nbsp;<a href="javascript: void(0);" onclick="javascript: add_replacement_row('{$key}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>{if !$smarty.foreach.repforeach.first}&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_replacement_row('{$key}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>{/if}
		</td>
	</tr>
	{/foreach}
{else}
	<tr id="rep_1">
		<td>&nbsp;</td>
		<td colspan="2">
			{$lng.lbl_replace}&nbsp;<input type="text" size="20" name="rep[1][what]" value="" />&nbsp;{$lng.lbl_by}&nbsp;<input type="text" size="20" name="rep[1][by]" value="" />&nbsp;<a href="javascript: void(0);" onclick="javascript: add_replacement_row(1);"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
		</td>
	</tr>
{/if}
