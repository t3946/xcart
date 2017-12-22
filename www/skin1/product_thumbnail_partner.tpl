{* $Id: product_thumbnail_partner.tpl,v 1.7 2004/06/24 12:19:12 max Exp $ *}
{if $config.Appearance.show_thumbnails eq "Y"}<IMG src="{$http_location}/image.php?productid={$productid}"{if $image_x ne 0} width="{$image_x}"{/if}{if $image_y ne 0} height="{$image_y}"{/if} alt="{$product|escape}" border="0">{/if}
