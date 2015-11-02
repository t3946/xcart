{if $saved_paymentid ne "Y"}<div align="right" id="cidev_tabs_menu">{/if}
<table width="960" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
{*
<td align="left">
{if $last_categoryid ne 0}
{assign var=last_categoryid value="?cat=`$last_categoryid`"}
{else}
{assign var=last_categoryid value=""}
{/if}
{include file="modules/Fast_Lane_Checkout/big_button.tpl" button_title=$lng.lbl_continue_shopping style="button" href="home.php`$last_categoryid`"}
</td>
<td align="right">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
{assign var="columns_counter" value=0}
{assign var="curpos" value="B"}
{foreach item=step from=$checkout_tabs}
{math assign="columns_counter" equation="x+1" x=$columns_counter}
<td>
{if $step.selected eq "Y"}
{assign var="curpos" value="A"}
<table cellspacing="0" cellpadding="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width="50%"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
<td width="19"><img src="{$ImagesDir}/cart_checkout.gif" width="19" height="16" alt="" /></td>
<td width="50%"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
</table>
{else}&nbsp;{/if}</td>
{/foreach}
</tr>

<tr>
<td colspan="{$columns_counter}"><img src="{$ImagesDir}/spacer.gif" width="1" height="3" alt="" /></td>
</tr>
<tr>
{assign var="cnt" value=0}
{assign var="curpos" value="B"}
{assign var="mark" value="B"}
{foreach item=step from=$checkout_tabs}
{math assign="cnt" equation="x+1" x=$cnt}
<td>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td{if $cnt gt 1} class="{if $curpos eq "B"}LineBeforeCart{else}LineAfterCart{/if}"{/if} width="50%"><img src="{$ImagesDir}/spacer.gif" width="1" height="2" alt="" /></td>
<td class="{if $curpos eq "B"}LineBeforeCart{else}LineAfterCart{/if}" width="2"><img src="{$ImagesDir}/spacer.gif" width="2" height="2" alt="" /></td>
{if $step.selected eq "Y"}{assign var="curpos" value="A"}{/if}
<td {if $cnt lt $columns_counter}class="{if $curpos eq "B"}LineBeforeCart{else}LineAfterCart{/if}"{/if} width="50%"><img src="{$ImagesDir}/spacer.gif" width="1" height="2" alt="" /></td>
</tr>
<tr>
<td width="50%"><img src="{$ImagesDir}/spacer.gif" width="1" height="3" alt="" /></td>
<td class="{if $mark eq "B"}LineBeforeCart{else}LineAfterCart{/if}" width="2"><img src="{$ImagesDir}/spacer.gif" width="2" height="5" alt="" /></td>
{if $mark ne $curpos}{assign var="mark" value="A"}{/if}
<td width="50%"><img src="{$ImagesDir}/spacer.gif" width="1" height="3" alt="" /></td>
</tr>
</table>
</td>
{/foreach}
</tr>

<tr>
<td colspan="{$columns_counter}"><img src="{$ImagesDir}/spacer.gif" width="1" height="3" alt="" /></td>
</tr>

<tr>
{assign var="hide_link" value=0}
{foreach item=step from=$checkout_tabs}
<td align="center">{if $step.link ne "" and $step.selected ne "Y" and $hide_link eq 0}<a href="{$step.link|amp}" class="CheckoutTab" style="color: #0000FF;">{$step.title}</a>{else}<font class="CheckoutTabSel" style="color: #000000;">{$step.title}</font>{/if}{if $step.selected eq "Y"}{assign var="hide_link" value=1}{/if}</td>
{/foreach}
</tr>

</table>
</td></tr>
*}

<tr>
<td
{if $checkout_step eq "-1" && $login eq ""}
class="cidev_checkout_bar0"
{elseif $checkout_step eq "-1" && $login ne ""}
class="cidev_checkout_bar01"
{elseif $checkout_step eq "0"}
class="cidev_checkout_bar2"
{elseif $checkout_step eq "1"}
	{if $cart.paymentid ne "" || $paymentid ne ""}
class="cidev_checkout_bar22"
	{else}
class="cidev_checkout_bar2"
	{/if}
{elseif $checkout_step eq "2"}
class="cidev_checkout_bar3"
{elseif $checkout_step eq "3"}
class="cidev_checkout_bar4"
{/if}
>
{if $checkout_step eq "-1" && $login eq ""}

{elseif $checkout_step eq "0"}
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
</ul>
{elseif $checkout_step eq "-1" && $login ne ""}
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" style="cursor: default;"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid={$cart.paymentid}'"></li>
 <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='cart.php?mode=checkout'"></li>
</ul>
{elseif $checkout_step eq "1"}
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
{if $cart.paymentid ne "" || $paymentid ne ""}
<li class="cidev_checkout_link_address" style="cursor: default;"></li>
{* <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='cart.php?mode=checkout'"></li> *}
<li class="cidev_checkout_link_shippings" onclick="javascript: document.registerform.action='register.php?mode=update&action=cart&cidev_return_to_step=3&paymentid={$paymentid}'; document.registerform.submit();"></li>
{/if}
</ul>
{elseif $checkout_step eq "2"}
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid={$cart.paymentid}'"></li>
</ul>
{elseif $checkout_step eq "3"}
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid={$paymentid}'"></li>
 <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='cart.php?mode=checkout'"></li>
</ul>
{elseif $checkout_step eq "4"}
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid={$cart.paymentid}'"></li>
 <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='cart.php?mode=checkout'"></li>
 <li class="cidev_checkout_link_review" onclick="javascript: self.location='cart.php?mode=checkout&paymentid={$paymentid}'"></li>
</ul>
{/if}
</td>
</tr>
</table>
{if $saved_paymentid ne "Y"}
</div>
{*
{if $checkout_step ne "1" && $checkout_step ne "0"}
<br />
{/if}
*}
<br />
{/if}
