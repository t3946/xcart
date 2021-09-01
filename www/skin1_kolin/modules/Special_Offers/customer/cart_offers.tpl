{if $products ne "" and $cart.have_offers ne ""}
<div align="right">
{include file="buttons/button.tpl" button_title=$lng.lbl_sp_cart_offers href="offers.php?mode=cart"}
<hr class="Line" size="1" />
</div>
{/if}
