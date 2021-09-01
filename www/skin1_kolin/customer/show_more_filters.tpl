<script language="JavaScript" type="text/javascript">
    <!--
    {literal}
    function uncheckAll(flag, form, prefix) {
        if (!form)
            return;

        if (prefix)
            var reg = new RegExp("^" + prefix, "");
        for (var i = 0; i < form.elements.length; i++) {
            if (form.elements[i].type == "checkbox" && (!prefix || form.elements[i].name.search(reg) == 0) && !form.elements[i].disabled) {
                form.elements[i].checked = false;
            }
        }
    }

    function parentSubmit() {
        var inputs = $('#f_searchform2 input:checked'),
            formparent = $('#f_searchform');
        inputs.each(function() {
            $('<input>').attr({
                type: 'hidden',
                name: $(this).attr('name'),
                value: $(this).val()
            }).appendTo(formparent);
        });
        _popup.hide();
        formparent.submit();
    }
    {/literal}
    -->
</script>


<form id="f_searchform2" name="f_searchform2" action="{$return}" method="GET">
    <input type="hidden" name="f_mode" value="f_search" id="f_mode">
    <input type="hidden" name="target" value="show_more">
    {if $filter eq "fvalues"}
        <input type="hidden" name="f_update" value="f_values">
        <input type="hidden" name="f_id" value="{$oFilter->getFilterId()}">
        <table>
        <tr>

        {assign var="row_conter" value="0"}
        {foreach from=$aFilterValues item=oFilterValue}
            {if $row_conter eq "0"}
                <td valign="top">
                <table>
            {/if}
            <tr>
                <td width="5">
                    <input name="fv_ids[{$oFilterValue->getFilterValueId()}]"
                           id="fv_id_{$oFilterValue->getFilterValueId()}"
                           value="Y" type="checkbox"
                            {if $oFilter->getFilterValuesSelected() &&  in_array($oFilterValue, $oFilter->getFilterValuesSelected())}
                                checked="checked"
                                {assign var="show_clear_all_button" value="Y"}
                            {/if}
                    >
                </td>
                <td nowrap="nowrap"
                    {if $tree_filter_values.selected eq 'Y' && $tree_filter_values.selected_and_found ne "Y"}style="color: #cccccc;"{/if}>
                    {$oFilterValue->getFilterValueName()}
                    ({$oFilterValue->getCount()})
                </td>
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


    {if $filter eq "brand"}
        <input type="hidden" name="f_update" value="brands">
        <table>
        <tr>

        {assign var="row_conter" value="0"}

        {foreach from=$aFilterValues item=oFilterValue}
            {if $row_conter eq "0"}
                <td valign="top">
                <table>
            {/if}
            <tr>
                <td width="5">
                    <input name="b_ids[{$oFilterValue->getBrandId()}]" id="b_id_{$oFilterValue->getBrandId()}" value="Y"
                           type="checkbox"
                            {if $v.selected eq 'Y'}
                                checked="checked"
                                {assign var="show_clear_all_button" value="Y"}
                            {/if}
                    >
                </td>
                <td nowrap="nowrap"
                    {if $v.selected eq 'Y' && $v.selected_and_found ne "Y"}style="color: #cccccc;"{/if}>
                    {$oFilterValue->getBrandName()}
                    ({$oFilterValue->getCount()})
                </td>
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


    <br/>
    <br/>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="left">
                {if $oFilter->getFoundValuesCount() > 0}
                    <input type="button" value="Show" onclick="parentSubmit();">
                {/if}
            </td>

            <td align="right">
                {if $show_clear_all_button eq "Y"}
                    <input type="submit" value="Clear All"
                           onclick="javascript: {if $filter eq "fvalues"} uncheckAll(true, document.f_searchform2, 'fv_ids');{elseif $filter eq "brand"}uncheckAll(true, document.f_searchform2, 'b_ids');{/if}  $('#f_mode').val('clear');">
                {/if}
            </td>
        <tr>
    </table>

</form>
