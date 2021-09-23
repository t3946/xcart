{*
$Id: wl_products.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $active_modules.Product_Options}
  <script type="text/javascript" src="{$SkinDir}/modules/Product_Options/edit_product_options.js"></script>
{/if}
{if $script_name eq ""}
  {assign var="script_name" value="cart"}
{/if}
{if $wl_products ne "" or ($active_modules.Gift_Certificates ne "" and $wl_giftcerts ne "")}
  <div class="products cart">
    {if $wl_products ne ""}
      <ul data-role="listview" data-type="cart-products">
        {foreach from=$wl_products item=product}

          <li>
            <div class="cart-item">
              <form action="cart.php" method="post" name="update{$product.wishlistid}_form">
                <input type="hidden" name="mode" value="wishlist" />
                <input type="hidden" name="eventid" value="{$eventid|escape}" />
                <input type="hidden" name="wlitem" value="{$product.wishlistid}" />
                <input type="hidden" name="action" value="update_quantity" />
                <div class="product-thumbnail">
                  {strip}
                    <div class="img-wrapper">
                      <img src="{$ImagesDir}/spacer.gif" class="leveler" alt="" />
                      {include file="product_thumbnail.tpl" productid=$product.display_imageid product=$product.product tmbn_url=$product.pimage_url type=$product.is_pimage image_x=$product.tmbn_x splash=$product.oSplash}
                    </div>
                  {/strip}
                  <div class="delete-button hidden-control">
                    {include file="customer/buttons/button.tpl" href="cart.php?mode=wldelete&wlitem=`$product.wishlistid`&eventid=`$eventid`" data_theme="f" data_icon="delete" button_title=$lng.lbl_delete}
                  </div>
                </div>
                <div class="product-details">
                  <span class="ui-li-heading">{$product.product}</span>
                  <div class="descr">
                    {if $giftregistry and $product.amount_purchased ge $product.amount}
                      <p class="product-details-title">{$lng.lbl_purchased}</p>
                    {/if}
                    {$product.descr}

                    {assign var="price" value=$product.taxed_price}
                    {if $active_modules.Product_Configurator and $product.product_type eq "C"}
                      {include file="modules/Product_Configurator/pconf_customer_cart.tpl" main_product=$product products=$product.subproducts}
                    {/if}
                    {if $product.amount_remain gt 0 or $allow_edit}
                      <div class="price-row">
                        <span class="product-price-text">
                        {currency value=$price} x <span id="cart-{$product.cartid}" class="amount">{$product.amount}</span><span class="hidden-control">{if $active_modules.Egoods and $product.distribution}<input type="hidden"{else}<input type="text"{/if} data-inline="true" size="1" name="quantity" id="qty_{$product.wishlistid}" value="{$product.amount}" class="amount-input" /></span> = </span>
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
                      <div class="hidden-control">
                        {include file="customer/buttons/update.tpl" type="input" additional_button_class="light-button" data_theme="a"}
                      </div>
                    </div>
                  {/if}
                  {if $product.product_options ne ""}
                    <div class="poptions-list">
                      {include file="modules/Product_Options/display_options.tpl" options=$product.product_options}
                      {if $giftregistry eq "" and $source ne "giftreg"}
                        <div class="hidden-control">
                          {include file="customer/buttons/edit_product_options.tpl" target="wishlist" data_theme="e" id="`$product.wishlistid`&amp;eventid=`$eventid`" style="link"}
                        </div>
                      {/if}
                    </div>
                  {/if}
                  {if $eventid gt 0}
                    <p class="whishlist-purchased-row">
                      {if $product.amount_remain gt 0}
                        {$lng.lbl_giftreg_items_purchased|substitute:"ordered":$product.amount_requested:"bought":$product.amount_purchased:"remain":$product.amount_remain}
                      {else}
                        {$lng.lbl_giftreg_all_items_purchased}
                      {/if}
                    </p>
                  {/if}
                  {if not ((($wl_products and $product.amount_purchased lt $product.amount and $product.avail gt "0") or $config.General.unlimited_products eq "Y") or $main_mode eq "manager" or $product.product_type eq "C") and $product.amount gt $product.avail}
                    <strong>{$lng.txt_out_of_stock}</strong>
                  {/if}

                  {if ((($wl_products and ($product.amount_purchased lt $product.amount or $eventid eq "") and $product.avail gt "0") or $config.General.unlimited_products eq "Y") or $main_mode eq "manager" or $product.product_type eq "C") and ($login or $giftregistry ne "")}
                    {if $giftregistry eq ""}
                      {include file="customer/buttons/add_to_cart.tpl" href="javascript: self.location = 'cart.php?mode=wl2cart&amp;wlitem=`$product.wishlistid`&amp;amount='+$('#qty_`$product.wishlistid`').val()" additional_button_class="light-button"}
                    {else}
                      {include file="customer/buttons/add_to_cart.tpl" href="javascript: self.location = 'cart.php?mode=wl2cart&amp;fwlitem=`$product.wishlistid`&amp;eventid=`$eventid`&amp;amount='+$('#qty_`$product.wishlistid`').val()" additional_button_class="light-button"}
                    {/if}
                  {/if}

                  {if $active_modules.Gift_Registry}
                    {include file="modules/Gift_Registry/giftreg_wishlist.tpl" wlitem_data=$product form_name="update`$product.wishlistid`_form"}
                  {/if}
                </div>
                <div class="clearing"></div>
              </div>
            </form>
          </div>
          <span onclick="$.mobile.changePage('product.php?productid={$product.productid}');" class="ui-li-link-alt ui-btn ui-btn-icon-notext ui-btn-up-d" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-icon="false" data-iconpos="notext" data-theme="d" title="{$product.product}">
            <span class="ui-btn-inner">
              <span class="ui-btn-text"></span>
              <span onclick="$.mobile.changePage('product.php?productid={$product.productid}');" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-r" data-iconpos="notext" data-theme="a" title="{$product.product}" class="ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all ui-btn-icon-notext">
                <span class="ui-btn-inner ui-btn-corner-all">
                  <span class="ui-btn-text"></span>
                  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span>
                </span>
              </span>
            </span>
          </span>
        </li>
      {/foreach}
    </ul>
  {/if}
  {if $active_modules.Gift_Certificates}
    {include file="modules/Gift_Certificates/gc_cart.tpl" giftcerts_data=$wl_giftcerts}
  {/if}
</div>
{if $giftregistry eq "" and $source ne "giftreg"}
  {include file="customer/buttons/button.tpl" button_title=$lng.lbl_wl_clear href="`$script_name`.php?mode=wlclear"}
  <div class="clearing"></div>
  <br />
  <div class="ui-body ui-body-b">
    <form method="post" action="{$script_name}.php" name="sendall_form">
      <input type="hidden" name="mode" value="send_friend" />
      <input type="hidden" name="action" value="entire_list" />
      <h3>{$lng.lbl_send_entire_wishlist}</h3>
      <input placeholder="{$lng.lbl_email}"type="text" id="sendall_form-friend_email" class="input-email input-required" name="friend_email" />
      {include file="customer/buttons/button.tpl" button_title=$lng.lbl_send type="input"}
    </form>
  </div>
{/if}
{else}
  {$lng.lbl_wl_empty}
{/if}
