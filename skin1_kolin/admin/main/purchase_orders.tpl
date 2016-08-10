{if $supplemental_category_section ne "Y"}

    {if ($smarty.get.mode ne "info")}
        {include file="page_title.tpl" title="PO pipeline"}
    {else}
        {include file="page_title.tpl" title=$lng.lbl_info_pages}
    {/if}

    {if ($smarty.get.mode ne "info")}
        {$lng.txt_product_verification_top_text}
        {assign var="capture_dialog_name" value="PO pipeline"}
        <br/>
        <br/>
    {else}
        {assign var="capture_dialog_name" value=$lng.lbl_info_pages}
    {/if}

{else}
    <br/>
    <br/>
    {assign var="capture_dialog_name" value="PO pipeline"}
{/if}

{capture name=dialog}
    <form method="POST" name="purchase_order_search">
        <div style="margin-bottom: 20px;">
            First check - maybe PO has already been entered or it is pending entry.
        </div>
        <div>
            <b>PO#</b> <input type="text" id="purchase_order_number_search" name="purchase_order_number_search"/> <input
                    id="purchase_order_search_submit" name="purchase_order_search_submit" type="submit" value="Search"/>
        </div>
    </form>
{/capture}
{capture name=dialog_upload}
    <form method="POST" name="purchase_order_upload" enctype="multipart/form-data">
        <div style="margin-bottom: 20px;">
            Enter PO# indicated on the original PO.
        </div>
        <div style="margin-bottom: 20px;">
            <b>PO#</b> <input type="text" id="purchase_order_number_upload" name="purchase_order_number_upload"/>
            <select name="purchase_order_storefront_upload" id="purchase_order_storefront_upload">
                <option value="-1">Select StoreFront</option>
                {html_options options=$aStorefronts->getStoreFrontsSelect() selected=-1}
            </select>
        </div>
        <div style="margin-bottom: 10px;">
            Attach original PO.
        </div>
        <div style="margin-bottom: 20px;">
            <input type="file" name="purchase_order_file" value="Choose flie"/>
        </div>
        <div>
            <input type="submit" id="purchase_order_upload_submit" name="purchase_order_upload_submit" value="Upload"/>
        </div>

    </form>
{/capture}
{capture name=dialog_pending}
    <form method="POST" name="purchase_order_enter">
        <div style="margin-bottom: 20px;" id="purchase_order_pending_anchor">
            First check - maybe PO has already been entered or it is pending entry.
        </div>
        <div style="margin-bottom: 20px;">
            <table cellpadding="3" cellspacing="1" width="50%">

                <tr class="TableHead">
                    <td width="100">PO #</td>
                    <td width="*" align="center">Original PO file</td>
                    <td width="100" align="center">StoreFront</td>
                    <td width="100" align="center">Select</td>
                </tr>
                {if !empty($aPendingOrders)}
                    {foreach from=$aPendingOrders item=oPendingOrder}
                        <tr>
                            <td align="center">{$oPendingOrder->getOrderNumber()}</td>
                            <td>{$oPendingOrder->getOrderOriginalFileName()}</td>
                            <td>
                                <select name="purchase_order_storefront[{$oPendingOrder->getPOId()}]">
                                    {html_options options=$aStorefronts->getStoreFrontsSelect() selected=$oPendingOrder->getStoreFrontId()}
                                </select
                            </td>
                            <td align="center"><input autocomplete="off" name="po_selected[]" type="radio"
                                                      value="{$oPendingOrder->getPOId()}"/></td>
                        </tr>
                    {/foreach}
                {/if}
            </table>
        </div>
        <div style="margin-bottom: 10px;">
            Enter PO using front-end checkout process.
        </div>
        <div style="margin-bottom: 10px;">
            <input type="button" id="purchase_order_enter_submit" name="purchase_order_enter_submit" value="Enter PO"/>
        </div>
        <div style="margin-bottom: 10px;">
            or drop PO if it has already been entered or has been canceled.
        </div>
        <div>
            <input type="submit" name="purchase_order_drop_submit" value="Drop PO"/>
        </div>
    </form>
{/capture}

{capture name=dialog_log}
        <div style="margin-bottom: 20px;">
            <table cellpadding="3" cellspacing="1" width="100%" >

                <tr class="TableHead">
                    <td width="150">Date </td>
                    <td width="150" align="center">Name</td>
                    <td width="*" align="center"> Action</td>
                </tr>
                {if !empty($aPendingOrdersLog)}
                <tr>
                    {foreach from=$aPendingOrdersLog item=oPendingOrdersLog}
                        <tr>
                            <td>{$oPendingOrdersLog->getLogDate()}</td>
                            <td>{$oPendingOrdersLog->getLogin()}</td>
                            <td>{$oPendingOrdersLog->getLogText()}</td>
                        </tr>
                    {/foreach}
                </tr>
                {/if}
            </table>
            {include file="customer/main/navigation.tpl"}
        </div>
{/capture}

{include file="dialog.tpl" title='PO# check' content=$smarty.capture.dialog extra='width="100%"'}
<br/>
<br/>
{include file="dialog.tpl" title='Upload PO' content=$smarty.capture.dialog_upload extra='width="100%"'}
<br/>
<br/>
{include file="dialog.tpl" title='Pending entry POs' content=$smarty.capture.dialog_pending extra='width="100%"'}
<br/>
<br/>
{include file="dialog.tpl" title='PO pipeline log' content=$smarty.capture.dialog_log extra='width="100%"'}

{if !empty($po_number) || !empty($po_pending)}
    <script>
        {literal}
        $(document).ready(function () {
            {/literal}
            {if (!empty($po_pending))}
                var el = $('#purchase_order_pending_anchor');
            {else}
                var el = $('#purchase_order_number_upload');
            {/if}
            {literal}
            el.val("{/literal}{$po_number}{literal}").focus();
            var elOffset = el.offset().top;
            var elHeight = el.height();
            var windowHeight = $(window).height();
            var offset;

            if (elHeight < windowHeight) {
                offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
            }
            else {
                offset = elOffset;
            }
            var speed = 700;
            $('html, body').animate({scrollTop: offset}, speed);

        });


        {/literal}
    </script>
{/if}
<script>
    {literal}
    $(document).ready(function () {
        $("#purchase_order_enter_submit").on("click", function () {
            var ordernumber = $("input[name^='po_selected']:checked");
            if (ordernumber.length != 0) {
                $.post(
                        "ajax_admin.php", {
                            ordernumber: ordernumber.val(),
                            ajax_action: 'select_purchase_order_for_entry'
                        }, function (data) {
                            window.open(data.frontend_url, '_blank')
                        }, "json");

            } else {
                alert('Select purchase order for entry!');
            }
        });
        $("#purchase_order_upload_submit").on('click','',function() {
            if ($("#purchase_order_storefront_upload").val()==-1) {
                alert("Select StoreFront");
                return false;
            }
        });
    });
    {/literal}
</script>
