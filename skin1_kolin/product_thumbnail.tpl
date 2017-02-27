{if $splash}
<div style="position: absolute; z-index:2;" class="images_splash">
    <img alt="{$splash->splash_name}" src="{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}{$config.Appearance.CDN_domain}{else}{$xcart_web_dir}{/if}{$splash->image_path}" />
</div>
{/if}
{if $config.Appearance.show_thumbnails eq "Y"}<img{if $id ne ''} id="{$id}"{/if} src="{if $tmbn_url}{$tmbn_url}{else}{if $full_url}{$http_location}{else}{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}{if $add_http_if_cdn eq "Y"}http://{/if}{$config.Appearance.CDN_domain}{else}{$xcart_web_dir}{/if}{/if}/image.php?type={$type|default:"T"}&amp;id={$productid}{/if}"{if $image_x ne 0} width="{$image_x}"{/if}{if $image_y ne 0} height="{$image_y}"{/if} alt="{$product|escape}" />{/if}

