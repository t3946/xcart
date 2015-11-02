{*
$Id: add_coupon.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<div data-role="collapsible" data-theme="c" data-content-theme="c">
  <h3>{$lng.lbl_redeem_discount_coupon}</h3>
  <div>
    <div class="text-block">{$lng.txt_add_coupon_header}</div>

    {if $gcheckout_enabled and $main ne 'checkout'}
      <div class="text-block">{$lng.txt_gcheckout_add_coupon_note}</div>
    {/if}
    <form action="cart.php" name="couponform" data-ajax="false">
      <input type="hidden" name="mode" value="add_coupon" />
      <table cellspacing="0" class="data-table" summary="{$lng.lbl_redeem_discount_coupon|escape}">
        <tr>
          <td class="data-name">{$lng.lbl_coupon_code}</td>
          <td><input type="text" size="32" name="coupon" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="button-row">{include file="customer/buttons/submit.tpl" type="input"}</td>
        </tr>
      </table>
    </form>
  </div>
</div>
