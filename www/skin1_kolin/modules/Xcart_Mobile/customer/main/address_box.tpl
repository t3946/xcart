{*
$Id: address_box.tpl 63 2012-10-30 11:56:13Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
{if $mode eq "select"}
  <form action="popup_address.php" method="post" name="address_{$address.id|default:0}" id="address_{$address.id|default:0}">
    <input type="hidden" name="mode" value="select" />
    <input type="hidden" name="id" value="{$address.id|default:0}" />
    <input type="hidden" name="type" value="{$type|escape:"html"|default:"B"}" />
    <input type="hidden" name="for" value="{$for|escape:"html"|default:"cart"}" />
  </form>
{/if}
<li id="address_box_{$address.id|default:0}" {if $mode eq 'select' and $type ne '' and $for ne '' and $checkout_module ne 'One_Page_Checkout' or $address.id lte 0}onclick="javascript: $('#address_{$address.id|default:0}').submit();"{/if}>
  {if $add_new}
    <a data-role="button" data-inline="true" data-theme="b" class="button" href="popup_address.php{if $mode eq 'select'}?return=select&for={$for}&type={$type}{/if}">{$lng.lbl_add_new_address}</a>
  {else}
    {if $address.default_s eq 'Y' or $address.default_b eq 'Y'}
      <h3 class="address-default">
        {if $address.default_s eq 'Y' and $address.default_b eq 'Y'}
          <img src="{$ImagesDir}/icon_billing.png" width="19" height="15" alt="" />
          <img src="{$ImagesDir}/icon_shipping.png" width="16" height="9" alt="" />
          {if $mode ne 'select'}
            {$lng.lbl_billing_and_shipping_address}
          {/if}
        {elseif $address.default_b eq 'Y'}
          <img src="{$ImagesDir}/icon_billing.png" width="19" height="15" alt="" />
          {if $mode ne 'select'}
            {$lng.lbl_billing_address}
          {/if}
        {else}
          <img src="{$ImagesDir}/icon_shipping.png" width="16" height="9" alt="" />
          {if $mode ne 'select'}
            {$lng.lbl_shipping_address}
          {/if}
        {/if}
      </h3>
    {/if}
    {include file="customer/main/address_details_html.tpl"}
  </li>
  <li>
    <div class="ui-grid-b">
      {if $mode eq 'select' and $type ne '' and $for ne '' and $checkout_module ne 'One_Page_Checkout' or $address.id lte 0}
        {assign var="block_2_letter" value="b"}
        {assign var="block_3_letter" value="c"}
        <div class="ui-block-a">
          {assign var="form_name" value=$address.id|default:0}
          {include file="customer/buttons/button.tpl" button_title=$lng.lbl_select href="javascript: $('#address_`$form_name`').submit();" data_theme="b"}
        </div>
      {/if}
      {if not ($checkout_module eq 'One_Page_Checkout' and $for eq 'cart')}
        <div class="ui-block-{$block_2_letter|default:'a'}">
          {include file="customer/buttons/button.tpl" button_title=$lng.lbl_change href="popup_address.php?id=`$address.id`" style="link"}
        </div>
      {/if}
      {if $address.default_s ne 'Y' and $address.default_b ne 'Y'}
        <div class="ui-block-{$block_3_letter|default:'b'}">
          {include file="customer/buttons/button.tpl" button_title=$lng.lbl_delete href="javascript: if (confirm(txt_are_you_sure)) self.location = 'address_book.php?mode=delete&amp;id=`$address.id`'" data_theme="f"}
        </div>
      {/if}
    </div>
    <br />
  {/if}
</li>
