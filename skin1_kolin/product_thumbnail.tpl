{if $splash}
<div style="position: absolute; z-index:2;" class="images_splash">
    <img alt="{$splash->splash_name}" src="{include file="product_image_path.tpl"}{$splash->image_path}" />
</div>
{/if}
{if $config.Appearance.show_thumbnails eq "Y"}<img{if $id ne ''} id="{$id}"{/if} src="{include file="product_image_src.tpl"}" {if $image_x ne 0} width="{$image_x}"{/if}{if $image_y ne 0} height="{$image_y}"{/if} alt="{$product|escape}" />{/if}

