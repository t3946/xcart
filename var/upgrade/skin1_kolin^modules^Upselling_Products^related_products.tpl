{* $Id: related_products.tpl,v 1.15 2005/12/07 14:07:32 max Exp $ *}
{capture name=dialog}
{if $product_links ne ""}
	{include file="customer/main/products_t.tpl" products=$product_links flag=related}
{else}
	<ul class="PRItems no_marker">
		<li>::&nbsp;<a href="home.php?cat={$current_category.categoryid}&amp;path=alt" title="" class="VertMenuItems"><font size="2">{$lng.lbl_other} {$current_category.category}</font></a></li>
	</ul>
{/if}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_related_products content=$smarty.capture.dialog extra='width="100%" class="recommends no_padding_bottom"'}
