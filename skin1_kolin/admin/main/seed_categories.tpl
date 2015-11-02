{* $Id: seed_categories.tpl,v 1.0 2011/11/22 18:19:42 kate Exp $ *}

{include file="page_title.tpl" title=$lng.lbl_seed_categories_management}

{$lng.txt_seed_categories_management_top_text}

<br /><br />

{capture name=dialog}

{assign var="colspan" value="7"}

<form action="seed_categories.php" method="post" name="seedcategoryform">

<input type="hidden" name="mode" value="update" />

<table cellpadding="2" cellspacing="1" width="100%" class="scats-table">

{if $seed_categories}
    <tr>
        <td class="scats-table-links" colspan="{$colspan}">{include file="main/check_all_row.tpl" form="seedcategoryform" prefix="delete"}</td>
    </tr>
{/if}

<tr class="TableHead">
	<td width="5px">&nbsp;</td>
	<td>{$lng.lbl_pos}</td>
	<td width="40%" align="center">{$lng.lbl_frontend_text}</td>
	<td align="center">{$lng.lbl_catid}</td>
	<td width="40%" align="center">{$lng.lbl_cat_search_keyphrase}</td>
	<td width="5%" align="center">{$lng.lbl_is_bold}</td>
	<td width="5%" align="center">{$lng.lbl_enabled}</td>
</tr>

{if $seed_categories}
    {foreach from=$seed_categories item="scat" key="scatid"}
    <tr>
        <td><input type="checkbox" name="delete[{$scatid}]" value="Y" /></td>
        <td><input type="text" name="update[{$scatid}][orderby]" value="{$scat.orderby}" size="4" /></td>
        <td><input type="text" name="update[{$scatid}][title]" value="{$scat.title}" size="60" /></td>
        <td><input type="text" name="update[{$scatid}][catid]" value="{$scat.catid}" size="6" /></td>
        <td><input type="text" name="update[{$scatid}][keyphrase]" value="{$scat.keyphrase}" size="60" /></td>
        <td><input type="checkbox" name="update[{$scatid}][is_bold]" value="y"{if $scat.is_bold eq "Y"} checked="checked"{/if} /></td>
        <td>
            <select name="update[{$scatid}][avail]">
                <option value="Y"{if $scat.avail eq "Y"} selected="selected"{/if}>{$lng.lbl_yes}</option>
                <option value="N"{if $scat.avail neq "Y"} selected="selected"{/if}>{$lng.lbl_no}</option>
            </select>
        </td>
    </tr>
    {/foreach}

    <tr>
        <td colspan="{$colspan}" class="scats-table-btns">
            <input type="button" value="{$lng.lbl_delete_selected}" onclick="javascript: if (checkMarks(this.form, new RegExp('delete\[[0-9]+\]', 'gi'))) submitForm(this.form, 'delete');" />
            <input type="submit" value="{$lng.lbl_update}" />
        </td>
    </tr>

{else}
    <tr>
        <td align="center" colspan="{$colspan}">{$lng.lbl_no_seed_categories}</td>
    </tr>
{/if}

<tr>
    <td colspan="{$colspan}">&nbsp;</td>
</tr>

<tr>
    <td class="scats-table-btns" colspan="{$colspan}">{include file="main/subheader.tpl" title=$lng.lbl_add_seed_category}</td>
</tr>

<tr>
	<td width="5px">&nbsp;</td>
    <td><input type="text" name="new_scat[orderby]" value="" size="4" /></td>
    <td><input type="text" name="new_scat[title]" value="" size="60" /></td>
    <td><input type="text" name="new_scat[catid]" value="" size="6" /></td>
    <td><input type="text" name="new_scat[keyphrase]" value="" size="60" /></td>
    <td><input type="checkbox" name="new_scat[is_bold]" value="y" {* checked="checked" *} /></td>
    <td>
        <select name="new_scat[avail]">
            <option value="Y" selected="selected">{$lng.lbl_yes}</option>
            <option value="N">{$lng.lbl_no}</option>
        </select>
    </td>
</tr>
<tr>
    <td class="scats-table-btns" colspan="{$colspan}"><input type="button" value="{$lng.lbl_add_new}" onclick="javascript: submitForm(this.form, 'add');" /></td>
</tr>

</table>

{include file="admin/main/location.tpl"}

{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_seed_categories extra='width="100%"'}

