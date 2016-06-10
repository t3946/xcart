
{if $supplemental_category_section ne "Y"}

{if ($smarty.get.mode ne "info")}
{include file="page_title.tpl" title="PRODUCT VERIFICATION"}
{else}
{include file="page_title.tpl" title=$lng.lbl_info_pages}
{/if}

{if ($smarty.get.mode ne "info")}
{$lng.txt_product_verification_top_text}
{assign var="capture_dialog_name" value="PRODUCT VERIFICATION"}
<br /><br />
{else}
{assign var="capture_dialog_name" value=$lng.lbl_info_pages}
{/if}

{else}
<br /><br />
{assign var="capture_dialog_name" value="PRODUCT VERIFICATION"}
{/if}



{capture name=dialog}
	<div style="font-weight: bold;">
		For each product compare FRONT END and DISTR WEBSITE columns to make sure that product descriptions and images are identical. If any descrepencies, choose VERIFIED = 'No' and explain what's the difference.
	</div>
<form action="verify_category.php" method="post" name="processcategoryform">

    <div id="send_note_for_product" class="ajax_note_field" style="display: none;">
        <input id="verified_product_id" type="hidden" value="" />
        <input id="verified_product_status_id" type="hidden" value="" />
        <textarea rows="3" style="width: 100%;" cols="70" name="payment_note" id="notes"></textarea><br>
        <div style="margin-top:10px">
            <input type="button" id="post_message" value="Send">
            <input type="button" id="cancel_message_button" value="Cancel">
        </div>

    </div>
<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	<td>DISTRIBUTOR</td>
	<td nowrap="nowrap" align="center">ORDER #</td>
	<td align="center">BACK END</td>
	<td align="center">FRONT END</td>
	<td nowrap="nowrap" align="center">DISTR WEBSITE</td>
	<td nowrap="nowrap" align="center">LAST VERIF DATE</td>
	<td align="center">VERIFIED?</td>
</tr>


{foreach from=$aManufacturers name=manufacturerFirst item=aManufacturer key=iManufacturerId}
	{assign var='showManufacturer' value=true}
	{foreach from=$aManufacturer name=manufacturerSecond item=oOrderManufacturer}
		{foreach from=$oOrderManufacturer->getOrderProducts() item=oProduct name=manufacturerThird}
			{if ($oProduct->getField('manufacturerid') == $iManufacturerId)}
				{assign var='oManufacturer' value = $oProduct->getManfacturerClass()}
				{assign var='oVerifyDate' value = $oProduct->getProductLastVerifyDate()}
				<tr{cycle values=', class="TableSubHead"'}>
					<td nowrap="nowrap" width="1%">
						{if $showManufacturer}
							<a style="font-weight: bold;" href="{$oManufacturer->getManufacturerModifyURL()}" target="_blank"> {$oManufacturer->getField('manufacturer')}</a>
							{assign var='showManufacturer' value=false}
						{/if}
					</td>
					<td nowrap="nowrap"><a target="_blank" href="{$oOrderManufacturer->getOrderModifyURL()}">{$oOrderManufacturer->getDisplayOrderNumber()}</a></td>
					<td><a target="_blank" href="{$oProduct->getProductModifyURL()}">{$oProduct->getField('productcode')}</a></td>
					<td nowrap="nowrap"><a target="_blank" href="{$oProduct->getProductFrontURL()}">{$oProduct->getField('product')}</a></td>
					<td nowrap="nowrap"><a href="{$oProduct->getProductURLOnDistributorWebSite()}">{$oProduct->getMPN()}</a></td>
					<td nowrap="nowrap">{if ($oVerifyDate)}$oVerifyDate->format('d.M.Y'){/if}</td>
					<td align="center">
					<select data-product-verification-id="{$oProduct->getField('productid')}" class="change_product_verify_status" name="product_verify_status">
						{foreach from=$aVerifyStatuses item=aVerifyStatus}
							<option value="{$aVerifyStatus.statusid}">
								{$aVerifyStatus.name}
							</option>
						{/foreach}
					</select>
					</td>
				</tr>
			{/if}
		{/foreach}
		{if ($smarty.foreach.manufacturerSecond.last)}
			{assign var='showManufacturer' value=true}
		{/if}
	{/foreach}

{foreachelse}

<tr>
	<td colspan="{if $supplemental_category_section ne "Y"}12{else}10{/if}" align="center">No products</td>
</tr>

{/foreach}

</form>

<br />

{/capture}
{include file="dialog.tpl" title=$capture_dialog_name content=$smarty.capture.dialog extra='width="100%"'}

{literal}
<script>

    function closeNoteForm($obj) {

    }

    $( document ).ready(function() {
        $('.change_product_verify_status').on('change','', function () {
            $('#verified_product_id').val($(this).data('product-verification-id'));
            $('#verified_product_status_id').val($(this).val());
            var position = $(this).position();
            var note_form = $('#send_note_for_product');
            note_form.css('left',position.left-142).css('top',position.top);
            note_form.show();
            note_form.find('textarea').focus();

        });
        $('#cancel_message_button').on('click','', function() {
            var divform = $(this).parents('#send_note_for_product');
            divform.find('textarea').val('');
            divform.hide();
        })

        $('#post_message').on('click','', function() {
            var divform = $(this).parents('#send_note_for_product');
            var product = $('#verified_product_id',divform);
            var status = $('#verified_product_status_id',divform);
            console.log(product.val());
            console.log(status.val());

            $.post('ajax_admin.php',{
                        product_id : product.val(),
                        verify_status_id: status.val(),
                        note_text: $('textarea',divform).val('')
                    },
                    function (data) {
                        if (data) {
                            $('textarea',divform).val('');
                            product.val('');
                            status.val('');
                            divform.hide();
                        }
                    }, 'json');


        })
    });
</script>
{/literal}