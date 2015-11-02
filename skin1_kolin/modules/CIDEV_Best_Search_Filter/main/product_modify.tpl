{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

{* {if $active_modules.CIDEV_Best_Search_Filter and $section eq "cidev_filter"} *}
{* {if $active_modules.CIDEV_Best_Search_Filter} *}
<a name="cidev_filter"></a>
<a name="section_cidev_filter"></a>

{if $cidev_filters_tree ne ""}
{capture name="dialog"}

{* <form name="cidev_product_filter_modify_form" method="post" action="product_modify.php?productid={$productid}#section_cidev_filter" > *}

<form name="cidev_product_filter_modify_form" method="post" action="product_modify.php" >

<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="mode" value="cidev_filter_delete" />
<input type="hidden" name="geid" value="{$geid}" />

{if $geid ne ''}
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="15" class="TableSubHead">
<input type="checkbox" value="Y" name="fv_id_fields[delete]" />
</td>
<td>
{/if}

<table width="100%" cellpadding="0" cellspacing="0" border="0">
{assign var="any_filter_values_was_found" value="N"}
{foreach from=$cidev_filters_tree item=filter key=filter_key}
 <tr>

   <td valign="top">

	{if $filter.filter_values ne ""}

	{assign var="filter_values_were_found" value="N"}
	{foreach from=$filter.filter_values item=filter_values key=filter_values_key}
	{if $cidev_filter_product ne ""}
	{foreach from=$cidev_filter_product item=item key=key}
	{if $item.fv_id eq $filter_values.fv_id && $filter_values_were_found eq "N"}
	<B>{$lng.lbl_cidev_filter}: {$filter.f_name}</B> {if $filter.f_active ne "Y"}({$lng.lbl_cidev_f_is_disabled}){/if}
	{assign var="filter_values_were_found" value="Y"}
	{assign var="any_filter_values_was_found" value="Y"}
	{/if}
	{/foreach}
	{/if}
	{/foreach}

        <table cellpadding="0" cellspacing="0">
	<tr>
        {foreach from=$filter.filter_values item=filter_values key=filter_values_key}
{if $cidev_filter_product ne ""}
{foreach from=$cidev_filter_product item=item key=key}
{if $item.fv_id eq $filter_values.fv_id}

	  <td>
                <input type="checkbox" name="posted_filter_values[{$filter_values.fv_id}]" value="Y" />
	  </td>
	  <td>
		{$filter_values.fv_name} {if $filter_values.fv_active ne "Y"} ({$lng.lbl_cidev_fv_is_disabled}){/if}
	  </td>
	  <td width="15">&nbsp;</td>
{/if}
{/foreach}
{/if}
        {/foreach}
         </tr>
        </table>
        {/if}

   </td>
 </tr>
 {if $filter_values_were_found eq "Y"}
 <tr><td height="10">&nbsp;</td></tr>
 {/if}

{/foreach}

 {if $any_filter_values_was_found eq "Y"}
 <tr>
  <td>
	<hr />
	<input type="submit" value="{$lng.lbl_delete_selected}" />
  </td>
 </tr>
 {/if}

</table>

{if $geid ne ''}
</td>
<tr>
</table>
{/if}

</form>

{if $any_filter_values_was_found eq "Y"}
<br />
<br />
<br />
{/if}

{include file="main/subheader.tpl" title=$lng.lbl_cidev_add_filter}


{* <form name="cidev_product_filter_add_form" method="post" action="product_modify.php?productid={$productid}#section_cidev_filter"> *}
<form name="cidev_product_filter_add_form" method="post" action="product_modify.php">

<input type="hidden" name="mode" value="cidev_filter_add" />
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="geid" value="{$geid}" />

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

function func_set_filter_value(obj){
	var id;
	id = obj.id.replace("filter_name_id_","");
	var filter_id = $("#"+obj.id).val();

	$('#filter_value_id_'+id).each(function() {
		$('#filter_value_id_'+id+' option').remove();
	});

	$('#filter_value_id_'+id)
         .append($("<option></option>")
         .attr("value", '')
         .text('{/literal}{$lng.lbl_select} {$lng.lbl_cidev_filter_value}{literal}')); 

	{/literal}
	{foreach from=$cidev_filters_tree item=filter key=filter_key}	
	{literal}
		if (filter_id == "{/literal}{$filter.f_id}{literal}"){

			{/literal}
			{if $filter.filter_values ne ""}
			{foreach from=$filter.filter_values item=item key=key} 
			{literal}

			        $('#filter_value_id_'+id)
			         .append($("<option></option>")
			         .attr("value", '{/literal}{$item.fv_id}{literal}')
			         .text('{/literal}{$item.fv_name|escape}{literal}')); 

		        {/literal}
		        {/foreach}
			{/if}
		        {literal}
		}
	{/literal}
	{/foreach}
	{literal}

}

function func_minus_filter(obj){
	var id;
	id = obj.id.replace("minus_filter_","");
	$("#cidev_add_filter_row_"+id).remove();
}

$(function(){

   var   i=0;

   $("#plus_filter").on("click", function(){

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

function cidev_start(){
	$("#filter_name_id_0").val("");
}

$(document).ready(function() {
        window.onload = cidev_start();
});

{/literal}
-->
</script>

{if $geid ne ''}
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="15" class="TableSubHead">
<input type="checkbox" value="Y" name="fv_id_fields[add]" />
</td>
<td>
{/if}

 <table id="cidev_add_filter_table">
  <tr id="cidev_add_filter_row_0">
    <td>
        <select name="filter_name_id[0]" id="filter_name_id_0" onchange="func_set_filter_value(this);">
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

 <table>
  <tr>
   <td>
	<input type="submit" value="{$lng.lbl_add}" />
   </td>
  </tr>
 </table>

{if $geid ne ''}
</td>
</tr>
</table>
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

{* {/if} *}

