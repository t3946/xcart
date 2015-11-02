{* $Id: location.tpl,v 1.4 2005/11/28 14:19:29 max Exp $ *}
{if $category_location and $cat ne ""}
{php} print_r($current_category) {/php}
<font class="NavigationPath">
{strip}
{section name=position loop=$category_location}
{if $category_location[position].1 ne "" }<a href="{$category_location[position].1|amp}{if $smarty.get.mode eq "info" || $current_category.main_order_by gt 500}{if %position.first%}?mode=info{else}&mode=info{/if}{/if}" class="NavigationPath">{/if}
{$category_location[position].0}
{if $category_location[position].1 ne "" }</a>{/if}
{if %position.last% ne "true"}&nbsp;&gt;&nbsp;
{/if}
{/section}
</font>
{/strip}
<br /><br />
{/if}
