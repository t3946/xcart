{if $global_config}
    <table class="default_template_config" width="100%" cellspacing="1" cellpadding="3">
    <tr>
        <td></td>
        <td>Storefront:</td>
        <td>
            <select name="storefront[]" class="default_storefront" disabled="disabled">
                <option value="-1">Default</option>
                {foreach from=$sites item=store}
                    <option value="{$store->storefrontid}" {if ($site.model->storefrontid == $store->storefrontid)}selected="selected"{/if}>
                        {$store->domain}
                    </option>
                {/foreach}
            </select>
        </td>
    </tr>
    {foreach from=$global_config item=glob}
        {include file="admin/main/configuration_row.tpl" model=$glob}
    {/foreach}
        <tr>
            <td colspan="3">
                <hr/>
            </td>
        </tr>
    </table>
{/if}
{if $site_config}
    <table width="100%" cellspacing="1" cellpadding="3">
    {foreach from=$site_config item=site}
        <tr>
            <td></td>
            <td>Storefront:</td>
            <td>
                <select name="storefront">
                {foreach from=$sites item=store}
                    <option value="{$store->storefrontid}" {if ($site.model->storefrontid == $store->storefrontid)}selected="selected"{/if}>
                        {$store->domain}
                    </option>
                {/foreach}
                </select>
            </td>
        </tr>

        {foreach from=$site.config item=site_params}
            {include file="admin/main/configuration_row.tpl" model=$site_params}
        {/foreach}
    {/foreach}
        <tr>
            <td colspan="3">
                <hr/>
            </td>
        </tr>
    </table>
{/if}

<table width="100%" cellspacing="1" cellpadding="3">
    <tr>
        <td align="right">
            <a class="add_template_button" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
        </td>
    </tr>
</table>

<script>
    {literal}
        $('.add_template_button').click(function(){
            tinymce.remove();
            $(this)
               .closest('table')
               .before($('table.default_template_config')
                   .clone()
                   .find('textarea').removeAttr('id').end()
                   .find('.default_storefront').removeAttr('disabled').find('option:first-child').text('Select storefront').end().end()
                   .removeClass('default_template_config')
               );

            tinymce.init({
                selector: "textarea.new_editor",
                resize: "both",
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste fullpage"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                forced_root_block : false,
                force_br_newlines : true,
                force_p_newlines : false,
                convert_urls: false,
                relative_urls: false
            });
           return false;
        });
    {/literal}
</script>