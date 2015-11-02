
<script language="JavaScript" type="text/javascript">
<!--
{literal}
function uncheckAll(flag, form, prefix) {
        if (!form)
                return;

        if (prefix)
                var reg = new RegExp("^"+prefix, "");
        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].type == "checkbox" && (!prefix || form.elements[i].name.search(reg) == 0) && !form.elements[i].disabled){
			form.elements[i].checked = false;
                }
        }
}
{/literal}
-->
</script>


<form name="f_searchform2" action="cidev_show_more_filters.php" method="POST">
<input type="hidden" name="f_mode" value="f_search" id="f_mode" >
<input type="hidden" name="target" value="show_more" >

{if $filter eq "fvalues" && $cidev_filters_tree_sorted ne "" && $f_id ne ""}

<input type="hidden" name="f_update" value="f_values" >
<input type="hidden" name="f_id" value="{$f_id}" >

<table>
<tr>

{assign var="row_conter" value="0"}

  {foreach from=$cidev_filters_tree_sorted item=v}
    {if $v.filter_values ne "" && $v.f_id eq $f_id}

     {foreach from=$v.filter_values item=tree_filter_values}
	{if $tree_filter_values.found eq 'Y' || $tree_filter_values.selected eq "Y"}

 {if $row_conter eq "0"}
  <td valign="top">
   <table>
 {/if}

        <tr>
        <td width="5">
                <input name="fv_ids[{$tree_filter_values.fv_id}]" id="fv_id_{$tree_filter_values.fv_id}" value="Y" type="checkbox"
                {if $tree_filter_values.selected eq 'Y'}
                        checked="checked"
                        {assign var="show_clear_all_button" value="Y"}
                {/if}
                >
        </td>
        <td nowrap="nowrap" {if $tree_filter_values.selected eq 'Y' && $tree_filter_values.selected_and_found ne "Y"}style="color: #cccccc;"{/if}>{$tree_filter_values.fv_name} {if $filter_found_fv_ids_count[$tree_filter_values.fv_id] ne ""}({$filter_found_fv_ids_count[$tree_filter_values.fv_id]}){/if}</td>
        </tr>

        {math equation="x+1" x=$row_conter assign="row_conter"}

 {if $row_conter eq $rows_in_one_column}
  {assign var="row_conter" value="0"}
   </table>
  </td>
 {/if}

	{/if}
     {/foreach}

    {/if}
  {/foreach}

{if $row_conter lt $rows_in_one_column}
   </table>
  </td>
{/if}

</tr>
</table>

{/if}


{if $filter eq "brand" && $filter_selected_and_found_brands ne ""}
<input type="hidden" name="f_update" value="brands" >
<table>
<tr>

{assign var="row_conter" value="0"}

{foreach from=$filter_selected_and_found_brands item=v key=k}
 {if $row_conter eq "0"}
  <td valign="top">
   <table>
 {/if}

   <tr>
    <td width="5">
        <input name="b_ids[{$v.brandid}]" id="b_id_{$v.brandid}" value="Y" type="checkbox"
                {if $v.selected eq 'Y'}
                        checked="checked"
                        {assign var="show_clear_all_button" value="Y"}
                {/if}
        >
    </td>
    <td nowrap="nowrap" {if $v.selected eq 'Y' && $v.selected_and_found ne "Y"}style="color: #cccccc;"{/if}>{$v.brand} ({$v.count_products})</td>
   </tr>

 {math equation="x+1" x=$row_conter assign="row_conter"}

 {if $row_conter eq $rows_in_one_column}
  {assign var="row_conter" value="0"}
   </table>
  </td>
 {/if}

{/foreach}

{if $row_conter lt $rows_in_one_column}
   </table>
  </td>
{/if}

</tr>
</table>
{/if}



<br />
<br />
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
        <td align="left">
{if $filter_found_fv_ids_count ne "" || $filter_selected_and_found_brands ne ""}
        <input type="submit" value="Show" >
        </td>
{/if}
        <td align="right">
        {if $show_clear_all_button eq "Y"}
        <input type="submit" value="Clear All" onclick="javascript: {if $filter eq "fvalues"} uncheckAll(true, document.f_searchform2, 'fv_ids');{elseif $filter eq "brand"}uncheckAll(true, document.f_searchform2, 'b_ids');{/if}  $('#f_mode').val('clear');" >
        {/if}
        </td>
<tr>
</table>

</form>
