{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

{* {if $active_modules.CIDEV_Best_Search_Filter and $section eq "cidev_filter"} *}
{if $active_modules.CIDEV_Best_Search_Filter}
<a name="cidev_filter"></a>
<a name="section_cidev_filter"></a>

{if $cidev_filters_tree ne ""}
{capture name="dialog"}

<form name="cidev_product_filter_form" method="post">

<input type="hidden" name="mode" value="cidev_filter_modify" />


<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

function func_minus_filter(obj){
	var id;
	id = obj.id.replace("minus_filter_","");
	$("#cidev_add_filter_row_"+id).remove();
}

$(function(){

/* var   form = $("#cidev_filter_table");
   var   plus_filter = $("#plus_filter");
   var   clon1 = $("select[name='filter_name_id[0]']");
   var   clon2 = $("select[name='filter_value_id[0]']");
   var   idClon1 = $(clon1).attr("id");
   var   idClon2 = $(clon2).attr("id");
   var   clonP = $("select[name='filter_name_id[0]']").parent().parent();  */ /* Если убрать один из .parent(), то примет не <tr>,а <td> вместе с содержимым*/

   var   i=0;

   $("#plus_filter").on("click", function(){
//      var new_cloned_row = $(clonP).clone();

	i++;

        var new_cloned_row = $("#cidev_add_filter_row_0").clone();
	new_cloned_row.attr("id", "cidev_add_filter_row_"+i);

        new_cloned_row.find("#filter_name_id_0").attr("name", "filter_name_id["+i+"]");
        new_cloned_row.find("#filter_value_id_0").attr("name", "filter_value_id["+i+"]");

        new_cloned_row.find("#filter_name_id_0").attr("id", "filter_name_id_"+i);
        new_cloned_row.find("#filter_value_id_0").attr("id", "filter_value_id_"+i);

        new_cloned_row.find("#minus_filter_0").attr("id", "minus_filter_"+i);
        new_cloned_row.find("#div_minus_filter_0").attr("id", "div_minus_filter_"+i);
        new_cloned_row.find("#div_minus_filter_"+i).css("display","");

        new_cloned_row.find("#div_plus_filter").remove();

        $("#cidev_add_filter_table").find("tr:last").after(new_cloned_row);
   });
});

{/literal}
-->
</script>

 <table id="cidev_add_filter_table">
  <tr id="cidev_add_filter_row_0">
    <td>
        <select name="filter_name_id[0]" id="filter_name_id_0">
        <option value="">{$lng.lbl_select} {$lng.lbl_cidev_filter_name}</option>
        {foreach from=$cidev_filters_tree item=filter key=filter_key}
        <option value="{$filter.f_id}">{$filter.f_name}</option>
        {/foreach}
        </select>
    </td>

    <td>
        <select name="filter_value_id[0]" id="filter_value_id_0">
        <option value="">{$lng.lbl_select} {$lng.lbl_cidev_filter_value}</option>
        </select>
    </td>

    <td>
	<div id="div_plus_filter"><input type="button" value="+" id="plus_filter" /></div>
	<div style="display: none;" id="div_minus_filter_0"><input type="button" value="&nbsp;&nbsp;&#822;" id="minus_filter_0" onclick="func_minus_filter(this);" /></div>
    </td>

  </tr>
 </table>



<table width="100%">
{foreach from=$cidev_filters_tree item=filter key=filter_key}
 <tr>

   <td valign="top">

	<h3>{$lng.lbl_cidev_filter}: {$filter.f_name} {if $filter.f_active ne "Y"}({$lng.lbl_cidev_f_is_disabled}){/if}</h3>

	{if $filter.filter_values ne ""}
	{assign var="filter_values_were_found" value="Y"}
	<table>
        {foreach from=$filter.filter_values item=filter_values key=filter_values_key}
	 <tr>
	   <td>
                <input type="checkbox" name="posted_filter_values[{$filter_values.fv_id}]" value="Y" 
			{if $cidev_filter_product ne ""}
			{foreach from=$cidev_filter_product item=item key=key}
			{if $item.fv_id eq $filter_values.fv_id} checked="checked"{/if}
			{/foreach}
			{/if}
		>
	   </td>
	   <td>
                {$filter_values.fv_name} {if $filter_values.fv_active ne "Y"} ({$lng.lbl_cidev_fv_is_disabled}){/if}
	   </td>
	 </tr>
        {/foreach}
        </table>
	{/if}

 </tr>
 <tr><td height="10">&nbsp;</td></tr>

{/foreach}
</table>

{if $filter_values_were_found eq "Y"}
<br />
<input type="submit" value="{$lng.lbl_modify}" />
{/if}

</form>

{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_cidev_product_filters extra='width="100%"'}
{*
{else}
{$lng.txt_N_results_found|substitute:"items":0}
*}
<br />
{/if}
{/if}

