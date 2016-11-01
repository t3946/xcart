<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">

<br>
<br>

{capture name=dialog_processing}
    {include file="customer/main/navigation.tpl"}

<div id="missing-sku-container">
    <ul>
        <li><a href="#missing_match">{$lng.label_missing_match}</a></li>
        <li><a href="#missing_not_match">{$lng.label_missing_not_match}</a></li>
    </ul>
<div id="missing_match">
<form>
<table width="100%" id="missing_sku_table">
    <tr>
        <td colspan="3">

        </td>
    </tr>
    <tr class="TableHead">
        <td width="150">Amazon SKU</td>
        <td width="150">SKU</td>
        <td width="*">Product</td>
        <td width="10">Orders</td>
        <td width="10">Action</td>
    </tr>
    {foreach from=$aMatchedProducts item=oMatchProduct}
        {assign var="oProduct" value=$oMatchProduct->getProductInstance()}
        <tr {cycle values=', class="TableSubHead"'}>
            <td>{$oMatchProduct->getMissingSKU()}</td>
            <td><input class="new_match_sku" name="xcart_match_sku" value="{$oProduct->getSKU()}"/></td>
            <td><a target="_blank" href="{$oProduct->getProductFrontURL()}">{$oProduct->getProductName()}</a></td>
            <td align="center">{$oMatchProduct->getOrdersCount()}</td>
            <td style="text-align: center">
                <div class="action_reclass_buttons ui buttons" data-missing-sku="{$oMatchProduct->getMissingSKU()}">
                    <div data-action="Edit" class="ui button item" style="border: 1px solid #808080;">Edit</div>
                    <div style="border-color: #808080; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0;"
                         class="ui combo top right dropdown icon button">
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            {if ($oMatchProduct->getOrdersCount())}
                            <div data-action="Fix_orders" class="item">Fix orders</div>
                            {/if}
                            <div data-action="Add" class="item">Add</div>
                            <div data-action="Delete" class="item">Delete</div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    {/foreach}
    </table>
</form>
</div>
<div id="missing_not_match">
    <form>
        <table width="100%" id="missing_sku_table">
            <tr>
                <td colspan="3">

                </td>
            </tr>
            <tr class="TableHead">
                <td width="150">Amazon SKU</td>
                <td width="150">SKU</td>
                <td width="150">Product ID</td>
                <td width="*"></td>
                <td width="10">Action</td>
            </tr>
            {foreach from=$aNotMatchedProducts item=oMatchProduct}
                <tr {cycle values=', class="TableSubHead"'}>
                    <td>{$oMatchProduct->getMissingSKU()}</td>
                    <td><input class="new_match_sku" name="xcart_match_sku" value=""/></td>
                    <td><input class="xcart_match_productid" name="xcart_match_productid" value=""/></td>
                    <td></td>
                    <td style="text-align: center">
                        <div class="action_reclass_buttons ui buttons" data-missing-sku="{$oMatchProduct->getMissingSKU()}">
                            <div data-action="Edit" class="ui button item" style="border: 1px solid #808080;">Edit</div>
                            <div style="border-color: #808080; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0;"
                                 class="ui combo top right dropdown icon button">
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div data-action="Delete" class="item">Delete</div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            {/foreach}
        </table>
    </form>
</div>
{/capture}

{$smarty.capture.dialog_processing}

<script src="{$SkinDir}/js/semantic/components/dropdown.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/js/semantic/components/transition.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>

<script type="text/javascript">
    $('.dropdown').dropdown();
    {literal}
    $( document ).ready(function() {
        $('#missing-sku-container').tabs()
        .on('click','.action_reclass_buttons .item',function(){
            var button = $(this);
            var action_value = $(this).data('action');
            if (action_value == 'Add') {
                var clone_row = button.closest('tr').clone();
                var menu = $('<div class="action_reclass_buttons ui buttons"> ' +
                        '<div data-action="Edit" class="ui button item" style="border: 1px solid #808080;">Edit</div>' +
                        '<div style="border-color: #808080; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0;"' +
                        ' class="ui combo top right dropdown icon button">' +
                        '<i class="dropdown icon"></i>' +
                        '<div class="menu">' +
                        '<div data-action="Add" class="item">Add</div>' +
                        '<div data-action="Delete" class="item">Delete</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                clone_row.dropdown().find('td:first-child').html($('<input/>').attr('name','amazon_sku').addClass('amazon_sku')).end()
                        .find('.new_match_sku').val('').end()
                        .find('td:nth-child(3)').empty().end()
                        .find('td:nth-child(4)').html(menu).find('.dropdown').dropdown();
                button.closest('tr').after(clone_row);
            } else {
                if (confirm("Are you sure?")) {
                    $.post('ajax_admin.php', {
                                action: action_value,
                                category: $(this).closest('.action_reclass_buttons').data('missing-sku'),
                                amazon_sku: $(this).closest('tr').find('input.amazon_sku').val(),
                                new_sku: $(this).closest('tr').find('input.new_match_sku').val(),
                                new_productid: $(this).closest('tr').find('input.xcart_match_productid').val(),
                                ajax_action: 'missing_structure_change'
                            },
                            function (data) {
                                if (data.result) {
                                    switch (action_value) {
                                        case 'Edit':
                                            break;
                                        case 'Delete' :
                                            button.closest('tr').remove();
                                            break;
                                    }
                                    alert(action_value + " has been done!");
                                } else {
                                    alert(data.error);
                                }
                            }, 'json');
                }
            }
        })
    });
    {/literal}
</script>