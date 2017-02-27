{capture name=product_splashes}
    <div>
        <select name="product_splash" id="product_splash_selector" style="width:80px;">
            <option value="0">No splash</option>
            {if ($aSplashes)}
                {foreach from=$aSplashes item=oSplash}
                    <option value="{$oSplash->id}" {if $oProductSplash && $oSplash->id == $oProductSplash->id}selected="selected"{/if}>{$oSplash->splash_name}</option>
                {/foreach}
            {/if}
        </select>
        <input data-productid="{$oProduct->productid}" type="button" id="product_add_splash" name="product_add_splash" value="Update"/>
    </div>
    <div style="margin-top:10px;" id="product_splash_preview">
        {if $oProductSplash}
            <img src="{$oProductSplash->image_path}" />
        {else}
            <img alt="No splash"/>
        {/if}
    </div>
{/capture}

{include file="dialog.tpl" title="Product splash" content=$smarty.capture.product_splashes extra='width="100%"'}

{literal}
    <script type="text/javascript">
        $('#product_splash_selector').change(function(){
            if ($(this).val() == 0){
                $('#product_splash_preview').find('img').prop('src', '');
            } else {
                $.post('ajax_admin.php', {
                            splash_id: $(this).val(),
                            ajax_action: 'get_splash_info'
                        },
                        function (data) {
                            if (data && data.result) {
                                $('#product_splash_preview')
                                        .find('img')
                                        .prop('src', data.data.image_path);
                            }
                        }, 'json');
            }
        });
        $('#product_add_splash').click(function() {
            var splash_id = $('#product_splash_selector ').find('option:selected').val();
            $.post('ajax_admin.php', {
                        splash_id: splash_id,
                        product_id: $(this).data('productid'),
                        ajax_action: 'change_product_splash'
                    },
                    function (data) {
                        if (data && data.result) {
                            alert('Splash has been updated')
                        }
                    }, 'json');
        })
    </script>
{/literal}