{* $Id: menu_cart.tpl,v 1.35.2.1 2006/10/13 10:41:21 svowl Exp $ *}
<div id="ajax_minicart">
{capture name=menu}
{include file="customer/main/minicart.tpl"}
<a href="cart.php" class="VertMenuItems"><b>{$lng.lbl_view_cart}</b></a><br />
{if $minicart_total_items gt 0 && $js_enabled}
	<a href="#" onclick="javascript: window.open('popup_shipquote.php?short=Y','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" class="VertMenuItems">{$lng.lbl_shipping_quote}</a><br />
{/if}
{if $active_modules.Google_Checkout eq ""}
<a href="cart.php?mode=checkout" class="VertMenuItems">{$lng.lbl_checkout}</a><br />
{/if}
{if $active_modules.Wishlist ne "" and $wlid ne ""}
<a href="cart.php?mode=friend_wl&amp;wlid={$wlid}" class="VertMenuItems">{$lng.lbl_friends_wish_list}</a><br />
{/if}

{if $active_modules.Wishlist ne ""}
<a href="cart.php?mode=wishlist" class="VertMenuItems">{$lng.lbl_wish_list}</a><br />
{if $active_modules.Gift_Registry ne ""}
<a href="giftreg_manage.php" class="VertMenuItems">{$lng.lbl_gift_registry}</a><br />
{/if}
{/if}
{if $anonymous_login eq "" && $login ne ""}
<a href="register.php?mode=update" class="VertMenuItems">{$lng.lbl_modify_profile}</a><br />
<a href="register.php?mode=delete" class="VertMenuItems">{$lng.lbl_delete_profile}</a><br />
{/if}
{*
<a href="orders.php" class="VertMenuItems">{$lng.lbl_orders_history}</a><br />
*}
<br />
<a href="retrieve_orders.php" class="VertMenuItems">{$lng.lbl_retrieve_orders}</a><br />
{if $user_subscription ne ""}
{include file="modules/Subscriptions/subscriptions_menu.tpl"}<br />
{/if}
{if $active_modules.RMA ne ""}
{include file="modules/RMA/customer_menu.tpl"}<br />
{/if}
{if $active_modules.Special_Offers ne ""}
{include file="modules/Special_Offers/menu_cart.tpl"}<br />
{/if}
{*
<br />
{if $js_enabled}
	<a href="{$js_update_link|amp}" class="SmallNote">{$lng.txt_javascript_disabled}</a>
{else}
	<a href="{$js_update_link|amp}" class="SmallNote">{$lng.txt_javascript_enabled}</a>
{/if}
*}
{/capture}
{ include file="menu.tpl" dingbats="dingbats_orders.gif" menu_title=$lng.lbl_your_cart menu_content=$smarty.capture.menu }
</div>
