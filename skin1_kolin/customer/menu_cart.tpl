{* $Id: menu_cart.tpl,v 1.35.2.1 2006/10/13 10:41:21 svowl Exp $ *}
<div id="ajax_minicart">

<table cellpadding="0" cellspacing="0" border="0">
<tr {if $minicart_total_items gt 0} id="id_tr_minicart" onclick="javascript: self.location='/cart.php';" style="cursor: pointer;"{/if}>
        <td class="cidev_minicart_l"></td>
        <td class="cidev_minicart_c"><div class="cidev_minicart_c_amount">{if $minicart_total_items gt 0}{$minicart_total_items}{else}0{/if}</div></td>
	{if $minicart_total_items gt 0}
        <td class="cidev_minicart_r"><a id="id_tr_minicart_a" href="/cart.php"><img src="{$ImagesDir}/spacer.gif" width="1" height="1" alt="View Cart" /></a></td>
	{else}
        <td class="cidev_minicart_r_empty"></td>
	{/if}
</tr>
</table>
{if $minicart_total_items gt 0 && $variant_id_for_point2 eq "0" && $variant_id_for_point2 ne ""}
<div style="position: absolute; margin-top: 2px; padding-left: 2px;">
<a href="javascript: void(0);" onclick="javascript: window.open('/popup_shipquote.php?short=Y','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" class="cart_popup_shipquote">{$lng.lbl_shipping_quote}</a>
</div>
{/if}

{*
<div id="miniCart" class="minicart" style="padding-bottom: 4px;" align="">
{if $minicart_total_items gt 0}
<a class="btn" href="cart.php">
<div onMouseOver="this.style.color='#FF0000'" onMouseOut="this.style.color='#0033CC'" style="padding-left: 20px; color: #0033CC;">VIEW CART</div>
</a>

<div style="position: absolute; margin-top: 0px; padding-left: 20px;">
<a href="#" onclick="javascript: window.open('popup_shipquote.php?short=Y','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" class="cart_popup_shipquote">{$lng.lbl_shipping_quote}</a>
</div>

{else}
<a class="btn" style="text-decoration: none; color: #333333;">CART IS EMPTY</a>
{/if}
<span class="minicart_item_count">
<span class="num">{if $minicart_total_items gt 0}{$minicart_total_items}{else}0{/if}</span>
<i class="c_r"></i>
</span>
</div>
*}

{*
<table cellpadding="0" border="0" cellspacing="0" width="240" height="102" style="background-color: #FFF3CD;">
{if $minicart_total_items > 0}
<tr>
        <td height="33" align="left" colspan="2" style="padding-left: 15px;" class="cart_font_menu">{$minicart_total_items} {if $minicart_total_items eq "1"}product{else}products{/if} {if $minicart_total_items eq "1"}is{else}are{/if} in your cart</td>
</tr>
<tr>
        <td height="33" align="left" colspan="2" style="padding-left: 15px;"><a href="#" onclick="javascript: window.open('popup_shipquote.php?short=Y','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" class="cart_popup_shipquote">{$lng.lbl_shipping_quote}</a></td>
</tr>
<tr>
        <td align="left" style="padding-left: 15px;"><a href="cart.php" class="cart_view_cart">{$lng.lbl_view_cart} &darr;</a></td>
        <td align="left" style=""><a href="cart.php?mode=checkout" class="cart_checkout">{$lng.lbl_checkout} &rarr;</a></td>
</tr>
{else}
<tr>
        <td align="left" colspan="2" style="padding-top: 7px; padding-left: 15px;" class="cart_font_menu">Your cart is empty</td>
</tr>
<tr>
        <td align="left" colspan="2" style="padding-top: 10px; padding-left: 15px;"><img src="{$ImagesDir}/Sleeping-Cart.png" alt="" border="0" /></td>
</tr>
{/if}
</table>
*}


{*
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
*}

{*
<a href="orders.php" class="VertMenuItems">{$lng.lbl_orders_history}</a><br />
*}
{*
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
*}
{*
<br />
{if $js_enabled}
	<a href="{$js_update_link|amp}" class="SmallNote">{$lng.txt_javascript_disabled}</a>
{else}
	<a href="{$js_update_link|amp}" class="SmallNote">{$lng.txt_javascript_enabled}</a>
{/if}
*}
</div>
