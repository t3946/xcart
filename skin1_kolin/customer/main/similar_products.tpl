{* $Id: similar_products.tpl,v 1.0 2010/09/07 12:10:32 kate Exp $ *}

{if $similar_products ne ""}
{capture name=dialog}


{include file="customer/main/products_t_new.tpl" products=$similar_products}

{*
	<ul class="PRItems no_marker">
		<li>::&nbsp;<a href="home.php?cat={$current_category.categoryid}&amp;path=alt" title="" class="VertMenuItems"><font size="2">{$lng.lbl_other} {$current_category.category}</font></a></li>
	</ul>
*}

{/capture}
{include file="dialog.tpl" title=$lng.lbl_similar_products content=$smarty.capture.dialog extra='width="100%" class="recommends no_padding_bottom"'}
{/if}
