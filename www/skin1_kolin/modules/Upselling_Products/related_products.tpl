{* $Id: related_products.tpl,v 1.15 2005/12/07 14:07:32 max Exp $ *}
{if $product_links ne ""}
<br />
{capture name=dialog}
	{include file="customer/main/products_t_new.tpl" products=$product_links flag=related}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_related_products content=$smarty.capture.dialog extra='width="100%" class="recommends no_padding_bottom"' do_not_use_h1="Y" }
{/if}
