{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}
{if $products ne ""}
<br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
{include file="buttons/button.tpl" button_title=$lng.lbl_search_again href="cidev_admin_add_filter_to_products.php"}
</td>
</tr>
</table>
{/if}
<br />

{capture name=dialog}

{if $cidev_filters_tree ne ""}

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
                                 .text('{/literal}{$item.fv_name}{literal}')); 

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

{/if}




{if $mode ne "search" or $products eq ""}
<form action="cidev_admin_add_filter_to_products.php" method="post" name="searchform">
<input type="hidden" name="mode" value="search" />
<input type="hidden" name="filter_mode" value="search" /> 

<input type="hidden" name="filter_replace_query" value="Y" /> 

<table width="100%">

{if $cidev_filters_tree ne ""}
<tr>
<td colspan="3">
<br />
{include file="main/subheader.tpl" title=$lng.lbl_cidev_filter_name class="grey"}
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
</td>
</tr>
{/if}

<tr><td colspan="3">&nbsp;</td></tr>
<tr><td colspan="3"><input type="submit" value="{$lng.lbl_search|strip_tags:false|escape}" /></tr>
</table>

</form>
{/if}
{* ------------------------------------ *}




{if $products ne ""}

<form action="cidev_admin_add_filter_to_products.php" method="post" name="cidev_admin_add_filter_to_products_form2">

{if $cidev_filters_tree ne ""}

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

  <br />
 <table>
  <tr>
   <td>
        <input type="button" value="{$lng.lbl_cidev_multiple_filter_values_add|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm(lbl_cidev_multiple_filter_values_add)) submitForm(this, 'add_to_products');" />
   </td>

   <td>
	<input type="button" value="{$lng.lbl_cidev_multiple_filter_values_replace|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm(lbl_cidev_multiple_filter_values_replace)) submitForm(this, 'replace_from_products');" />
   </td>

   <td>
        <input type="button" value="{$lng.lbl_cidev_multiple_filter_values_delete|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm(lbl_cidev_multiple_filter_values_delete)) submitForm(this, 'delete_from_products');" />
   </td>

  </tr>
 </table>

<br />
<br />
{/if}


 {if $products eq "" && $mode ne "search"}
	{assign var="mode" value="search"}
 {/if}

 {if $mode eq "search"}
 {if $total_items gt "1"}
 {$lng.txt_N_results_found|substitute:"items":$total_items}<br />
 {$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
 {elseif $total_items eq "0" || $total_items eq ""}
 <br />
 <div align="center">{$lng.txt_no_products_in_cat}</div>
 {/if}
 {/if}
 <br />

 {if $products ne ""}

<script type="text/javascript">
//<![CDATA[
var lbl_cidev_multiple_filter_values_add = "{$lng.lbl_cidev_multiple_filter_values_add|wm_remove|escape:javascript}";
var lbl_cidev_multiple_filter_values_replace = "{$lng.lbl_cidev_multiple_filter_values_replace|wm_remove|escape:javascript}";
var lbl_cidev_multiple_filter_values_delete = "{$lng.lbl_cidev_multiple_filter_values_delete|wm_remove|escape:javascript}";
//]]>
</script>

  <!-- SEARCH RESULTS START -->

  <br />

  {if $total_pages gt 2}
  {assign var="navpage" value=$navigation_page}
  {/if}

  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="navpage" value="{$navpage}" />
  <input type="hidden" name="sort" value="{$smarty.get.sort}" />
  <input type="hidden" name="sort_direction" value="{$smarty.get.sort_direction}" />

  <table cellpadding="0" cellspacing="0" width="100%">

  <tr>
  <td>

  {include file="customer/main/navigation.tpl"}

	{include file="main/check_all_row.tpl" style="line-height: 170%;" form="cidev_admin_add_filter_to_products_form2" prefix="productids"}
	<br />

	<table cellpadding="2" cellspacing="1" width="100%">

	{assign var="url_to" value="cidev_admin_add_filter_to_products.php?f_id=`$f_id`&amp;mode=search&amp;page=`$navpage`"}

	<tr class="TableHead">
	  <td width="5">&nbsp;</td>
	  <td width="50" nowrap="nowrap">{if $search_prefilled.sort_field eq "productcode"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=productcode&amp;sort_direction={if $search_prefilled.sort_field eq "productcode"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_sku}</a></td>
	  <td width="*" nowrap="nowrap">{if $search_prefilled.sort_field eq "title"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=title&amp;sort_direction={if $search_prefilled.sort_field eq "title"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_product}</a></td>
	  <td width="30%">{$lng.lbl_cidev_filters}</td>
	</tr>

	{section name=prod loop=$products}

	<tr{cycle values=', class="TableSubHead"'}>
	  <td width="5" align="center">
		<input type="checkbox" name="productids[{$products[prod].productid}]" />
	  </td>
	  <td><a href="product_modify.php?productid={$products[prod].productid}#section_cidev_filter">{$products[prod].productcode}</a></td>
	  <td width="*">
	  <b><a href="product_modify.php?productid={$products[prod].productid}#section_cidev_filter">{$products[prod].product}</a></b>
	  </td>
	  <td width="30%">

		{if $cidev_filters_tree ne "" && $products[prod].cidev_filter_products ne ""}
			{foreach from=$cidev_filters_tree item=v key=k}
				{assign var="filter_name_is_shown" value=""}
				{foreach from=$products[prod].cidev_filter_products item=vv key=kk}
					{if $v.f_id eq $vv.f_id}
						{if $filter_name_is_shown ne "Y"}
							{if $v.f_id eq $f_id}<B>{/if}{$v.f_name}:{if $v.f_id eq $f_id}</B>{/if}
							{assign var="filter_name_is_shown" value="Y"}
						{/if}

						{$vv.fv_name};
					{/if}
				{/foreach}
				{if $filter_name_is_shown eq "Y"}
				<br />
				{/if}
			{/foreach}
		{/if}
	  </td>

	</tr>

	{/section}

	</table>

	  <br />

  {include file="customer/main/navigation.tpl"}

  <br />
        <input type="button" value="{$lng.lbl_cidev_multiple_filter_values_add|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm(lbl_cidev_multiple_filter_values_add)) submitForm(this, 'add_to_products');" />

        <input type="button" value="{$lng.lbl_cidev_multiple_filter_values_replace|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm(lbl_cidev_multiple_filter_values_replace)) submitForm(this, 'replace_from_products');" />

        <input type="button" value="{$lng.lbl_cidev_multiple_filter_values_delete|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm(lbl_cidev_multiple_filter_values_delete)) submitForm(this, 'delete_from_products');" />

  {/if}

  </td>
  </tr>

  </table>
  </form>

{/if}

{/capture}
{include file="dialog.tpl" title=$lng.lbl_cidev_search_by_filter content=$smarty.capture.dialog extra='width="100%"'}
