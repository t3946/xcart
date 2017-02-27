{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}
    {assign var="imagePath" value="//`$config.Appearance.CDN_domain`"}
{else}
    {assign var="imagePath" value=$xcart_web_dir}
{/if}
{if $splash}
<div style="position: absolute; z-index:2;" class="images_splash">
    <img alt="{$splash->splash_name}" src="{$imagePath}{$splash->image_path}" />
</div>
{/if}
{if $config.Appearance.show_thumbnails eq "Y"}<img{if $id ne ''} id="{$id}"{/if}
    src="{if $tmbn_url}{$imagePath}{$tmbn_url}{else}{if $full_url}{$http_location}{else}{imagePath}/image.php?type={$type|default:"T"}&amp;id={$productid}{/if}{/if}"
    {if $image_x ne 0} width="{$image_x}"{/if}{if $image_y ne 0} height="{$image_y}"{/if} alt="{$product|escape}" />{/if}

