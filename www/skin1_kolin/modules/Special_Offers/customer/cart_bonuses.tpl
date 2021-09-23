{* $Id: cart_bonuses.tpl,v 1.6 2006/03/28 08:21:09 max Exp $ *}
{if $cart.bonuses ne ""}
<div align="left">
<font class="ProductPrice">{$lng.lbl_sp_cart_bonuses_title}</font>
<ul>
{if $cart.bonuses.points ne 0}
<li>{$lng.lbl_sp_cart_bonuses_bp|substitute:"num":$cart.bonuses.points}</li>
{/if}
{if $cart.bonuses.memberships ne ""}
<li>{$lng.lbl_sp_cart_bonuses_memberships}<br />
{foreach name=memberships from=$cart.bonuses.memberships item=membership}
{$membership}
{if $smarty.foreach.memberships.last ne "1"} {$lng.lbl_or} {/if}
{/foreach}
</li>
{/if}
</ul>
</div>
<hr class="Line" size="1" />
{/if}{* $cart.bonuses ne "" *}
