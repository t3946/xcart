
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
	<div style="margin-bottom: 20px;">
		For each product compare FRONT END and DISTR WEBSITE columns to make sure that product descriptions and images are identical. If any descrepencies, choose VERIFIED = 'No' and explain what's the difference.
	</div>

    <div id="send_note_for_product" class="ajax_note_field" style="display: none;">
        <input id="verified_product_id" type="hidden" value="" />
        <input id="verified_order_id" type="hidden" value="" />
        <input id="verified_product_status_id" type="hidden" value="" />
        <textarea rows="3" style="width: 100%;" cols="70" name="payment_note" id="notes"></textarea><br>
        <div style="margin-top:10px">
            <input type="button" id="post_message" value="Send">
            <input type="button" id="cancel_message_button" value="Cancel">
        </div>

    </div>
<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	<td width="100">DISTRIBUTOR</td>
	<td width="60" nowrap="nowrap" align="center">ORDER #</td>
	<td width="100" align="center">BACK END</td>
	<td align="center">FRONT END</td>
	<td width="100" nowrap="nowrap" align="center">DISTR WEBSITE</td>
	<td width="100" nowrap="nowrap" align="center">LAST VERIF DATE</td>
	<td width="100" align="center">VERIFIED?</td>
</tr>


{foreach from=$aManufacturers name=manufacturerFirst item=aManufacturer key=iManufacturerId}
	{assign var='showManufacturer' value=true}
	{foreach from=$aManufacturer name=manufacturerSecond item=oOrderManufacturer}
		{foreach from=$oOrderManufacturer->getOrderProducts() item=oProduct name=manufacturerThird}
			{if ($oProduct->getField('manufacturerid') == $iManufacturerId && $oProduct->getField('verification_statusid') < 3)}
				{assign var='oManufacturer' value = $oProduct->getManfacturerClass()}
				{assign var='oVerifyDate' value = $oProduct->getProductLastVerifyDate()}
				{assign var='oHTMLShot' value = $oProduct->getHTMLShot($oOrderManufacturer->getField('orderid'))}
				<tr{cycle values=', class="TableSubHead"'} {if $showManufacturer}data-manufacturer-id="{$oManufacturer->getField('manufacturerid')}"{/if}>
					<td width="1%">
						{if $showManufacturer}
							<a style="font-weight: bold;" href="{$oManufacturer->getManufacturerModifyURL()}" target="_blank"> {$oManufacturer->getField('manufacturer')}</a>
							{assign var='showManufacturer' value=false}
                        {else}
                            <a style="font-weight: bold; display:none;" href="{$oManufacturer->getManufacturerModifyURL()}" target="_blank"> {$oManufacturer->getField('manufacturer')}</a>
						{/if}
					</td>
					<td nowrap="nowrap"><a target="_blank" href="{$oOrderManufacturer->getOrderModifyURL()}">{$oOrderManufacturer->getDisplayOrderNumber()}</a></td>
					<td nowrap="nowrap"><a target="_blank" href="{$oProduct->getProductModifyURL()}">{$oProduct->getField('productcode')}</a></td>
					<td><a target="_blank" href="{$oProduct->getProductFrontURL()}">{$oProduct->getField('product')}</a>
                        {if (!empty($oHTMLShot))}
                            <a title="View HTML-Shot" target="_blanks" style="float:right; margin-top:3px;" href="/admin/view_html_shot.php?id={$oHTMLShot->getId()}" class="html-shot-view">
                                <img src="{$ImagesDir}/html-shot.png" />
                            </a>
                        {/if}
                    </td>
					<td nowrap="nowrap"><a target="_blank" href="{$oProduct->getProductURLOnDistributorWebSite()}">{$oProduct->getMPN()}</a></td>
					<td nowrap="nowrap">{if ($oVerifyDate)}{$oVerifyDate->format('d-M-Y')}{/if}</td>
					<td align="center">
					<select title="{$oProduct->getProductVerificationHistoryLastNote()}" data-order-id="{$oOrderManufacturer->getField('orderid')}" data-product-verification-id="{$oProduct->getField('productid')}"
                            class="change_product_verify_status" name="product_verify_status" data-prev-val="{$oProduct->getField('verification_statusid')}">
						{foreach from=$aVerifyStatuses item=aVerifyStatus}
							<option value="{$aVerifyStatus.statusid}"  {if $oProduct->getField('verification_statusid') == $aVerifyStatus.statusid} selected="selected"{/if}>
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
	<td colspan="10" align="center">No products</td>
</tr>

{/foreach}



    <tr id="click_to_back_changes" style="display:none;">
        <td colspan="7" style="padding: 10px 3px; background-color:#FFF;"><a href="#" style="font-weight: bold;" onclick="$('#click_to_back_changes').nextAll('tr').fadeToggle('slow'); return false;">View already verified products</a></td>
    </tr>



</table>



{/capture}
{include file="dialog.tpl" title=$capture_dialog_name content=$smarty.capture.dialog extra='width="100%"'}

{literal}
<script>

    function submitChanges(obj){
        var product = $('#verified_product_id',obj),
            order = $('#verified_order_id',obj),
            status = $('#verified_product_status_id',obj),
            selectchanged = $('select[data-product-verification-id='+product.val()+']'),
            rowtohide = selectchanged.parent().parent();
            rowtohide.css('opacity',0.5);
            obj.hide();

        $.post('ajax_admin.php',{
                    product_id : product.val(),
                    order_id : order.val(),
                    verify_status_id: status.val(),
                    note_text: $('textarea',obj).val(),
                    ajax_action: 'change_verify_product_status'
                },
                function (data) {
                    if (data) {
                        if (data.result) {
                            rowtohide.css('opacity',1);
                            selectchanged.attr("data-prev-val",status.val());
                            selectchanged.attr("title",$('textarea',obj).val());
                            if (status.val() == 3) {


                                rowtohide.fadeOut('slow', function () {
                                    if (rowtohide.data('manufacturer-id')){
                                        var nextrow = rowtohide.next('tr:visible');
                                        if (nextrow.data('manufacturer-id') > 0) {

                                        } else {
                                            nextrow.attr('data-manufacturer-id',rowtohide.data('manufacturer-id'));
                                            $('td:first-child > a',nextrow).show();
                                        }
                                    }
                                    //rowtohide.show();
                                    if ($('#click_to_back_changes').next('tr').is(':visible')) {
                                        rowtohide.show();
                                    }

                                    $('#click_to_back_changes').after(rowtohide);

                                    $('td:first-child > a',rowtohide).show();
                                    $('#click_to_back_changes').show();
                                });

                            }
                            $('textarea',obj).val('');
                            product.val('');
                            status.val('');


                        } else {
                            alert(data.error);
                        }
                    }
                }, 'json');
    }

    $( document ).ready(function() {

        var supervise = {};
        $('select.change_product_verify_status').each(function() {
            var id = $(this).data('product-verification-id');
            if (supervise[id]) {
                $(this).parent().parent().remove();
            }
            else {
                supervise[id] = [];
            }
            supervise[id].push($(this).data('order-id'));

        });
        $.each( supervise, function( key, value ) {
            $('select[data-product-verification-id='+key+']:visible').attr('data-order-id',value);
        });

        $('#send_note_for_product').next('table').find('tr:even').removeClass('TableSubHead');
        $('#send_note_for_product').next('table').find('tr:odd').addClass('TableSubHead');

        $('.change_product_verify_status').on('change','', function () {
            var statusid = $(this).val();
            $('#verified_product_id').val($(this).data('product-verification-id'));
            $('#verified_order_id').val($(this).attr('data-order-id'));
            $('#verified_product_status_id').val(statusid);
            if (statusid > 0 && statusid < 3) {
                var position = $(this).position(),
                note_form = $('#send_note_for_product'),
                textarea = note_form.find('textarea');
                note_form.css('left', position.left - 342).css('top', position.top);

                if (statusid == 1)
                    textarea.attr('placeholder',"Please describe the problem and explain why you didn't fix it.");
                if (statusid == 2)
                    textarea.attr('placeholder',"Please describe what was the problem and how did you fix it.");
                note_form.show();
                textarea.focus();
            } else {
                submitChanges($('#send_note_for_product'));
            }

        });
        $('#cancel_message_button').on('click','', function() {
            var divform = $(this).parents('#send_note_for_product'),
            productid = $('#verified_product_id',divform).val(),
            curselect = $('select[data-product-verification-id='+productid+']'),
            prevval = curselect.data('prev-val');
            curselect.val(prevval);
            divform.find('textarea').val('');
            divform.hide();
        });

        $('#post_message').on('click','', function() {
            submitChanges($(this).parents('#send_note_for_product'))
        })
    });
</script>
{/literal}