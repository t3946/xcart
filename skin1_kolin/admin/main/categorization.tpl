

{*
{if $show_recalc_bayes_button eq "Y"}
<br />
<br />
<form name="pc_form2" action="classification.php" method="POST">
<input type="hidden" name="mode" value="">
<input type="button" value="Recalc bayes" onclick="javascript: submitForm(this, 'recalc_bayes');" />
</form>
{/if}
*}

{*
{if $show_check_status_button eq "Y"}
<br />
<br />
<form name="pc_form3" action="classification.php" method="POST">
<input type="hidden" name="mode" value="">
<input type="button" value="Check status" onclick="javascript: submitForm(this, 'check_status');" />
</form>
{/if}
*}


{if $products_minimum_number_of_autoclassify_product_per_turn ne ""}
<br />

{capture name=dialog}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[

var filled_or_not_arr = new Array;
var count_products_minimum_number_of_autoclassify_product_per_turn = {$count_products_minimum_number_of_autoclassify_product_per_turn};
var allow_blank_google_product_category = '{$pc_options.allow_blank_google_product_category}';

{foreach from=$products_minimum_number_of_autoclassify_product_per_turn item=v key=k}
	filled_or_not_arr[{$k}] = "N";
{/foreach}

{literal}

function func_check_google_product_category(){

	if (allow_blank_google_product_category == "Y"){
		return true;
	}
	else {

		var google_product_category_field_0;
		var google_product_category_field_1;

                var val_google_product_category_field_0;
                var val_google_product_category_field_1;

{/literal}
{foreach from=$products_minimum_number_of_autoclassify_product_per_turn item=v key=k}
{literal}
		google_product_category_field_0 = 'google_product_category_{/literal}{$k}{literal}_0';
		google_product_category_field_1 = 'google_product_category_{/literal}{$k}{literal}_1';

		if (document.getElementById(google_product_category_field_0)){

			val_google_product_category_field_0 = $('#'+google_product_category_field_0).val();

			if (val_google_product_category_field_0.trim() == ""){
				return false;
			}
		}

                if (document.getElementById(google_product_category_field_1)){

                        val_google_product_category_field_1 = $('#'+google_product_category_field_1).val();

                        if (val_google_product_category_field_1.trim() == ""){
				return false;
                        }
                }


{/literal}
{/foreach}
{literal}


		return true;
	}
}

function func_check_all_filled_or_not(){

	var counter = 0;

	{/literal}
	{foreach from=$products_minimum_number_of_autoclassify_product_per_turn item=v key=k}
	{literal}
		if (filled_or_not_arr[{/literal}{$k}{literal}] == "Y"){
			counter++;
		}
	{/literal}
	{/foreach}
	{literal}

	if (counter == count_products_minimum_number_of_autoclassify_product_per_turn){
		$('#form1_div_submit_button_id').show();
	} else {
		$('#form1_div_submit_button_id').hide();
	}
}

function func_cancel_approve_button_clicked(code){

	filled_or_not_arr[code] = "N";

        $('#form1_div_approve_button_'+code).show();
        $('#form1_div_skip_button_'+code).show();
	$('#form1_div_correct_categoryid_'+code).show();

	$('#form1_div_cancel_approve_button_'+code).hide();

	func_check_all_filled_or_not();
}

function func_cancel_skip_button_clicked(code){

        filled_or_not_arr[code] = "N";

	$('#form1_skip_id_'+code).val("");

        $('#form1_div_approve_button_'+code).show();
        $('#form1_div_skip_button_'+code).show();
	$('#form1_div_correct_categoryid_'+code).show();

	$('#form1_div_cancel_skip_button_'+code).hide();

        func_check_all_filled_or_not();
}

function func_skip_button_clicked(code){

	filled_or_not_arr[code] = "Y";

	$('#form1_skip_id_'+code).val("Y");

	$('#form1_div_correct_categoryid_'+code).hide();
        $('#form1_div_approve_button_'+code).hide();
        $('#form1_div_skip_button_'+code).hide();

        $('#form1_div_cancel_skip_button_'+code).show();

	func_check_all_filled_or_not();
}

function func_approve_button_clicked(code){

	filled_or_not_arr[code] = "Y";

        $('#form1_div_correct_categoryid_'+code).hide();
	$('#form1_div_approve_button_'+code).hide();
	$('#form1_div_skip_button_'+code).hide();

	$('#form1_div_cancel_approve_button_'+code).show();


/*
        var button_value = $('#form1_approve_button_id_'+code).val();

        if (button_value == "Approve"){
                $('#form1_approve_button_id_'+code).val("Cancel approve");
		filled_or_not_arr[code] = "Y";
        } else {
                $('#form1_approve_button_id_'+code).val("Approve");
		filled_or_not_arr[code] = "N";
        }
*/

	func_check_all_filled_or_not();
}

function func_correct_categoryid_field_filled(code){

	var field_val = $('#form1_correct_categoryid_id_'+code).val();
	field_val = field_val.replace(/[^0-9]/g, '');

	if (field_val == "0" || field_val == ""){
		
		if (field_val == "0"){
			field_val = "";
		}

                filled_or_not_arr[code] = "N";

                $('#form1_div_approve_button_'+code).show();
	        $('#form1_div_skip_button_'+code).show();

	} else {

		filled_or_not_arr[code] = "Y";

		$('#form1_div_approve_button_'+code).hide();
	        $('#form1_div_skip_button_'+code).hide();
	}

	$('#form1_correct_categoryid_id_'+code).val(field_val);

	func_check_all_filled_or_not();
}

{/literal}
//]]>
</script>

{if $category_not_ready_to_classification ne ""}
{foreach from=$category_not_ready_to_classification item=v key=k}
Category {$v} is not ready to classification!<br />
{/foreach}
{/if}


<table width="100%">
<tr>
<td>
<B>Products classified:</B> {$count_ACC_or_MC_products} / {$count_products}<br />
</td>
<td align="right">
<a style="color: blue;" target="_blank" href="classification_log.php?mode=search">Classification log</a>
</td>
</tr>
<tr>
<td>
<B>Categories containing classified products:</B> {$count_pc_cats_with_pr} / {$count_all_pc_cats}
</td>
<td align="right">
{*
<a style="color: blue;" target="_blank" href="cats_no_class_prods.php">Show {$count_cats_with_no_classified_products} categories containing no classified products</a>
*}

<a style="color: blue;" target="_blank" href="category_structure.php">Category structure</a>
</td>
</tr>
</table>

<br />
<br />

<form name="pc_form1" action="classification.php" method="POST">
<input type="hidden" name="mode" value="">
<input type="hidden" name="date_time_start" value="{$date_time_start}">

<table border="0" width="100%" cellpadding="3" cellspacing="1">

<tr class='TableSubHead' >
<td><B>Product name</B></td>
<td><B>Suggested category (products / categoryid)</td>
<td><B>Score</B></td>
<td><B>Delta</B></td>
<td><B>Cat ID</B></td>
<td><B>Action</B></td>
</tr>

{foreach from=$products_minimum_number_of_autoclassify_product_per_turn item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
<td width="350" nowrap="nowrap">
<a href="http://{$current_storefront_info.domain}/product.php?productid={$v.productid}" target="_blank" style="color: blue;">{$v.product}</a>
<input type="hidden" name="posted_data[{$k}][productid]" value="{$v.productid}" />

<input id="form1_skip_id_{$k}" type="hidden" name="posted_data[{$k}][skip]" value="" />
</td>
<td valign="middle" nowrap="nowrap">
{*
{if $v.categoryid_path_arr ne ""}
<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.categoryid}" target="_blank" style="color: blue;">
{foreach from=$v.categoryid_path_arr item=vv key=kk}
{$vv}{if $kk < ($v.categoryid_path_arr_count - 1)} > {/if}
{/foreach}
</a>
{/if}
*}

{if $v.relevant_cats.0.categoryid_path_arr ne ""}
<br />
{foreach from=$v.relevant_cats.0.categoryid_path_arr item=vv key=kk}

{if $kk eq ($v.relevant_cats.0.categoryid_path_arr_count - 1)}<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.relevant_cats.0.categoryid}" target="_blank" style="color: blue;">
{if $v.relevant_cats.1.product_count gt 0}<B>{/if}
{/if}
{$vv}{if $kk eq ($v.relevant_cats.0.categoryid_path_arr_count - 1)}{if $v.relevant_cats.1.product_count gt 0}</B>{/if}</a>{/if}{if $kk < ($v.relevant_cats.0.categoryid_path_arr_count - 1)} <B>></B> {/if} {if $v.relevant_cats.1.product_count gt 0 && $kk eq ($v.relevant_cats.0.categoryid_path_arr_count - 1)}({$v.relevant_cats.0.count_pc_products} / {$v.relevant_cats.0.categoryid}){/if}

{/foreach}

{*
{if $v.relevant_cats.0.google_product_category eq ""}
<input id="google_product_category_{$k}_0" type="text" name="posted_data[{$k}][google_product_category][{$v.relevant_cats.0.categoryid}]" value="{$v.relevant_cats.0.google_product_category}" />
{/if}
*}

{/if}

{*
{if $v.relevant_cats.1.categoryid_path_arr ne ""}
<br />
<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.relevant_cats.1.categoryid}" target="_blank" style="color: blue;">
{foreach from=$v.relevant_cats.1.categoryid_path_arr item=vv key=kk}
{$vv}{if $kk < ($v.relevant_cats.1.categoryid_path_arr_count - 1)} > {/if}
{/foreach}
</a>
{/if}
*}

{if $v.relevant_cats.1.categoryid_path_arr ne ""}
<br />
{foreach from=$v.relevant_cats.1.categoryid_path_arr item=vv key=kk}

{if $kk eq ($v.relevant_cats.1.categoryid_path_arr_count - 1)}<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.relevant_cats.1.categoryid}" target="_blank" style="color: blue;">
{if $v.relevant_cats.1.product_count gt 0}<B>{/if}
{/if}
{$vv}{if $kk eq ($v.relevant_cats.1.categoryid_path_arr_count - 1)}{if $v.relevant_cats.1.product_count gt 0}</B>{/if}</a>{/if}{if $kk < ($v.relevant_cats.1.categoryid_path_arr_count - 1)} <B>></B> {/if} {if $v.relevant_cats.1.product_count gt 0 && $kk eq ($v.relevant_cats.1.categoryid_path_arr_count - 1)}({$v.relevant_cats.1.count_pc_products} / <a href="javascript: void(0);" onclick="javascript: $('#form1_correct_categoryid_id_{$k}').val('{$v.relevant_cats.1.categoryid}');">{$v.relevant_cats.1.categoryid}</a>){/if}

{/foreach}

{*
{if $v.relevant_cats.1.google_product_category eq ""}
<input id="google_product_category_{$k}_1" type="text" name="posted_data[{$k}][google_product_category][{$v.relevant_cats.1.categoryid}]" value="{$v.relevant_cats.1.google_product_category}" />
{/if}
*}


{/if}


</td>

<td valign="middle">

{if $v.relevant_cats.0.score ne ""}
{$v.relevant_cats.0.score}
{/if}

{if $v.relevant_cats.1.score ne ""}
<br />
{$v.relevant_cats.1.score}
{/if}

</td>

<td valign="middle">
{$v.pc_delta}
</td>

<td valign="top">
 <div id="form1_div_correct_categoryid_{$k}">
  <input type="text" id="form1_correct_categoryid_id_{$k}" name="posted_data[{$k}][correct_categoryid]" value="" size="5" onkeyup="javascript: func_correct_categoryid_field_filled('{$k}');" onchange="javascript: func_correct_categoryid_field_filled('{$k}');" />
 </div>

 <div id="form1_div_cancel_approve_button_{$k}" style="display: none;">
  <input type="button" id="form1_cancel_approve_button_id_{$k}" value="Cancel approve" onclick="javascript: func_cancel_approve_button_clicked('{$k}');" />
 </div>

 <div id="form1_div_cancel_skip_button_{$k}" style="display: none;">
  <input type="button" id="form1_cancel_skip_button_id_{$k}" value="Cancel skip" onclick="javascript: func_cancel_skip_button_clicked('{$k}');" />
 </div>
</td>

<td width="100">
 <div id="form1_div_approve_button_{$k}">
  <input type="button" id="form1_approve_button_id_{$k}" value="Approve" onclick="javascript: func_approve_button_clicked('{$k}');" />
 </div>

 <div id="form1_div_skip_button_{$k}">

{if $pc_options.allow_skip_products eq "Y"}
  <input type="button" id="form1_skip_button_id_{$k}" value="Skip" onclick="javascript: func_skip_button_clicked('{$k}');" />
{/if}

 </div>
</td>
</tr>
{/foreach}

</table>

<div id="form1_div_submit_button_id" style="display: none;">
{* <input type="button" value="Submit" onclick="javascript: if (func_check_google_product_category()) submitForm(this, 'submit_pc_form1'); else alert('Fill all Google Product Category values')" /> *}
<input type="button" value="Submit" onclick="javascript: submitForm(this, 'submit_pc_form1');" />
</div>


{/capture}
{include file="dialog.tpl" title="Categorization" content=$smarty.capture.dialog extra='width="100%"'}

{/if}
