{*
$Id: checkout_2_method.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<h1>{$lng.lbl_shipping_and_payment}</h1>
{if $smarty.get.err eq 'gc_not_enough_money'}
  <div class="center error-message">{$lng.txt_gc_not_enough_money}</div>
{/if}
<form action="cart.php" method="post" name="cartform" data-ajax="false">
  <input type="hidden" name="mode" value="checkout" />
  <input type="hidden" name="cart_operation" value="cart_operation" />
  <input type="hidden" name="action" value="update" />
  {assign var=modify_url value="cart.php?mode=checkout&edit_profile&paymentid=`$paymentid`"}
  <div data-role="collapsible-set">
    {if $config.Shipping.enable_shipping eq "Y" && $config.Shipping.need_shipping_section eq "Y"}
      <div class="flc-checkout-container" data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
        <h3>{$lng.lbl_shipping_address}</h3>
        <div>
          {if $userinfo}
            {if $userinfo.default_address_fields.address}
              {$userinfo.s_address}<br />
            {/if}
            {if $userinfo.default_address_fields.address_2 and $userinfo.s_address_2}
              {$userinfo.s_address_2}<br />
            {/if}
            {if $userinfo.default_address_fields.city}
              {$userinfo.s_city}<br />
            {/if}
            {if $userinfo.default_address_fields.county and $config.General.use_counties eq "Y" and $userinfo.s_county}
              {$userinfo.s_countyname}<br />
            {/if}
            {if $userinfo.default_address_fields.state}
              {$userinfo.s_statename}<br />
            {/if}
            {if $userinfo.default_address_fields.country}
              {$userinfo.s_countryname}<br />
            {/if}
            {if $userinfo.default_address_fields.zipcode}
              {include file="main/zipcode.tpl" val=$userinfo.s_zipcode zip4=$userinfo.s_zip4 static=true}
            {/if}
          {else}
            No data
          {/if}
          {if $userinfo ne ""}
            <div class="text-pre-block">
              {if $login ne ''}
                {assign var=modify_url value="popup_address.php?mode=select&for=cart&type=S"}
                {assign var=link_href value="popup_address.php?mode=select&for=cart&type=S"}
              {/if}
              {include file="customer/buttons/modify.tpl" href=$modify_url link_href=$link_href|default:$modify_url style="link"}
            </div>
          {/if}

        </div>
      </div>
    {/if}
    <div class="flc-checkout-container" data-role="collapsible" data-collapsed="true" data-theme="c" data-content-theme="c">
      <h3>{$lng.lbl_billing_address}</h3>
      <div>
        {if $userinfo ne ''}
          {if $userinfo.default_address_fields.address}
            {$userinfo.b_address}<br />
          {/if}
          {if $userinfo.default_address_fields.address_2 and $userinfo.b_address_2}
            {$userinfo.b_address_2}<br />
          {/if}
          {if $userinfo.default_address_fields.city}
            {$userinfo.b_city}<br />
          {/if}
          {if $userinfo.default_address_fields.county and $config.General.use_counties eq "Y" and $userinfo.b_county}
            {$userinfo.b_countyname}<br />
          {/if}
          {if $userinfo.default_address_fields.state}
            {$userinfo.b_statename}<br />
          {/if}
          {if $userinfo.default_address_fields.country}
            {$userinfo.b_countryname}<br />
          {/if}
          {if $userinfo.default_address_fields.zipcode}
            {include file="main/zipcode.tpl" val=$userinfo.b_zipcode zip4=$userinfo.b_zip4 static=true}
          {/if}
        {else} 
          No data 
        {/if} 
        {if $userinfo}
          <div class="text-pre-block">
            {if $login ne ''}
              {assign var=modify_url value="popup_address.php?mode=select&for=cart&type=B"}
              {assign var=link_href value="popup_address.php?mode=select&for=cart&type=B"}
            {/if}
            {include file="customer/buttons/modify.tpl" href=$modify_url link_href=$link_href|default:$modify_url style="link"}
          </div>
        {/if}
      </div>
    </div>
  </div>
  {if $config.Shipping.enable_shipping eq "Y"}
    <div class="flc-checkout-options" data-role="collapsible" data-collapsed="{if $smarty.get.section ne '' && $smarty.get.section ne 'shipping_method'}true{else}false{/if}" data-theme="b" data-content-theme="b">
      <h3>{$lng.lbl_delivery}</h3>
      <div>
        {include file="customer/main/checkout_shipping_methods.tpl"}
        {if $display_ups_trademarks and $current_carrier eq "UPS"}
          {include file="modules/UPS_OnLine_Tools/ups_notice.tpl"}
        {/if}
      </div>
    </div>
  {/if}
  <div class="flc-checkout-options" data-role="collapsible" data-collapsed="{if $smarty.get.section ne '' && $smarty.get.section ne 'payment_method'}true{else}false{/if}" data-theme="e" data-content-theme="e">
    <h3>{$lng.lbl_payment_method}</h3>
    <div>
      {include file="customer/main/checkout_payment_methods.tpl}
    </div>
  </div>
  {include file="customer/buttons/continue.tpl" type="input" additional_button_class="main-button" data_inline="false" data_icon="arrow-r"}

</form>
