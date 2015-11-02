{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

<br />
{capture name=dialog}

<div align="left">{include file="buttons/button.tpl" button_title=$lng.lbl_cidev_back_to_filters_list href="cidev_admin_filters.php"}</div>
<br />

<script type="text/javascript">
//<![CDATA[
var cidev_txt_update_msg = "{$lng.lbl_update|wm_remove|escape:javascript}" + "?";
//]]>
</script>


<form action="cidev_admin_filter_category.php?f_id={$f_id}" method="post" name="cidev_filter_cats_form">
  <input type="hidden" name="mode" value="update" />

  <select name="fc_categoryids[]" multiple="multiple" size="40">
{foreach from=$allcategories item=c key=catid}
        <option value="{$catid}" 
		{if $fc_categoryids[$catid] eq "Y"}
			selected="selected"
		{/if}
        >{$c}</option>
{/foreach}
  </select>
<br />
<br />

<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: if (confirm(cidev_txt_update_msg)) submitForm(this.form, 'update');" />

</form>


{/capture}
{include file="dialog.tpl" title=$lng.lbl_cidev_change_categories content=$smarty.capture.dialog extra='width="100%"'}
