{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

{capture name="dialog"}

<form action="cidev_admin_filter_values.php?f_id={$f_id}" method="post" name="cidev_add_filter_value_form" >

<input type="hidden" name="mode" value="add">

<table width="100%" align="left">

<tr>
  <th width="100px">POS.:</th>
  <td><input type="text" size="3" name="fv_order_by" value="" /></td>
</tr>

<tr>
  <th width="100px">{$lng.lbl_cidev_name}:</th>
  <td><input type="text" size="60" name="fv_name" value="" /></td>
</tr>

<tr>
  <th width="100px">{$lng.lbl_cidev_filter_active}:</th>
  <td><input type="checkbox" name="fv_active" value="Y" checked="checked" /></td>
</tr>

<tr>
  <td width="100px">&nbsp;</td>
  <td>
	<input type="submit" class="big-main-button" value="{$lng.lbl_add|strip_tags:false|escape}" />
  </td>
</tr>

</table>

</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_cidev_add_value content=$smarty.capture.dialog extra='width="100%"'}
