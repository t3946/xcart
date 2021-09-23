{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

{capture name=dialog}

<script type="text/javascript">
//<![CDATA[
var cidev_txt_delete_msg = "{$lng.lbl_delete_selected|wm_remove|escape:javascript}";
checkboxes_form = 'cidev_filters_form';
checkboxes = [{foreach from=$cidev_filters item=v key=k}{if $k gt 0},{/if}'to_delete[{$v.f_id}]'{/foreach}];
//]]>
</script>
<script type="text/javascript" src="{$SkinDir}/change_all_checkboxes.js"></script>

<div style="line-height:17px;"><a href="javascript:change_all(true);">{$lng.lbl_check_all}</a> / <a href="javascript:change_all(false);">{$lng.lbl_uncheck_all}</a></div>

<form action="cidev_admin_filters.php" method="post" name="cidev_filters_form">

<input type="hidden" name="mode" value="update" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
  <th width="1%">&nbsp;</th>
  <th width="5%">{$lng.lbl_cidev_filter_pos}</th>
  <th width="*">{$lng.lbl_cidev_filter_name}</th>
  <th width="5%">{$lng.lbl_cidev_filter_active}</th>
  <th width="15%">{$lng.lbl_cidev_filter_values}</th>
</tr>

{foreach from=$cidev_filters item=v}
<tr{cycle values=", class='TableSubHead'"}>
  <td align="center"><input type="checkbox" name="to_delete[{$v.f_id}]" /></td>
  <td align="center"><input type="text" name="records[{$v.f_id}][f_order_by]" size="5" value="{$v.f_order_by}" /></td>
  <td><input type="text" name="records[{$v.f_id}][f_name]" value="{$v.f_name|escape}" size="60" style="width: 96%;" /></td>
  <td align="center"><input type="checkbox" name="records[{$v.f_id}][f_active]" value="Y"{if $v.f_active eq "Y"} checked="checked"{/if} /></td>
  <td align="center"><a href="cidev_admin_filter_values.php?f_id={$v.f_id}">{$lng.lbl_cidev_add_modify_view}</a></td>
</tr>
{/foreach}

<tr>
  <td colspan="3">
        <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('^to_delete\\[.+\\]', 'gi')) &amp;&amp;confirm(cidev_txt_delete_msg)) submitForm(this.form, 'delete');" />
  </td>
  <td colspan="3" align="right" class="main-button">
        <input type="submit" class="big-main-button" value="{$lng.lbl_update|strip_tags:false|escape}" />
  </td>
</tr>

</table>

</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_cidev_filters content=$smarty.capture.dialog extra='width="100%"'}
