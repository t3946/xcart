<script type="text/javascript" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
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

<div id="po-tabs-container">
    <ul>
        <li><a href="#po_check">PO# check</a></li>
        <li><a href="#po_upload">Upload PO</a></li>
        <li><a href="#pending_po">Pending entry POs</a></li>
        <li><a href="#po_pipeline_log">PO pipeline log</a></li>

    </ul>


{capture name=dialog}
<div id="po_check">
    <form method="POST" action="purchase_orders.php" name="purchase_order_search">
        <div style="margin-bottom: 20px;">
            First check - maybe PO has already been entered or it is pending entry.
        </div>
        <div>
            <b>PO#</b> <input type="text" id="purchase_order_number_search" name="purchase_order_number_search"/> <input
                    id="purchase_order_search_submit" name="purchase_order_search_submit" type="submit" value="Search"/>
        </div>
    </form>
 </div>
{/capture}
{capture name=dialog_upload}
    <div id="po_upload">
    <form method="POST" action="purchase_orders.php" name="purchase_order_upload" enctype="multipart/form-data">
        <div style="margin-bottom: 20px;">
            Enter PO# indicated on the original PO.
        </div>
        <div style="margin-bottom: 20px;">
            <b>PO#</b> <input size="13" type="text" id="purchase_order_number_upload" name="purchase_order_number_upload"/>
            <select name="purchase_order_storefront_upload" id="purchase_order_storefront_upload">
                <option value="-1">Select StoreFront</option>
                {html_options options=$aStorefronts->getStoreFrontsSelect() selected=-1}
            </select>
        </div>
        <div>
            <b>PO has been received by</b>
            <select name="purchase_order_received_status" style="width: 198px;">
                {html_options options=$aRecievedStatuses}
            </select>
        </div>
        <div style="margin-bottom: 10px; margin-top: 10px;">
            <div><b>Attach original PO.</b>
                <input type="file" name="purchase_order_file" value="Choose flie"/>
            </div>

        </div>
        <div>
            <input type="submit" id="purchase_order_upload_submit" name="purchase_order_upload_submit" value="Upload"/>
        </div>

    </form>
    </div>
{/capture}
{capture name=dialog_pending}
<div id="pending_po">
    <form method="POST" action="purchase_orders.php" name="purchase_order_enter">
        <div style="margin-bottom: 20px;" id="purchase_order_pending_anchor">
        </div>
        <div style="margin-bottom: 20px;">
            <table cellpadding="3" cellspacing="1" width="50%">

                <tr class="TableHead">
                    <td width="100">PO #</td>
                    <td width="*" align="center">Original PO file</td>
                    <td width="100" align="center">StoreFront</td>
                    <td width="100" align="center">Received by</td>
                    <td align="center">Select</td>
                </tr>
                {if !empty($aPendingOrders)}
                    {foreach from=$aPendingOrders item=oPendingOrder}
                        <tr>
                            <td align="center">{$oPendingOrder->getOrderNumber()}</td>
                                <td><a target="_blank" href="{$oPendingOrder->getOrderFileLink()}">{$oPendingOrder->getOrderOriginalFileName()}</a></td>
                            <td>
                                <select name="purchase_order_storefront[{$oPendingOrder->getPOId()}]">
                                    {html_options options=$aStorefronts->getStoreFrontsSelect() selected=$oPendingOrder->getStoreFrontId()}
                                </select
                            </td>
                            <td align="center" nowrap="nowrap">{$oPendingOrder->getReceivedByName()}</td>
                            <td align="center"><input autocomplete="off" name="po_selected[]" type="radio" value="{$oPendingOrder->getPOId()}"/></td>
                        </tr>
                    {/foreach}
                {/if}
            </table>
        </div>
        <div style="margin-bottom: 10px;">
            <span>Enter PO using front-end checkout process.</span>
            <input type="submit" id="purchase_order_enter_submit" name="purchase_order_enter_submit" value="Enter PO"/>
            <b>OR</b> <span>drop PO if it has already been entered or has been canceled.</span>
            <input type="submit" name="purchase_order_drop_submit" value="Drop PO"/>
        </div>
    </form>
   </div>
{/capture}

{capture name=dialog_log}
<div id="po_pipeline_log">
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
                    {assign var="oCustomer" value=$oPendingOrdersLog->getCustomerEntity()}
                        <tr>
                            <td>{$oPendingOrdersLog->getLogDate()}</td>
                            <td>{$oCustomer->getCustomerFullName()}<br/>{if $oCustomer->getCustomerLogin()}({/if}{$oCustomer->getCustomerLogin()}{if $oCustomer->getCustomerLogin()}){/if}</td>
                            <td>{$oPendingOrdersLog->getLogText()}</td>
                        </tr>
                    {/foreach}
                </tr>
                {/if}
            </table>
            {include file="customer/main/navigation.tpl"}
        </div>
</div>
{/capture}

{$smarty.capture.dialog}
{$smarty.capture.dialog_upload}
{$smarty.capture.dialog_pending}
{$smarty.capture.dialog_log}
</div>


    <script>
        {literal}
        $(document).ready(function () {
            var el = $('#purchase_order_number_upload');{/literal}
            {if (!empty($po_number))}{literal}
                el.val("{/literal}{$po_number}{literal}").focus();{/literal}
            {/if}
            {literal}

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
            setTimeout(function() {$('html, body').animate({scrollTop: offset}, speed)}, 100);
        });
        {/literal}
    </script>

<script>
    {literal}
    $(document).ready(function () {
        $('#po-tabs-container').tabs();
        $("#purchase_order_enter_submit").click(function () {
            var ordernumber = $("input[name^='po_selected']:checked");
            if (ordernumber.length != 0) {
                $(this).closest('form').attr('target', '_blank');
                return true;

            } else {
                alert('Please select PO for entry!');
                return false;
            }
        });
        $("#purchase_order_upload_submit").on('click','',function() {
            if ($("#purchase_order_storefront_upload").val()==-1) {
                alert("Please select StoreFront");
                return false;
            }
        });
    });
    {/literal}
</script>
