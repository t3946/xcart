{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}
<br />

{capture name=dialog}

<form name="form_sitemap" method="post" action="{$smarty.server.REQUEST_URI|escape}">
<input type="hidden" name="sitemap[config]" value="update" />

<table width="100%">

<tr>
  <td colspan="5"><br />{include file="main/subheader.tpl" title="Extra URLs"}</td>
</tr>

<tr class="TableHead">
  <td width="10">&nbsp;</td>
  <td width="30%">{$lng.lbl_page_name}</td>
  <td width="50%">{$lng.lbl_page_url}</td>
  <td width="10%">{$lng.lbl_orderby}</td>
  <td width="10%">{$lng.lbl_active}</td>
</tr>

{if $sitemap_extra ne ''}

{foreach from=$sitemap_extra item=item}
<tr{cycle name=$type values=", class='TableSubHead'"}>
  <td><input type="checkbox" name="sitemap[delete][]" value="{$item.id}" /></td>
  <td align="center"><input type="text" size="27" name="sitemap[update][{$item.id}][name]" value="{$item.name|escape}" /></td>
  <td align="center"><input type="text" size="47" name="sitemap[update][{$item.id}][url]" value="{$item.url|escape}" /></td>
  <td align="center"><input type="text" size="5" name="sitemap[update][{$item.id}][orderby]" value="{$item.orderby}" /></td>
  <td align="center"><input type="checkbox" name="sitemap[update][{$item.id}][active]" value="Y"{if $item.active eq 'Y'} checked="checked"{/if} /></td>
</tr>
{/foreach}

<tr>
  <td colspan="5" class="SubmitBox">
  <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick='javascript: if (checkMarks(this.form, new RegExp("sitemap.delete", "ig"))) {ldelim}document.form_sitemap.elements["sitemap[config]"].value="delete"; document.form_sitemap.submit();{rdelim}' />
  <input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
  </td>
</tr>

{else}

<tr>
  <td colspan="5" align="center">No extra urls defined</td>
</tr>

{/if}

<tr>
  <td colspan="5"><br />{include file="main/subheader.tpl" title=$lng.lbl_add_new}</td>
</tr>

<tr>
  <td>&nbsp;</td>
  <td align="center"><input type="text" size="27" name="sitemap[add][name]" /></td>
  <td align="center"><input type="text" size="47" name="sitemap[add][url]" /></td>
  <td align="center"><input type="text" size="5" name="sitemap[add][orderby]" value="" /></td>
  <td align="center"><input type="checkbox" name="sitemap[add][active]" value="Y" checked="checked" /></td>
</tr>

<tr>
  <td colspan="5" class="SubmitBox">
  <input type="button" value="{$lng.lbl_add_new|strip_tags:false|escape}" onclick='javascript: document.form_sitemap.elements["sitemap[config]"].value="add"; document.form_sitemap.submit();' />
  </td>
</tr>

</table>

</form>
{/capture}
{include file="dialog.tpl" title="Extra URLs" content=$smarty.capture.dialog extra='width="100%"'}
