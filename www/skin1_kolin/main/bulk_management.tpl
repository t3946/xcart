{* $Id: bulk_management.tpl,v 1.0 2010/10/20 18:29:48 kate Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_bulk_product_management}
{include file="dialog_tools.tpl"}
<br />
{include file="main/include_js.tpl" src="change_all_checkboxes.js"}

{capture name=dialog}
<script type="text/javascript" src="{$SkinDir}/bulk_management.js"></script>
<script type="text/javascript">
<!--
{literal}
function select_all(id, prefix) {
	if (document.getElementById(id).checked) {
		checkAll(true, document.bulkform, prefix);
	} else {
		checkAll(false, document.bulkform, prefix);
	}
}
{/literal}
-->
</script>

<form name="bulkform" action="bulk_management.php" method="post">
<input type="hidden" name="mode" value="review" />

<table cellpadding="1" cellspacing="1" width="100%">

<tr>
	<td colspan="2" id="hnew">{include file="main/subheader.tpl" title=$lng.txt_bulk_manage_new_title class="just_red_line"}{$lng.txt_bulk_manage_new}</td>
</tr>
<tr>
	<td colspan="2">
		{if $new}
			<div class="b_scroll_div">
				<table cellspacing="1" cellpadding="1" width="100%" class="b_scroll_table" border="0">
				<tr>
                    <td>&nbsp;</td>
					{foreach from=$colnames item="col"}
						{if $col eq 'productcode'}
							<td class="b_colnames"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td><input type="checkbox" name="new_all" value="Y" id="new_all" onchange="javascipt: select_all('new_all', 'new_sel');" /></td><td nowrap="nowrap">{$lng.lbl_select_all}</td></tr></table></td>
						{else}
							<td class="b_colnames"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td>{if $col ne 'productid' && $col ne 'category'}<input type="checkbox" name="new_sel[{$col}]" value="Y" />{else}&nbsp;{/if}</td><td nowrap="nowrap">!{$col|upper}</td></tr></table></td>
						{/if}
					{/foreach}
				</tr>
				<tr class="dark">
                    <td>&nbsp;</td>
					{foreach from=$colnames item="col"}
						{if $col eq 'productcode'}
							<td class="b_subheader"><b>{$lng.lbl_sku}</b></td>
						{else}
							<td class="b_subheader"><b>{$lng.lbl_csv}</b></td>
						{/if}
					{/foreach}
				</tr>
				{foreach from=$new item="nproduct"}
					<tr{cycle values=', class="dark"' name="ncycle"}>
                        <td class="p_new_style"><a href="#" onclick="bpm_row_click(this); return false;"><div class="bpm_plus"></div></a></td>
						{foreach from=$colnames item="col"}
							<td class="p_new_style"{if $col eq 'productcode'} nowrap="nowrap"{/if}><div class="bpm_one_row pink">{$nproduct[$col]}</div></td>
						{/foreach}
					</tr>
				{/foreach}
				</table>
			</div>
		{else}
			<b>{$lng.lbl_no_products}</b>
		{/if}
	</td>
</tr>
<tr><td colspan="2"><br /><br /></td></tr>
<tr>
	<td colspan="2" id="hexisting">{include file="main/subheader.tpl" title=$lng.txt_bulk_manage_existing_title class="just_red_line"}{$lng.txt_bulk_manage_existing}</td>
</tr>
<tr>
	<td colspan="2">
		{if $existing}
			<div class="b_scroll_div">
				<table cellspacing="1" cellpadding="1" width="100%" class="b_scroll_table" border="0">
				<tr>
                    <td>&nbsp;</td>
					{foreach from=$colnames item="col"}
						{if $col eq 'productcode'}
							<td class="b_colnames"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td><input type="checkbox" name="existing_all" value="Y" id="existing_all" onchange="javascipt: select_all('existing_all', 'existing_sel');" /></td><td nowrap="nowrap">{$lng.lbl_select_all}</td></tr></table></td>
						{else}
							<td class="b_colnames" colspan="2"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td>{if $col ne 'productid' && $col ne 'category'}<input type="checkbox" name="existing_sel[{$col}]" value="Y" />{else}&nbsp;{/if}</td><td nowrap="nowrap">!{$col|upper}</td></tr></table></td>
						{/if}
					{/foreach}
				</tr>
				<tr class="dark">
                    <td class="b_subheader">&nbsp;</td>
					{foreach from=$colnames item="col"}
						{if $col eq 'productcode'}
							<td class="b_subheader"><b>{$lng.lbl_sku}</b></td>
						{else}
							<td class="b_subheader"><b>{$lng.lbl_dbsr}</b></td>
							<td class="b_subheader"><b>{$lng.lbl_csv}</b></td>
						{/if}
					{/foreach}
				</tr>
				{foreach from=$existing item="eproduct" name="ecolumns"}
					<tr{cycle values=', class="dark"' name="ecycle"}>
                        <td><a href="#" onclick="bpm_row_click(this); return false;"><div class="bpm_plus"></div></a></td>
						{foreach from=$colnames item="col"}
							{if $col eq 'productcode'}
								<td nowrap="nowrap"><div class="bpm_one_row">{$eproduct.dbsr[$col]}</div></td>
							{elseif $col eq 'add_date'}
								<td class="b_empty_value"><div class="bpm_one_row">{$eproduct.dbsr[$col]|date_format:"%A %d %B %Y %T %p"}</div></td>
								<td><div class="bpm_one_row">{$eproduct.csv[$col]}</div></td>
							{else}
								<td class="b_empty_value"><div class="bpm_one_row">{$eproduct.dbsr[$col]}</div></td>
								<td><div class="bpm_one_row">{$eproduct.csv[$col]}</div></td>
							{/if}
						{/foreach}
					</tr>
				{/foreach}
				</table>
			</div>
		{else}
			<b>{$lng.lbl_no_products}</b>
		{/if}
	</td>
</tr>
<tr><td colspan="2"><br /><br /></td></tr>
<tr>
<td colspan="2" id="hdiscontinued">{include file="main/subheader.tpl" title=$lng.txt_bulk_manage_discontinued_title class="just_red_line"}{$lng.txt_bulk_manage_discontinued}</td>
</tr>
<tr>
	<td colspan="2">
		{if $discontinued}
			<div class="b_scroll_div">
				<table cellspacing="1" cellpadding="1" width="100%" class="b_scroll_table" border="0">
				<tr>
                    <td class="b_colnames">&nbsp;</td>
					{foreach from=$colnames item="col"}
						{if $col eq 'productcode'}
							<td class="b_colnames">&nbsp;</td>
						{else}
							<td class="b_colnames" nowrap="nowrap">!{$col|upper}</td>
						{/if}
					{/foreach}
				</tr>
				<tr class="dark">
                    <td class="b_subheader">&nbsp;</td>
					{foreach from=$colnames item="col"}
						{if $col eq 'productcode'}
							<td class="b_subheader"><b>{$lng.lbl_sku}</b></td>
						{else}
							<td class="b_subheader"><b>{$lng.lbl_dbsr}</b></td>
						{/if}
					{/foreach}
				</tr>
				{foreach from=$discontinued item="dproduct"}
					<tr{cycle values=', class="dark"' name="dcycle"}>
                        <td class="p_discont_style"><a href="#" onclick="bpm_row_click(this); return false;"><div class="bpm_plus"></div></a></td>
						{foreach from=$colnames item="col"}
							{if $col eq 'add_date'}
								<td class="p_discont_style"><div class="bpm_one_row blue">{$dproduct[$col]|date_format:"%A %d %B %Y %T %p"}</div></td>
							{else}
								<td class="p_discont_style"{if $col eq 'productcode'} nowrap="nowrap"{/if}><div class="bpm_one_row blue">{$dproduct[$col]}</div></td>
							{/if}
						{/foreach}
					</tr>
				{/foreach}
				</table>
			</div>
		{else}
			<b>{$lng.lbl_no_products}</b>
		{/if}
	</td>
</tr>

<tr>
	<td width="6px"><input type="checkbox" name="avail_disabled" value="Y" /></td>
	<td>{$lng.lbl_change_availability_to_disabled}</td>
</tr>

<tr>
	<td><input type="checkbox" name="qis_zero" value="Y" /></td>
	<td>{$lng.lbl_change_quantity_in_stock_to_zero}</td>
</tr>

<tr>
	<td><input type="checkbox" name="change_catid" value="Y" /></td>
	<td>{$lng.lbl_change_main_catid}&nbsp;<input type="text" name="newcatid" value="" size="15" /></td>
</tr>

<tr>
	<td colspan="2">
		<br />
		<input type="button" value="{$lng.lbl_review_updation}" onclick="javascript: document.bulkform.submit();" />&nbsp;
		<input type="button" value="{$lng.lbl_cancel}" onclick="javascript: self.location = 'search.php?mode=search';" />
	</td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_bulk_product_management content=$smarty.capture.dialog extra='width="100%"'}
