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
checkboxes_form = 'cidev_filter_values_form';
checkboxes = [{foreach from=$cidev_filter_values item=v key=k}{if $k gt 0},{/if}'to_delete[{$v.fv_id}]'{/foreach}];
//]]>
</script>
<script type="text/javascript" src="{$SkinDir}/change_all_checkboxes.js"></script>

<div style="line-height:17px;"><a href="javascript:change_all(true);">{$lng.lbl_check_all}</a> / <a href="javascript:change_all(false);">{$lng.lbl_uncheck_all}</a></div>

<form action="cidev_admin_filter_values.php?f_id={$f_id}" method="post" name="cidev_filter_values_form">

<input type="hidden" name="mode" value="update" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
  <th width="10">&nbsp;</th>
  <th width="10%">{$lng.lbl_cidev_pos}</th>
  <th width="*">{$lng.lbl_cidev_name}</th>
  <th width="15%">{$lng.lbl_cidev_active}</th>
</tr>

{foreach from=$cidev_filter_values item=v}
<tr{cycle values=", class='TableSubHead'"}>
  <td align="center"><input type="checkbox" name="to_delete[{$v.fv_id}]" /></td>
  <td align="center"><input type="text" name="records[{$v.fv_id}][fv_order_by]" size="5" value="{$v.fv_order_by}" /></td>
  <td><input type="text" name="records[{$v.fv_id}][fv_name]" value="{$v.fv_name|escape}" size="60" style="width: 99%;" /></td>
  <td align="center"><input type="checkbox" name="records[{$v.fv_id}][fv_active]" value="Y"{if $v.fv_active eq "Y"} checked="checked"{/if} /></td>
</tr>
{/foreach}

<tr>
  <td colspan="2">
        <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('^to_delete\\[.+\\]', 'gi')) &amp;&amp;confirm(cidev_txt_delete_msg)) submitForm(this.form, 'delete');" />
  </td>
  <td colspan="2" align="right" class="main-button">
        <input type="submit" class="big-main-button" value="{$lng.lbl_update|strip_tags:false|escape}" />
  </td>
</tr>

</table>

</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_cidev_filter_values content=$smarty.capture.dialog extra='width="100%"'}
