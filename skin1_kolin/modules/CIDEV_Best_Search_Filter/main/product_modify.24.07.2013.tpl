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

<form action="servletX" method="post" id="form">
 <input type="button" value="+" id="plus" />
 <table>
  <tr>
    <td>
	<select name="filter_name_id[0]" id="filter_name_id_0">
	<option value="">{$lng.lbl_select} {$lng.lbl_cidev_filter_name}</option>
	{foreach from=$cidev_filters_tree item=filter key=filter_key}
	<option value="{$filter.f_id}">{$filter.f_name}</option>
	{/foreach}
	</select>
    </td>

    <td>
        <select name="TEST_filter_name_id[0]" id="TEST_filter_name_id_0">
        <option value="">{$lng.lbl_select} {$lng.lbl_cidev_filter_name}</option>
        {foreach from=$cidev_filters_tree item=filter key=filter_key}
        <option value="{$filter.f_id}">{$filter.f_name}</option>
        {/foreach}
        </select>
    </td>

  </tr>
<!-- кнопка сабмита здесь-->
 </table>
</form>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
$(function(){
  var 	form = $("#form table"),
	plus = $("#plus"),
	clon1 = $("select[name='filter_name_id[0]']"),
	clon2 = $("select[name='TEST_filter_name_id[0]']"),
	clonP = $("select[name='filter_name_id[0]']").parent().parent();	/* Если убрать один из .parent(), то примет не <tr>,а <td> вместе с содержимым*/

  var   idClon1 = $(clon1).attr("id");
  var   idClon2 = $(clon2).attr("id");

  var 	i=0;

  $(plus).on("click", function(){
	var clones = $(clonP).clone();
	i+=1;

	clones.find("#"+idClon1).attr("name", "filter_name_id["+i+"]");
	clones.find("#"+idClon2).attr("name", "TEST_filter_name_id["+i+"]");

        clones.find("#"+idClon1).attr("id", "filter_name_id_"+i);
        clones.find("#"+idClon2).attr("id", "TEST_filter_name_id_"+i);

	$(form).find("tr:last").after(clones);
  });
});

{/literal}
-->
</script>

<br />
<br />

<form name="cidev_product_filter_form" method="post">

<input type="hidden" name="mode" value="cidev_filter_modify" />


<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

        var n=2;
	var n_plus=3;

	function filter_minus(del_div_id){

alert(del_div_id);
		document.getElementById('cidev_filter_fields_'+del_div_id).innerHTML = "";
	}

        function filter_plus(){
//        document.getElementById('cidev_filter_fields').innerHTML+='<tr><td><select name="cidev_filter_name_'+n+'" id="cidev_filter_name_'+n+'"><option value="">'+{/literal}{$lng.lbl_select} {$lng.lbl_cidev_filter_name}{literal}+'</option>'+{/literal}{foreach from=$cidev_filters_tree item=filter key=filter_key}{literal}+'<option value="'+{/literal}{$filter.f_id}{literal}+'">'+{/literal}{$filter.f_name}{literal}+'</option>'+{/literal}{/foreach}{literal}+'</select></td><td></td></tr>';
	        document.getElementById('cidev_filter_fields_'+n).innerHTML += '<table><tr><td><input type=button onclick="filter_minus('+n+');" value="-"></td><td><select name="cidev_filter_name_'+n+'" id="cidev_filter_name_'+n+'"><option value="">{/literal}{$lng.lbl_select} {$lng.lbl_cidev_filter_name}{literal}</option>{/literal}{foreach from=$cidev_filters_tree item=filter key=filter_key}{literal}<option value="{/literal}{$filter.f_id}{literal}">{/literal}{$filter.f_name}{literal}</option>{/literal}{/foreach}{literal}</select></td><td></td></tr></table><div id="cidev_filter_fields_'+n_plus+'"></div>';
        	n++;
		n_plus++;
        }

{/literal}
-->
</script>


<div id="cidev_filter_fields_1">
<table>
<tr>
<td>
<select name="cidev_filter_name_1" id="cidev_filter_name_1">
<option value="">{$lng.lbl_select} {$lng.lbl_cidev_filter_name}</option>
{foreach from=$cidev_filters_tree item=filter key=filter_key}
<option value="{$filter.f_id}">{$filter.f_name}</option>
{/foreach}
</select>
</td>
<td>
</td>
</tr>
</table>
</div>
<div id="cidev_filter_fields_2">
</div>

<table>
<tr>
<td colspan="2">
	<input type=button onclick='filter_plus();' value="+">
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

