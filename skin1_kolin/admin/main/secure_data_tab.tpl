
{include file="main/subheader.tpl" title="Product return/replacement request"}

{if $rmas ne ""}
<br />
{foreach from=$rmas item=rma key=rma_id}

<a name="target_rma_{$rma_id}" id="target_rma_{$rma_id}"></a>

<div align="center"><h1  style="color: #550000; margin-bottom: 0px;">RMA Request # {$order.order_prefix}{$order.orderid}_R-{$rma.rma_number}</h1></div>

{include file="customer/main/rma_products.tpl" rma_info=$rma}

<br />
<hr />
<br />
{/foreach}
{/if}

<br />
{$lng.lbl_back_end_RMA_intro}
<br />
<br />

<form action="order.php" method="post" name="rma_request_form1">
<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="orderid" value="{$orderid}" />
<input type="button" value="Create RMA request" onclick="javascript: submitForm(this, 'create_rma_request');" />
</form>

{if $smarty.get.target_rma ne ""}
<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

        $(document).ready(function (){
                $('html, body').animate({
                    scrollTop: $("#target_rma_{/literal}{$smarty.get.target_rma}{literal}").offset().top
                }, 1000);
        });

{/literal}
//]]>
</script>
{/if}
