{* $Id: recommends.tpl,v 1.9 2006/03/21 07:17:18 svowl Exp $ *}
{if $recommends}
{capture name=recommends}

{include file="customer/main/products_t_new.tpl" products=$recommends}

{*$lng.txt_recommends_comment*}
{*
<ul class="RPItems no_marker">
{section name=num loop=$recommends}
	<li>::&nbsp;<a href="product.php?productid={$recommends[num].productid}" class="VertMenuItems"><font size=2>{$recommends[num].product}</font></a></li>
{/section}
</ul>
*}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_recommends content=$smarty.capture.recommends extra='width="100%" class="recommends no_padding_bottom"' do_not_use_h1="Y"}
{/if}
