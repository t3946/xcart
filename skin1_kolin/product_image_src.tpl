{include file="product_image_path.tpl"}
{if $tmbn_url}{$imagePath}{$tmbn_url}{else}{if $full_url}{$http_location}{else}{$imagePath}/default_image.gif{/if}{/if}