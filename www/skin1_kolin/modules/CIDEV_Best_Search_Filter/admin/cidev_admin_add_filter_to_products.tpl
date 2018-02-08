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

function cidev_scrollTop(){
        $("#brands").scrollTop(0);
        $("#manufacturers").scrollTop(0);
        $("#providers").scrollTop(0);
}

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

function func_clear_filter(obj){

        var id;
        id = obj.id.replace("clear_filter_","");

        $("#filter_name_id_"+id).val("");
        $('#filter_value_id_'+id).each(function() {
                $('#filter_value_id_'+id+' option').remove();
        });
        $('#filter_value_id_'+id)
         .append($("<option></option>")
         .attr("value", '')
         .text('{/literal}{$lng.lbl_select} {$lng.lbl_cidev_filter_value}{literal}')); 
}

function func_minus_filter_all(form, prefix){

        var reg = new RegExp("^"+prefix, "");

        var i_keys = new Array();

        j = 0;

        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].type == "select-one" && form.elements[i].name.search(reg) == 0){
                        var i_key = form.elements[i].name.match(/\[(.*?)\]/);
                        if (i_key){
                                i_key = i_key[0];
                                i_key = i_key.replace(/[\[\]]/g, '');
                                i_keys[j] = i_key;
                                j++;
                        }
                }
        }

        for (var k = 0; k < i_keys.length; k++) {
                var del_row_id = 'cidev_add_filter_row_' + i_keys[k];
                if (document.getElementById(del_row_id) && k > 0){
                        $("#"+del_row_id).remove();
                }
        }

        $("#filter_name_id_0").val("");
        $('#filter_value_id_0').each(function() {
                $('#filter_value_id_0 option').remove();
        });
        $('#filter_value_id_0')
         .append($("<option></option>")
         .attr("value", '')
         .text('{/literal}{$lng.lbl_select} {$lng.lbl_cidev_filter_value}{literal}')); 
}


function func_minus_filter(obj){
        var id;
        id = obj.id.replace("minus_filter_","");
        $("#cidev_add_filter_row_"+id).remove();
}

$(function(){

   var   i=100;

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

        new_cloned_row.find("#clear_filter_0").attr("id", "clear_filter_"+i);
        new_cloned_row.find("#div_clear_filter_0").attr("id", "div_clear_filter_"+i);

        new_cloned_row.find("#div_plus_filter").remove();
	new_cloned_row.find("#div_minus_filter_all").remove();

        $("#cidev_add_filter_table").find("tr:last").after(new_cloned_row);
   });
});

function cidev_start(){
        $("#filter_name_id_0").val("");
	cidev_scrollTop();
}

{/literal}
-->
</script>
{/if}


{if $search_prefilled.sorted_filter_values_id eq ""}
<script type="text/javascript">
<!--
{literal}
$(document).ready(function() {
        window.onload = cidev_start();
});

{/literal}
-->
</script>
{else}
<script type="text/javascript">
<!--
{literal}
$(document).ready(function() {
        window.onload = cidev_scrollTop();
});

{/literal}
-->
</script>
{/if}


{if $mode ne "search" or $products eq ""}

{include file="main/multirow.tpl"}
{include file="main/include_js.tpl" src="reset.js"}
<script type="text/javascript">
<!--
var searchform_def = [
    ['posted_data[category_main]', true],
    ['posted_data[search_in_subcategories]', true],
    ['posted_data[by_title]', true],
    ['posted_data[by_shortdescr]', true],
    ['posted_data[by_fulldescr]', true],
    ['posted_data[by_keywords]', true],
    ['posted_data[price_min]', '{$zero}'],
    ['posted_data[avail_min]', '0'],
    ['posted_data[weight_min]', '{$zero}'],
    ['posted_data[discount_slope]', ''],
    ['posted_data[by_froogle_title]', true],
    ['posted_data[empty_froogle_title]', false],
    ['posted_data[no_thumbnail]', false],
    ['posted_data[no_product_image]', false],
    ['posted_data[no_detailed_images]', false],
    ['posted_data[broken_images]', false],
    ['posted_data[outdated_discount_table]', false],
    ['posted_data[categoryid]', ''],
    ['posted_data[substring]', ''],
    ['posted_data[productid]', ''],
    ['posted_data[provider]', ''],
    ['posted_data[froogle_differs]', false],
    ['posted_data[date_period]', '{$search_prefilled.date_period}'],
    ['StartDay', '{$search_prefilled.start_date|default:$smarty.now|date_format:"%d"}'],
    ['StartMonth', '{$search_prefilled.start_date|default:$smarty.now|date_format:"%m"}'],
    ['StartYear', '{$search_prefilled.start_date|default:$smarty.now|date_format:"%Y"}'],
    ['EndDay', '{$search_prefilled.end_date|default:$smarty.now|date_format:"%d"}'],
    ['EndMonth', '{$search_prefilled.end_date|default:$smarty.now|date_format:"%m"}'],
    ['EndYear', '{$search_prefilled.end_date|default:$smarty.now|date_format:"%Y"}']
];

{if $current_area ne "C"}
var extraSkuRows = [ 
{section name="extra_sku_array" loop=$search_prefilled.extra_sku}
    [{$smarty.section.extra_sku_array.index},"{$search_prefilled.extra_sku[$smarty.section.extra_sku_array.index]}"],               
{/section}
];

var extraSkuCount = {$smarty.section.extra_sku_array.total};
{/if}   

{literal}
function managedate(status) {
        var fields = ['StartDay','StartMonth','StartYear','EndDay','EndMonth','EndYear'];
        
        for (i in fields) {
                if (document.searchform.elements[fields[i]]) {
                        document.searchform.elements[fields[i]].disabled = status;
        }
    }
}
{/literal}

-->
</script>


<form action="cidev_admin_add_filter_to_products.php" method="post" name="searchform">
<input type="hidden" name="mode" value="search" />
<input type="hidden" name="filter_mode" value="search" />
<input type="hidden" name="froogle_titles" value="N" />
{if $usertype eq "P"}
<input type="hidden" name="posted_data[flag_ship_freight]" value=""/>
{/if}

<table cellpadding="1" cellspacing="5" width="100%">

{*
<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_search_in_category_id}:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
        <input name="posted_data[categoryid]" value="{$search_prefilled.categoryid}" style="width: 70%;" />
        </td>
</tr>

<tr>
        <td colspan="2" width="10" height="10">&nbsp;</td>
        <td height="10">
<table cellpadding="0" cellspacing="0">
<tr>
        <td width="5" nowrap="nowrap">{$lng.lbl_as}&nbsp;&nbsp;</td>
        <td width="5"><input type="checkbox" id="posted_data_category_main" name="posted_data[category_main]"{if $search_prefilled eq "" or $search_prefilled.category_main} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_category_main">{$lng.lbl_main_category}</label>&nbsp;&nbsp;</td>
        <td width="5"><input type="checkbox" id="posted_data_category_extra" name="posted_data[category_extra]"{if $search_prefilled.category_extra} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_category_extra">{$lng.lbl_additional_category}</label></td>
</tr>
</table>
        </td>
</tr>

<tr>
        <td colspan="2" width="10" height="10">&nbsp;</td>
        <td height="10">
<table cellpadding="0" cellspacing="0">
<tr>
        <td width="5"><input type="checkbox" id="posted_data_search_in_subcategories" name="posted_data[search_in_subcategories]"{if $search_prefilled eq "" or $search_prefilled.search_in_subcategories} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_search_in_subcategories">{$lng.lbl_search_in_subcategories}</label></td>
</tr>
</table>
        </td>
</tr>
*}
<input type="hidden" name="posted_data[categoryid]" value="">
<input type="hidden" name="posted_data[category_extra]" value="">
<input type="hidden" name="posted_data[category_main]" value="">
<input type="hidden" name="posted_data[search_in_subcategories]" value="">

<tr>
        <td height="10" width="25%" class="FormButton" nowrap="nowrap">{$lng.lbl_sku}:</td>
        <td width="10" height="10">&nbsp;</td>
    <td height="10" width="80%" id="skuRow">
                <script type="text/javascript">
                <!--
                {literal}
                        $('input[name^="posted_data[extra_sku]"]').on('keydown', function () {
//                                reset_form('searchform', searchform_def);
                        });
                {/literal}
                -->
                </script>
        {if $current_area eq "C"}
            <input type="text" maxlength="64" name="posted_data[productcode]" value="{$search_prefilled.productcode|escape}" style="width:70%" /><br/>
        {else}
        <table width="70%" border="0" cellpadding="0" cellspacing="0">

            <tr id="sku_row_0">
                <td id="sku_box_1" align="left" width="90%">
                    <input 
                        name="posted_data[extra_sku][0]"  
                        type="text" 
                        style="width: 98%;"
			value="{$search_prefilled.extra_sku[0]}"
                    >
                </td>
                <td align="left" width="10%">
                    {include file="buttons/multirow_add.tpl" mark="sku" is_lined=false}
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            for (var i = 1 ; i < extraSkuCount; i++ ) {ldelim}
                add_inputset("sku",document.getElementById("sku_box_1"),false);
            {rdelim}
            for (var i = 0 ; i < extraSkuCount; i++ ) {ldelim}
                var obj = document.getElementsByName("posted_data[extra_sku]["+i+"]");//.value=extraSkuRows[i][1];
                obj[0].value = extraSkuRows[i][1];
            {rdelim}
        </script>

                {/if}
    </td>
</tr>

<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_search_for_pattern}:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10" width="80%">
<input type="text" name="posted_data[substring]" size="30" style="width:70%" value="{$search_prefilled.substring}" />
{*
&nbsp;
<input type="submit" value="{$lng.lbl_search|strip_tags:false|escape}" />
*}
        </td>
</tr>

{if $config.General.allow_search_by_words eq 'Y'}
<tr>
<td height="10" colspan="2"></td>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
        <td width="5"><input type="radio" name="posted_data[including]" value="all"{if $search_prefilled eq "" or $search_prefilled.including eq '' or $search_prefilled.including eq 'all'} checked="checked"{/if} /></td>
        <td nowrap="nowrap">all words&nbsp;&nbsp;</td>

{*
        <td width="5"><input type="radio" name="posted_data[including]" value="any"{if $search_prefilled.including eq 'any'} checked="checked"{/if} /></td>
        <td nowrap="nowrap">{$lng.lbl_any_word}&nbsp;&nbsp;</td>
*}
        <td width="5"><input type="radio" name="posted_data[including]" value="phrase"{if $search_prefilled.including eq 'phrase'} checked="checked"{/if} /></td>
        <td nowrap="nowrap">"exact phrase"&nbsp;&nbsp;</td>
        <td width="5"><input type="checkbox" id="posted_data_by_title" name="posted_data[by_title]"{if $search_prefilled eq "" or $search_prefilled.by_title} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_by_title">{$lng.lbl_product_title}</label>&nbsp;&nbsp;</td>
        <td width="5"><input type="checkbox" id="posted_data_by_fulldescr" name="posted_data[by_fulldescr]"{if $search_prefilled eq "" or $search_prefilled.by_fulldescr} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_by_fulldescr">{$lng.lbl_det_description}</label>&nbsp;&nbsp;</td>
</tr>
</table>
</td>
</tr>
{/if}

{*
<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_search_in}:</td>
        <td width="10" height="10">&nbsp;</td>
        <td>
<table cellpadding="0" cellspacing="0">
<tr>
        <td width="5"><input type="checkbox" id="posted_data_by_title" name="posted_data[by_title]"{if $search_prefilled eq "" or $search_prefilled.by_title} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_by_title">{$lng.lbl_product_title}</label>&nbsp;&nbsp;</td>
        <td width="5"><input type="checkbox" id="posted_data_by_shortdescr" name="posted_data[by_shortdescr]"{if $search_prefilled eq "" or $search_prefilled.by_shortdescr} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_by_shortdescr">{$lng.lbl_short_description}</label>&nbsp;&nbsp;</td>
        <td width="5"><input type="checkbox" id="posted_data_by_fulldescr" name="posted_data[by_fulldescr]"{if $search_prefilled eq "" or $search_prefilled.by_fulldescr} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_by_fulldescr">{$lng.lbl_det_description}</label>&nbsp;&nbsp;</td>
        <td width="5"><input type="checkbox" id="posted_data_by_keywords" name="posted_data[by_keywords]"{if $search_prefilled eq "" or $search_prefilled.by_keywords} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_by_keywords">{$lng.lbl_keywords}</label>&nbsp;&nbsp;</td>
</tr>
<tr>
        <td width="5"><input type="checkbox" id="posted_data_by_froogle_title" name="posted_data[by_froogle_title]"{if $search_prefilled eq "" or $search_prefilled.by_froogle_title} checked="checked"{/if} /></td>
        <td nowrap="nowrap"><label for="posted_data_by_froogle_title">{$lng.lbl_product_name_froogle}</label>&nbsp;&nbsp;</td>
        <td colspan="5">&nbsp;</td>

</tr>
</table>
        </td>
</tr>
*}

{if $active_modules.Extra_Fields && $extra_fields ne ''}
<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_search_also_in}:</td>
        <td width="10" height="10">&nbsp;</td>
        <td>
<table cellpadding="0" cellspacing="0">
{foreach from=$extra_fields item=v}
<tr>
        <td width="5"><input type="checkbox" id="posted_data_extra_fields_{$v.fieldid}" name="posted_data[extra_fields][{$v.fieldid}]"{if $v.selected eq "Y"} checked="checked"{/if} /></td>
        <td><label for="posted_data_extra_fields_{$v.fieldid}">{$v.field}</label></td>
</tr>
{/foreach}
</table>
        </td>
</tr>
{/if}

</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_availability}:</td>
        <td height="10"></td>
        <td height="10">
        <select name="posted_data[forsale]">
{*                <option value=""></option> *}
                <option value="Y"{if $search_prefilled.forsale eq "Y"} selected="selected"{/if}>{$lng.lbl_avail_for_sale}</option>
{*              <option value="H"{if $product.forsale eq "H"} selected="selected"{/if}>{$lng.lbl_hidden}</option> *}
                <option value="N"{if $search_prefilled.forsale eq "N"} selected="selected"{/if}>{$lng.lbl_disabled}</option>
{if $active_modules.Product_Configurator}
                <option value="B"{if $search_prefilled.forsale eq "B"} selected="selected"{/if}>{$lng.lbl_bundled}</option>
{/if}
		<option value="">Any</option>
        </select>
        </td>
</tr>

<tr>
        <td colspan="2"></td>
        <td>
        <hr />
{*
<table cellpadding="0" cellspacing="0">
<tr>
        <td><input type="checkbox" value='Y' id="posted_data_is_modify" name="posted_data[is_modify]" /></td>
        <td>&nbsp;</td>
        <td height="10" class="FormButton" nowrap="nowrap"><label for="posted_data_is_modify">{$lng.lbl_search_and_modify}</label></td>
</tr>
</table>
*}
        </td>
</tr>

<tr>
        <td colspan="2"></td>
        <td>
<table cellpadding="0" cellspacing="0">
<tr>
        <td><input type="checkbox" id="posted_data_is_export" name="posted_data[is_export]" value="Y" /></td>
        <td>&nbsp;</td>
        <td class="FormButton" nowrap="nowrap"><label for="posted_data_is_export">{$lng.lbl_search_and_export}</label></td>
</tr>
</table>
        </td>
</tr>

<tr>
        <td colspan="2"></td>
        <td><input type="submit" value="{$lng.lbl_search|strip_tags:false|escape}" /></td>
</tr>

</table>

<br />

<table>
<tr>
        <td id="close1" style="cursor: hand;" onclick="visibleBox('1')"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_click_to_open|escape}" /></td>
        <td id="open1" style="display: none; cursor: hand;" onclick="visibleBox('1')"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_click_to_close|escape}" /></td>
        <td><a href="javascript:void(0);" onclick="visibleBox('1')"><b>{$lng.lbl_advanced_search_options}</b></a></td>
</tr>
</table>

<br />

<table cellpadding="0" cellspacing="0" width="100%" style="display: none;" id="box1">
<tr>
        <td>

<table cellpadding="1" cellspacing="5" width="100%">

<tr>
        <td colspan="3"><br />{include file="main/subheader.tpl" title=$lng.lbl_advanced_search_options}</td>
</tr>

{if $active_modules.Manufacturers && $manufacturers ne ''}
{capture name=manufacturers_items}
{section name=mnf loop=$manufacturers}
                <option value="{$manufacturers[mnf].manufacturerid}"{if $manufacturers[mnf].selected eq 'Y'} selected="selected"{/if}>{$manufacturers[mnf].manufacturer}</option>
{/section}
{/capture}
<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_manufacturers}:</td>
        <td height="10"></td>
        <td height="10">
        <select name="posted_data[manufacturers][]" style="width:70%" multiple="multiple" size="{if $smarty.section.mnf.total gt 5}5{else}{$smarty.section.mnf.total}{/if}">
{$smarty.capture.manufacturers_items}
        </select>
        </td>
</tr>
{/if}

{if $active_modules.Brands && $brands ne ''}
{capture name=brands_items}
{section name=mnf loop=$brands}
                <option value="{$brands[mnf].brandid}"{if $brands[mnf].selected eq 'Y'} selected="selected"{/if}>{$brands[mnf].brand}</option>
{/section}
{/capture}
<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_brands}:</td>
        <td height="10"></td>
        <td height="10">
        <select name="posted_data[brands][]" style="width:70%" multiple="multiple" size="{if $smarty.section.mnf.total gt 5}5{else}{$smarty.section.mnf.total}{/if}">
{$smarty.capture.brands_items}
        </select>
        </td>
</tr>
{/if}

{*
<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_productid}#:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10" width="80%"><input type="text" maxlength="64" name="posted_data[productid]" value="{$search_prefilled.productid}" style="width:70%" /></td>
</tr>
*}

<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_price} ({$config.General.currency_symbol}):</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10" width="80%">
<table cellpadding="0" cellspacing="0">
<tr>
        <td><input type="text" size="10" maxlength="15" name="posted_data[price_min]" value="{if $search_prefilled eq ""}{$zero}{else}{$search_prefilled.price_min|formatprice}{/if}" /></td>
        <td>&nbsp;-&nbsp;</td>
        <td><input type="text" size="10" maxlength="15" name="posted_data[price_max]" value="{$search_prefilled.price_max|formatprice}" /></td>
</tr>
</table>
        </td>
</tr>

<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_quantity}:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10" width="80%">
<table cellpadding="0" cellspacing="0">
<tr>
        <td><input type="text" size="10" maxlength="10" name="posted_data[avail_min]" value="{if $search_prefilled eq ""}0{else}{$search_prefilled.avail_min}{/if}" /></td>
        <td>&nbsp;-&nbsp;</td>
        <td><input type="text" size="10" maxlength="10" name="posted_data[avail_max]" value="{$search_prefilled.avail_max}" /></td>
</tr>
</table>
        </td>
</tr>

<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_weight} ({$config.General.weight_symbol}):</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10" width="80%">
<table cellpadding="0" cellspacing="0">
<tr>
        <td><input type="text" size="10" maxlength="10" name="posted_data[weight_min]" value="{if $search_prefilled eq ""}{$zero}{else}{$search_prefilled.weight_min|formatprice}{/if}" /></td>
        <td>&nbsp;-&nbsp;</td>
        <td><input type="text" size="10" maxlength="10" name="posted_data[weight_max]" value="{$search_prefilled.weight_max|formatprice}" /></td>
</tr>
</table>
        </td>
</tr>

{if $usertype ne 'C' && $usertype ne 'B' && $active_modules.Feature_Comparison && $fclasses ne ''}
<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_product_feature_classes}:</td>
        <td height="10"></td>
        <td height="10">
        <select name="posted_data[fclassid]" style="width:70%">
                <option value=""></option>
{foreach from=$fclasses item=v}
                <option value="{$v.fclassid}"{if $search_prefilled.fclassid eq $v.fclassid} selected="selected"{/if}>{$v.class}</option>
{/foreach}
        </select>
        </td>
</tr>
{/if}

{if $usertype eq "A"}
<tr>
        <td colspan="3"><br />{include file="main/subheader.tpl" title=$lng.lbl_added_on_date_by_operator|escape class="grey"}</td>
</tr>

<tr>
        <td class="FormButton" nowrap="nowrap" width="25%">{$lng.lbl_date_period}:</td>
        <td width="10">&nbsp;</td>
        <td>
<table cellpadding="0" cellspacing="0">
<tr>
        <td width="5"><input type="radio" id="date_period_D" name="posted_data[date_period]" value="D"{if $search_prefilled.date_period eq "D"} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
        <td class="OptionLabel"><label for="date_period_D">{$lng.lbl_today}</label></td>
        <td width="5"><input type="radio" id="date_period_W" name="posted_data[date_period]" value="W"{if $search_prefilled.date_period eq "W"} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
        <td class="OptionLabel"><label for="date_period_W">{$lng.lbl_this_week}</label></td>
        <td width="5"><input type="radio" id="date_period_M" name="posted_data[date_period]" value="M"{if $search_prefilled.date_period eq "M"} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
        <td class="OptionLabel"><label for="date_period_M">{$lng.lbl_this_month}</label></td>
        <td width="5"><input type="radio" id="date_period_null" name="posted_data[date_period]" value=""{if $search_prefilled eq "" or $search_prefilled.date_period eq ""} checked="checked"{/if} onclick="javascript:managedate(true)" /></td>
        <td class="OptionLabel"><label for="date_period_null">{$lng.lbl_all_dates}</label></td>
</tr>
<tr>
        <td width="5"><input type="radio" id="date_period_C" name="posted_data[date_period]" value="C"{if $search_prefilled.date_period eq "C"} checked="checked"{/if} onclick="javascript:managedate(false)" /></td>
        <td colspan="7" class="OptionLabel"><label for="date_period_C">{$lng.lbl_specify_period_below}</label></td>
</tr>
</table>
</td>
</tr>

<tr>
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_date_from}:</td>
        <td width="10">&nbsp;</td>
        <td>
        {html_select_date prefix="Start" time=$search_prefilled.start_date start_year=$config.Company.start_year end_year=$config.Company.end_year}
        </td>
</tr>

<tr>
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_date_through}:</td>
        <td width="10">&nbsp;</td>
        <td>
        {html_select_date prefix="End" time=$search_prefilled.end_date start_year=$config.Company.start_year end_year=$config.Company.end_year display_days=yes}
        </td>
</tr>

{if $providers}
<tr>
        <td height="10" width="20%" class="FormButton" nowrap="nowrap">{$lng.lbl_operators}:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10" width="80%">
        <select name="posted_data[providers][]" multiple="multiple">
        {foreach from=$providers item="provider"}
            <option value="{$provider.login}"{if $search_prefilled.providers[$provider.login]} selected="selected"{/if}>{$provider.login} ({$provider.firstname} {$provider.lastname})</option>
        {/foreach}
        </select>
    </td>
</tr>
{/if}
{/if}


<tr>
        <td colspan="3"><br />{include file="main/subheader.tpl" title=$lng.lbl_discount_table_options class="grey"}</td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_empty_discount_slope}:</td>
        <td height="10"></td>
        <td height="10"><input name="posted_data[empty_discount_slope]" value="Y" type="checkbox"{if $search_prefilled.empty_discount_slope eq "Y"} checked="checked"{/if} onclick="visibleBox('2',true)" /></td>
</tr>

<tbody{if $search_prefilled.empty_discount_slope eq "Y"} style="display: none;"{/if} id="box2">

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_discount_slope}:</td>
        <td height="10"></td>
        <td height="10"><input name="posted_data[discount_slope]" type="text" value="{if $search_prefilled eq ""}{$zero}{else}{$search_prefilled.discount_slope|formatprice}{/if}"  size="10" maxlength="10" /></td>
</tr>
<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_discount_table}:</td>
        <td height="10"></td>
        <td height="10"><input name="posted_data[discount_table]" type="text" value="{$search_prefilled.discount_table}" class="InputWidth" /></td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_outdated_discount_table}:</td>
        <td height="10"></td>
        <td height="10"><input name="posted_data[outdated_discount_table]" value="Y" type="checkbox"{if $search_prefilled.outdated_discount_table eq "Y"} checked="checked"{/if} /></td>
</tr>

</tbody>

<tr>
        <td colspan="3"><br />{include file="main/subheader.tpl" title=$lng.lbl_additional_options class="grey"}</td>
</tr>
</table>

<table cellpadding="1" cellspacing="5">
<tr>
        <td class="FormButton">{$lng.lbl_free_shipping}:&nbsp;</td>
        <td>
        <select name="posted_data[flag_free_ship]">
                <option value=""></option>
                <option value="Y"{if $search_prefilled.flag_free_ship eq "Y"} selected="selected"{/if}>{$lng.lbl_assigned}</option>
                <option value="N"{if $search_prefilled.flag_free_ship eq "N"} selected="selected"{/if}>{$lng.lbl_not_assigned}</option>
        </select>
&nbsp;&nbsp;
</td>
{if $usertype ne 'P'}
        <td class="FormButton">{$lng.lbl_shipping_freight}:&nbsp;</td>
        <td>
        <select name="posted_data[flag_ship_freight]">
                <option value=""></option>
                <option value="Y"{if $search_prefilled.flag_ship_freight eq "Y"} selected="selected"{/if}>{$lng.lbl_assigned}</option>
                <option value="N"{if $search_prefilled.flag_ship_freight eq "N"} selected="selected"{/if}>{$lng.lbl_not_assigned}</option>
        </select>
        </td>
{/if}

        <td class="FormButton">{$lng.lbl_global_discounts}:&nbsp;</td>
        <td>
        <select name="posted_data[flag_global_disc]">
                <option value=""></option>
                <option value="Y"{if $search_prefilled.flag_global_disc eq "Y"} selected="selected"{/if}>{$lng.lbl_assigned}</option>
                <option value="N"{if $search_prefilled.flag_global_disc eq "N"} selected="selected"{/if}>{$lng.lbl_not_assigned}</option>
        </select>
&nbsp;&nbsp;
        </td>
</tr>
<tr>
        <td class="FormButton">{$lng.lbl_tax_exempt}:&nbsp;</td>
        <td>
        <select name="posted_data[flag_free_tax]">
                <option value=""></option>
                <option value="Y"{if $search_prefilled.flag_free_tax eq "Y"} selected="selected"{/if}>{$lng.lbl_assigned}</option>
                <option value="N"{if $search_prefilled.flag_free_tax eq "N"} selected="selected"{/if}>{$lng.lbl_not_assigned}</option>
        </select>
        </td>
        
	<td class="FormButton">{$lng.lbl_min_order_amount}:&nbsp;</td>
        <td>
        <select name="posted_data[flag_min_amount]">
                <option value=""></option>
                <option value="Y"{if $search_prefilled.flag_min_amount eq "Y"} selected="selected"{/if}>{$lng.lbl_assigned}</option>
                <option value="N"{if $search_prefilled.flag_min_amount eq "N"} selected="selected"{/if}>{$lng.lbl_not_assigned}</option>
        </select>
&nbsp;&nbsp;
        </td>
        <td class="FormButton">{$lng.lbl_lowlimit_in_stock}:&nbsp;</td>
        <td>
        <select name="posted_data[flag_low_avail_limit]">
                <option value=""></option>
                <option value="Y"{if $search_prefilled.flag_low_avail_limit eq "Y"} selected="selected"{/if}>{$lng.lbl_assigned}</option>
                <option value="N"{if $search_prefilled.flag_low_avail_limit eq "N"} selected="selected"{/if}>{$lng.lbl_not_assigned}</option>
        </select>
        </td>
</tr>
<tr>
        <td class="FormButton">{$lng.lbl_list_price}:&nbsp;</td>
        <td>
        <select name="posted_data[flag_list_price]">
                <option value=""></option>
                <option value="Y"{if $search_prefilled.flag_list_price eq "Y"} selected="selected"{/if}>{$lng.lbl_assigned}</option>
                <option value="N"{if $search_prefilled.flag_list_price eq "N"} selected="selected"{/if}>{$lng.lbl_not_assigned}</option>
        </select>
&nbsp;&nbsp;
        </td>
        <td colspan="4">&nbsp;</td>
</tr>

</table>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
        <td colspan="3"><br />{include file="main/subheader.tpl" title=$lng.lbl_custom_options class="grey"}</td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_find_multisku_only}:</td>
        <td height="10"></td>
        <td height="10" width="80%"><input name="posted_data[duplicate_sku]" value="Y" type="checkbox"{if $search_prefilled.duplicate_sku eq "Y"} checked="checked"{/if} /></td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_empty_froogle_title}:</td>
        <td height="10"></td>
        <td height="10" width="80%"><input name="posted_data[empty_froogle_title]" value="Y" type="checkbox"{if $search_prefilled.empty_froogle_title eq "Y"} checked="checked"{/if} /></td>
</tr>
<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_froogle_differs}:</td>
        <td height="10"></td>
        <td height="10" width="80%"><input name="posted_data[froogle_differs]" value="Y" type="checkbox"{if $search_prefilled.froogle_differs eq "Y"} checked="checked"{/if} /></td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_no_thumbnail}:</td>
        <td height="10"></td>
        <td height="10" width="80%"><input name="posted_data[no_thumbnail]" value="Y" type="checkbox"{if $search_prefilled.no_thumbnail eq "Y"} checked="checked"{/if} /></td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_no_product_image}:</td>
        <td height="10"></td>
        <td height="10" width="80%"><input name="posted_data[no_product_image]" value="Y" type="checkbox"{if $search_prefilled.no_product_image eq "Y"} checked="checked"{/if} /></td>
</tr>

{if $active_modules.Detailed_Product_Images ne ""}
<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_no_detailed_images}:</td>
        <td height="10"></td>
        <td height="10" width="80%"><input name="posted_data[no_detailed_images]" value="Y" type="checkbox"{if $search_prefilled.no_detailed_images eq "Y"} checked="checked"{/if} /></td>
</tr>
{/if}

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_products_with_broken_images}:</td>
        <td height="10"></td>
        <td height="10" width="80%"><input name="posted_data[broken_images]" value="Y" type="checkbox"{if $search_prefilled.broken_images eq "Y"} checked="checked"{/if} /></td>
</tr>



{if $cidev_filters_tree ne ""}
<tr>
<td colspan="3">
<br />
{include file="main/subheader.tpl" title=$lng.lbl_cidev_filter_name class="grey"}

 <table id="cidev_add_filter_table">

 {if $search_prefilled.sorted_filter_values_id ne ""}

        {assign var="tmp_filter_id" value="0"}

        {foreach from=$search_prefilled.sorted_filter_values_id item=item key=key}

{foreach from=$item item=item_fv_id}
  <tr id="cidev_add_filter_row_{$tmp_filter_id}">
    <td>
        <select name="filter_name_id[{$tmp_filter_id}]" id="filter_name_id_{$tmp_filter_id}" onchange="func_set_filter_value(this);">
        <option value="">{$lng.lbl_select} {$lng.lbl_cidev_filter_name}</option>
        {foreach from=$cidev_filters_tree item=filter key=filter_key}
        <option value="{$filter.f_id}" {if $key eq $filter.f_id}selected="selected"{/if}>{$filter.f_name}</option>
        {/foreach}
        </select>
    </td>

    <td>
        <select name="filter_value_id[{$tmp_filter_id}]" id="filter_value_id_{$tmp_filter_id}">
        <option value="">{$lng.lbl_select} {$lng.lbl_cidev_filter_value}</option>
        {foreach from=$cidev_filters_tree item=v key=k}
         {if $v.f_id eq $key && $v.filter_values ne ""}
          {foreach from=$v.filter_values item=vv key=kk}
           <option value="{$vv.fv_id}"{if $item_fv_id eq $vv.fv_id}selected="selected"{/if}>{$vv.fv_name}</option>
          {/foreach}
         {/if}
        {/foreach}
        </select>
    </td>

    <td>
        {if $tmp_filter_id eq "0"}
        <div style="float:left;" id="div_plus_filter"><input type="button" value="+" id="plus_filter" /></div>
        {/if}
        <div {if $tmp_filter_id eq "0"}style="display: none; float:left;"{else}style="float:left;"{/if} id="div_minus_filter_{$tmp_filter_id}"><input type="button" value=" - " id="minus_filter_{$tmp_filter_id}" onclick="func_minus_filter(this);" /></div>
        <div style="float:left;" id="div_clear_filter"><input type="button" value="Clear" id="clear_filter_{$tmp_filter_id}" onclick="func_clear_filter(this);" /></div>

        {if $tmp_filter_id eq "0"}
        <div style="float:left;" id="div_minus_filter_all"><input type="button" value="Clear all options" id="div_minus_filter_all" onclick="func_minus_filter_all(document.searchform, 'filter_name_id');" /></div>
        {/if}
    </td>

  </tr>
  {math assign="tmp_filter_id" equation="x+1" x=$tmp_filter_id}
{/foreach}

        {/foreach}

 {else}

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
        <div style="float:left;" id="div_plus_filter"><input type="button" value="+" id="plus_filter" /></div>
        <div style="display: none; float:left;" id="div_minus_filter_0"><input type="button" value=" - " id="minus_filter_0" onclick="func_minus_filter(this);" /></div>
        <div style="float:left;" id="div_clear_filter"><input type="button" value="Clear" id="clear_filter_0" onclick="func_clear_filter(this);" /></div>
        <div style="float:left;" id="div_minus_filter_all"><input type="button" value="Clear all options" id="div_minus_filter_all" onclick="func_minus_filter_all(document.searchform, 'filter_name_id');" /></div>
    </td>

  </tr>
{/if}

 </table>

</td>
</tr>
{/if}


<tr>
        <td colspan="3">&nbsp;</td>
</tr>

<tr>
        <td>&nbsp;</td>
        <td colspan="2" class="SubmitBox">
        <input type="submit" value="{$lng.lbl_search|strip_tags:false|escape}" />
{*        <input type="button" value="{$lng.lbl_reset|strip_tags:false|escape}" onclick="javascript: reset_form('searchform', searchform_def);" /> *}

        <input type="button" value="{$lng.lbl_reset|strip_tags:false|escape}" onclick="javascript: submitForm(document.searchform, 'search_reset');" />

{*
{if $active_modules.Wholesale_Trading}
        <input type="button" value="{$lng.lbl_generate_discounts|strip_tags:false|escape}" onclick="javascript: submitForm(document.searchform, 'search_gen_discounts');" />
        <input type="button" value="{$lng.lbl_improve_froogle_titles|strip_tags:false|escape}" onclick="javascript: document.searchform.froogle_titles.value = 'Y'; document.searchform.submit();" />
{/if}
*}
        {if $search_prefilled.date_period ne "C"}
            <script type="text/javascript" language="JavaScript 1.2">
            <!--
                managedate(true);
            -->
            </script>
        {/if}
        </td>
</tr>

</table>

        </td>
</tr>
</table>

</form>

{if $search_prefilled.need_advanced_options}
<script type="text/javascript" language="JavaScript 1.2">
<!--
visibleBox('1');
-->
</script>
{/if}

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
        <div style="float:left;" id="div_plus_filter"><input type="button" value="+" id="plus_filter" /></div>
        <div style="display: none;" id="div_minus_filter_0"><input type="button" value=" - " id="minus_filter_0" onclick="func_minus_filter(this);" /></div>
        <div style="float:left;" id="div_minus_filter_all"><input type="button" value="Clear all options" id="div_minus_filter_all" onclick="func_minus_filter_all(document.cidev_admin_add_filter_to_products_form2, 'filter_name_id');" /></div>

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
