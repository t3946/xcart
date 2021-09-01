{*
$Id: shipping_estimator.tpl 63 2012-10-30 11:56:13Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
{if $login eq '' and $config.Shipping.enable_shipping eq 'Y'}
  <div data-role="collapsible" data-theme="b" data-collapsed="{if $shipping ne ''}false{else}true{/if}" data-content-theme="b">
    <h3>{$lng.lbl_shipping_cost}</h3>
    <div class="estimator-container cart-border">
      {if $userinfo ne ''}
        <strong>{$lng.lbl_destination}:</strong>
        {foreach from=$shipping_estimate_fields item=f key=k name=estimate}
          {if $userinfo.address.S eq ''}
            {assign var=k value="s_"|cat:$k}
          {/if}  
          {assign var=_fieldname value=$k|cat:'name'}
          {assign var=_field value=$userinfo.address.S.$_fieldname|default:$userinfo.address.S.$k|default:$userinfo.$_fieldname|default:$userinfo.$k}
          {if $f.avail eq 'Y' and $_field ne ''}
            {$_field}
        {if not $smarty.foreach.estimate.last}, {/if}{/if}
      {/foreach}
      {assign var=btitle value=$lng.lbl_change}
    {/if}
    <div class="button-row">
      {include file="customer/buttons/button.tpl" button_title=$btitle|default:$lng.lbl_estimate_shipping_cost href="javascript:popupOpen('popup_estimate_shipping.php');" style="link"}
    </div>
    <div class="smethods">
      {include file="customer/main/checkout_shipping_methods.tpl" simple_list=true}
    </div>
  </div>
</div>
{/if}
