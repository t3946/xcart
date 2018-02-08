<br>
<br>
{capture name=amazon_products_listing}
<div style="float:right"><a style="line-height:28px;" target="_blank" href="az_monitor_upload_status.php">Monitor Upload Status</a></div>
{include file="customer/main/per_page_editor.tpl" per_page=$per_page per_page_text='Products per page'}
{include file="customer/main/navigation.tpl"}
<div style="clear: both">
{include file="main/check_all_row.tpl" form="createlistingsform" prefix="productids"}
</div>

<form action="az_create_listings.php" method="post" name="createlistingsform">
    {include file="admin/main/az_listing_product_table.tpl" asin_edit=true}
    <p>
        <input type="submit" value="Submit to listing loader" />
    </p>
    {include file="customer/main/per_page_editor.tpl" per_page=$per_page per_page_text='Products per page'}
    {include file="customer/main/navigation.tpl"}
</form>
{/capture}

{include file="dialog.tpl" title="Creating Product Listings on Amazon (`$total_items`)" content=$smarty.capture.amazon_products_listing extra='width="100%"'}
{literal}
    <script type="text/javascript">
        $('button.button').on('click','',function(){
            var icon = $(this).find('.icon');
            var amazon_link = '{/literal}{$sAmazonLink}{literal}';
            if (icon.hasClass('edit')){
                $(this).prev('a').replaceWith($('<input size="10" class="live_asin_edit" />'));
            } else if (icon.hasClass('save')){
                var input = $(this).prev('input');
                if (input.val() == '') {
                    input.val($(this).data('asin'));
                } else {
                    var iProduct = $(this).closest('tr').data('product-id');
                    $.post('ajax_admin.php', {
                                product_id: iProduct,
                                listing_upload_asin: input.val(),
                                ajax_action: 'verification_arbitrage_full'
                            },
                            function (data) {
                                if (data.result) {
                                    input.replaceWith($('<a target="_blank" href="' + amazon_link.replace('%s', input.val()) + '"/>').text(input.val()));
                                }
                            },
                            'json');
                }
            }
            icon.toggleClass('edit').toggleClass('save');
            return false;
        });
    </script>
{/literal}