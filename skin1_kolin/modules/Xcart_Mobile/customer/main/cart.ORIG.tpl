{*
$Id: cart.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<div class="ui-grid-a">
  <div class="ui-block-a">
    {$smarty.capture.continue_button}
  </div>
  <div class="ui-block-b checkout-button">
    {$smarty.capture.checkout_button}
  </div>
</div>
<script type="text/javascript">
  //<![CDATA[
  var txt_are_you_sure = '{$lng.txt_are_you_sure|wm_remove|escape:"javascript"}';
  minicart_total_items = {$minicart_total_items};
  //]]>
</script>
{if $products ne ""}
  <script type="text/javascript" src="{$SkinDir}/js/cart.js"></script>
  <div class="products cart">
    <form action="cart.php" method="post" name="cartform">
      <input type="hidden" name="action" value="update" />
      <ul data-role="listview" data-type="cart-products">
        {foreach from=$products item=product}
          {if $product.hidden eq ""}
            <li>
              <div class="cart-item">
                <div class="product-thumbnail">
                  {strip}
                    {include file="product_thumbnail.tpl" productid=$product.display_imageid product=$product.product tmbn_url=$product.pimage_url type=$product.is_pimage}
                    {if $active_modules.Special_Offers and $product.have_offers}
                      <span class="so-thumb" onclick="javascript: $.mobile.changePage('offers.php?mode=product&amp;productid={$product.productid}');"></span>
                    {/if}
                  {/strip}
                </div>
                <div class="product-details">
                  <span class="ui-li-heading">{$product.product|amp}</span>
                  <div class="descr">
                    {assign var="price" value=$product.display_price}
                    {if $active_modules.Product_Configurator and $product.product_type eq "C"}
                      {include file="modules/Product_Configurator/pconf_customer_cart.tpl" main_product=$product}
                      {assign var="price" value=$product.pconf_display_price}
                    {/if}
                    <div class="price-row">
                      {strip}
                        <span class="product-price-text">
                          {currency value=$price} &times;
                          <span class="amount-section">
                            {if !($active_modules.Egoods and $product.distribution)}
                              <input type="number" data-inline="true" size="1"
                              {else}
                                {$product.amount}</span><input type="hidden"
                            {/if} 
                                        name="productindexes[{$product.cartid}]" value="{$product.amount}" class="amount-input" />
                          {if !($active_modules.Egoods and $product.distribution)}
                            {include file="customer/buttons/button.tpl" button_title=$lng.lbl_update_qty href="javascript: return updateCartItem(`$product.cartid`);" data_theme="c" data_mini="true" data_iconpos="notext" data_icon="refresh"}
                          {/if}
                        </span> = </span>
                      {/strip}
                      <span class="product-price-value">
                        {multi x=$price y=$product.amount assign=unformatted}{currency value=$unformatted}
                      </span>
                      <span class="market-price">
                        {alter_currency value=$unformatted}
                      </span>
                      {if $config.Taxes.display_taxed_order_totals eq "Y" and $product.taxes}
                        <div class="taxes">
                          {include file="customer/main/taxed_price.tpl" taxes=$product.taxes is_subtax=true}
                        </div>
                      {/if}
                    </div>
                    {if $product.product_options ne ""}
                      {include file="customer/buttons/edit_product_options.tpl" id=$product.cartid data_theme="c" data_icon="gear" data_mini="true"}
                    {/if}
                    {if $active_modules.Special_Offers}
                      {include file="modules/Special_Offers/customer/cart_price_special.tpl"}
                    {/if}
                    {if $active_modules.Gift_Registry}
                      {include file="modules/Gift_Registry/product_event_cart.tpl"}
                    {/if}
                    {if $active_modules.Special_Offers}
                      {include file="modules/Special_Offers/customer/cart_free.tpl"}
                    {/if}
                    {if $gcheckout_display_product_note and $product.valid_for_gcheckout eq 'N'}
                      <div class="gc_disabled">{$lng.lbl_gcheckout_product_disabled}</div>
                    {/if}
                  </div>
                  <div class="clearing"></div>
                </div>
              </div>
              {* "Delete" button *}
              {capture name="delete_confirm"}
                {strip}
                  var del = confirm('{$lng.lbl_remove} \'{$product.product|escape}\'?');
                  if (del == true) {ldelim}
                    $.mobile.changePage('{$catalogs.customer}/cart.php?mode=delete&productindex={$product.cartid}');
                    event.stopPropagation();
                  {rdelim}
                {/strip}
              {/capture}
              <span class="ui-li-link-alt ui-btn ui-btn-icon-notext ui-btn-up-d" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-icon="false" data-iconpos="notext" data-theme="d" title="{$lng.lbl_delete}">
                <span class="ui-btn-inner">
                  <span class="ui-btn-text"></span>
                  <span onclick="{$smarty.capture.delete_confirm}" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="delete" data-iconpos="notext" data-theme="f" title="{$lng.lbl_delete}" class="ui-btn ui-btn-up-f ui-shadow ui-btn-corner-all ui-btn-icon-notext">
                    <span class="ui-btn-inner ui-btn-corner-all">
                      <span class="ui-btn-text"></span>
                      <span class="ui-icon ui-icon-delete ui-icon-shadow">&nbsp;</span>
                    </span>
                  </span>
                </span>
              </span>
            </li>
          {/if}
        {/foreach}
      </ul>
      <div class="right-block">
        {include file="customer/main/cart_subtotal.tpl"}
      </div>
      <div class="clearing"></div>
      {include file="customer/main/shipping_estimator.tpl"}
      {include file="modules/Gift_Registry/gift_wrapping_cart.tpl"}
      {if $active_modules.Special_Offers && $cart.free_offers}
        <div data-role="collapsible" data-theme="c" data-content-theme="c">
          <h3>{$lng.lbl_sp_offers|escape}</h3>
          <div>
            {include file="modules/Special_Offers/customer/free_offers.tpl"}
          </div>
        </div>
      {/if}
      {if $active_modules.Gift_Certificates && $cart.giftcerts}
        <div data-role="collapsible" data-theme="c" data-content-theme="c">
          <h3>{$lng.lbl_sp_offers|escape}</h3>
          <div>
            <div class="text-block">{$lng.txt_cart_note}</div>
            {include file="modules/Gift_Certificates/gc_cart.tpl" giftcerts_data=$cart.giftcerts}
          </div>
        </div>
      {/if}


  </div>
</form>

<div class="ui-grid-a">
  <div class="ui-block-a">
    {include file="customer/buttons/button.tpl" style="link" additional_button_class="simple-delete-button" button_title=$lng.lbl_clear_cart href="javascript: if (confirm(txt_are_you_sure)) self.location='cart.php?mode=clear_cart'; return false;"}
    {if $active_modules.Special_Offers}
      {include file="modules/Special_Offers/customer/cart_checkout_buttons.tpl"}
    {/if}
  </div>
  <div class="ui-block-b checkout-button">
    {$smarty.capture.checkout_button|replace:'top_checkout_button':'bottom_checkout_button'}
  </div>
  {if $active_modules.Bongo_International}
      <div class="ui-block-c checkout-bongo">
            {include file="modules/Bongo_International/checkout_button.tpl"}
      </div>
  {/if}
</div>
{else}
  {$lng.txt_your_shopping_cart_is_empty}
{/if}

{if $active_modules.Special_Offers and $cart ne ""}
  {include file="modules/Special_Offers/customer/cart_offers.tpl"}
  {include file="modules/Special_Offers/customer/promo_offers.tpl"}
{/if}
{if $cart.coupon_discount eq 0 and $products and $active_modules.Discount_Coupons}
  {include file="modules/Discount_Coupons/add_coupon.tpl"}
{/if}
