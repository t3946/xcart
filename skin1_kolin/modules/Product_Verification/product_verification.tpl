{* $Id: categories.tpl,v 1.43.2.4 2007/01/10 07:27:08 max Exp $ *}

{if $supplemental_category_section ne "Y"}

{if ($smarty.get.mode ne "info")}
{include file="page_title.tpl" title="PRODUCT VERIFICATION"}
{else}
{include file="page_title.tpl" title=$lng.lbl_info_pages}
{/if}

{if ($smarty.get.mode ne "info")}
{$lng.txt_product_verification_top_text}
{assign var="capture_dialog_name" value="PRODUCT VERIFICATION"}
<br /><br />
{else}
{assign var="capture_dialog_name" value=$lng.lbl_info_pages}
{/if}

{else}
<br /><br />
{assign var="capture_dialog_name" value="PRODUCT VERIFICATION"}
{/if}



{capture name=dialog}

<br />

<form action="verify_category.php" method="post" name="processcategoryform">
<input type="hidden" name="cat_org" value="{$smarty.get.cat|escape:"html"}" />

{if $supplemental_category_section eq "Y"}
<input type="hidden" name="supplemental_category_section" value="Y" />
{/if}

<table cellpadding="2" cellspacing="1" width="100%">

<tr class="TableHead">
	<td>DISTRIBUTOR</td>
	<td align="center">ORDER #</td>
	<td align="center">BACK END</td>
	<td align="center">FRONT END</td>
	<td align="center">DISTR WEBSITE</td>
	<td align="center">LAST VERIF DATE</td>
	<td align="center">VERIFIED?</td>
</tr>

{assign var="cat_selected" value=0}
{foreach from=$subcategories item=c key=catid}

{if 
(($supplemental_category_section eq "Y" && $c.supplemental_category eq "Y") || ($supplemental_category_section ne "Y" && $c.supplemental_category ne "Y"))
&&
(($smarty.get.mode eq "info" && $c.order_by gt 500) || ($smarty.get.mode ne "info" && $c.order_by le 500) || ($smarty.get.cat gt 0))
}

<tr{cycle values=', class="TableSubHead"'}>
	<td width="1%"><input type="text" size="4" name="posted_data[{$catid}][order_by]" maxlength="4" value="{if $c.parentid neq $cat && $c.add_order_by}{$c.add_order_by}{else}{$c.order_by}{/if}" /></td>
	<td align="center"><a href="category_modify.php?cat={$catid}" title="{$lng.lbl_categories_more}">{$lng.lbl_categories_more}</a></td>
	<td align="center"><input type="text" size="60" value="{ $c.category|escape }" name="posted_data[{$catid}][category]" class="{if $c.avail eq "N"}ItemsListDisabled{else}ItemsListBold{/if}" /></td>
	<td align="center" nowrap="nowrap"><input type="text" size="5" name="posted_data[{$catid}][parentid]" value="{$c.parentid}" />{if $supplemental_category_section ne "Y"}&nbsp;<input type="text" size="20" name="posted_data[{$catid}][additional_parentid]" value="{$additional_parentid[$catid].add_parentids}" />{/if}</td>
	<td align="center" nowrap="nowrap"><input type="text" size="5" name="posted_data[{$catid}][parentid]" value="{$c.parentid}" />{if $supplemental_category_section ne "Y"}&nbsp;<input type="text" size="20" name="posted_data[{$catid}][additional_parentid]" value="{$additional_parentid[$catid].add_parentids}" />{/if}</td>
	<td align="center" nowrap="nowrap"><input type="text" size="5" name="posted_data[{$catid}][parentid]" value="{$c.parentid}" />{if $supplemental_category_section ne "Y"}&nbsp;<input type="text" size="20" name="posted_data[{$catid}][additional_parentid]" value="{$additional_parentid[$catid].add_parentids}" />{/if}</td>
	<td align="center">
	<select name="posted_data[{$catid}][avail]">
		<option value="Y"{if $c.avail eq "Y"} selected="selected"{/if}>{$lng.lbl_yes}</option>
		<option value="N"{if $c.avail eq "N"} selected="selected"{/if}>{$lng.lbl_no}</option>
	</select>
	</td>
</tr>

{assign var="cat_selected" value=1}
{/if}
{foreachelse}

<tr>
	<td colspan="{if $supplemental_category_section ne "Y"}12{else}10{/if}" align="center">{$lng.txt_no_categories}</td>
</tr>

{/foreach}

{if $subcategories && $cat_selected eq "1"}
<tr>
	<td colspan="{if $supplemental_category_section ne "Y"}12{else}10{/if}">
<b>*{$lng.lbl_note}:</b> {$lng.txt_categoryies_management_note}
	</td>
</tr>
<tr>
	<td colspan="{if $supplemental_category_section ne "Y"}12{else}10{/if}" class="SubmitBox">
<input type="button" value="{$lng.lbl_update_all|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'apply');" />
<br /><br />
<input type="button" value="{$lng.lbl_modify_first_selected|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />
<input type="button" value="{if $config.Appearance.delete_only_first_cat eq 'Y'}{$lng.lbl_delete_rirst_selected|strip_tags:false|escape}{else}{$lng.lbl_delete_selected|strip_tags:false|escape}{/if}" onclick="javascript: submitForm(this, 'delete');" />
	</td>
</tr>
{/if}

<tr>
	<td colspan="{if $supplemental_category_section ne "Y"}12{else}10{/if}" class="SubmitBox"><input type="button" value="{$lng.lbl_add_new_|strip_tags:false|escape}" onclick="self.location='category_modify.php?mode=add&amp;cat={$cat}&amp;supplemental_category_section={$supplemental_category_section|default:N}'" /></td>
</tr>

</table>

<input type="hidden" name="mode" value="apply" />
</form>

<br />

{/capture}
{include file="dialog.tpl" title=$capture_dialog_name content=$smarty.capture.dialog extra='width="100%"'}
