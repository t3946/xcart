{if $show_next_products eq "Y" }

	Page: {$ajax_navigation_page}

	<br />
	{if $total_items gt "1"}
		{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
	{elseif $total_items eq "0"}
		{$lng.txt_N_results_found|substitute:"items":0}
	{/if}

<ul data-role="listview" data-type="products-list" data-divider-theme="c" class="ui-listview">
      {foreach from=$products item=product}

{if $product.new_notify_in_stock_price ne ""}
        {assign var="current_price" value=$product.new_notify_in_stock_price}
{else}
        {if $product.map_price gt $product.taxed_price}
                {assign var="current_price" value=$product.map_price}
        {else}
                {assign var="current_price" value=$product.taxed_price}
        {/if}
{/if}

        <li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-up-c ui-btn-icon-right ui-li-has-arrow ui-li">

	<div class="ui-btn-inner ui-li"><div class="ui-btn-text">

          <a href="{$current_location}/product.php?productid={$product.productid}" class="ui-link-inherit">
            <span class="product-thumbnail">
              {include file="product_thumbnail.tpl" productid=$product.productid product=$product.product tmbn_url=$product.tmbn_url}
              <img src="{$ImagesDir}/spacer.gif" class="leveler" alt="" />
              <span class="labels">
                {if $active_modules.On_Sale}
                  {include file="modules/On_Sale/on_sale_icon_products_list.tpl" product=$product}
                {/if}
                {if $active_modules.Special_Offers and $product.have_offers}
                  <span class="so-thumb" onclick="javascript: $.mobile.changePage('offers.php?mode=product&amp;productid={$product.productid}');"></span>
                {/if}
              </span>
            </span>
            <span class="product-details">
              <span class="ui-li-heading">
                <span class="list">{$product.product|amp}</span>
                <span class="grid">{$product.product|amp|truncate:79:'...'}</span>
                {if $active_modules.New_Arrivals}
                  {include file="modules/New_Arrivals/new_arrivals_show_date.tpl" product=$product}
                {/if}
              </span>
              <span class="ui-li-desc">
                {if $config.Appearance.display_productcode_in_list eq "Y" && $product.productcode}
                  <span class="sku">{$lng.lbl_sku}: {$product.productcode|escape}</span>
                {/if}
                {if $product.product_type ne "C"}
                  {if $product.appearance.is_auction}
                    <span class="price">{$lng.lbl_enter_your_price}</span><br />
                    {$lng.lbl_enter_your_price_note}
                  {else}

{*
                    {if $product.appearance.has_price || !$product.appearance}
                      {if $product.appearance.has_market_price and $product.appearance.market_price_discount gt 0}
                        <span class="market-price">
                          {strip}
                            <span class="market-price-value">{currency value=$product.list_price}</span>
                            {if $product.appearance.market_price_discount gt 0}
                              {if $config.General.alter_currency_symbol ne ""}
                                ,
                              {/if}
                              <span class="price-save">&nbsp;{$lng.lbl_save_price} {$product.appearance.market_price_discount}%</span>
                            {/if}
                          {/strip}
                        </span>
                      {/if}
*}
                      <span class="price">
{*                        <span class="price-value">{currency value=$product.taxed_price}</span> *}
                        Price: <span class="price-value">{include file="currency.tpl" value=$current_price}</span>
{*                        <span class="market-price">{alter_currency value=$product.taxed_price}</span> *}
                      </span>

<span class="sku">
            {if $product.avail gt 0 or $config.General.unlimited_products eq "Y"}
              {$lng.lbl_in_stock_top}
            {else}
              {$lng.lbl_out_stock}
            {/if}
</span>

                      {if $product.taxes}
                        <span class="taxes">{include file="customer/main/taxed_price.tpl" taxes=$product.taxes is_subtax=true}</span>
                      {/if}
{*
                    {/if}
*}
                    {if $active_modules.Special_Offers and $product.use_special_price}
                      {include file="modules/Special_Offers/customer/product_special_price.tpl"}
                    {/if}
                  {/if}
                {/if}
              </span>
            </span>
          </a>
          {if $active_modules.Feature_Comparison && $product.fclassid gt 0 and not $featured}
            {include file="modules/Feature_Comparison/compare_checkbox.tpl" id=$product.productid assign="fcomp_checkbox"}
            {$fcomp_checkbox|replace:"fcomp-checkbox-box":"fcomp-checkbox-box left-block"}
            <div class="clearing"></div>
          {/if}

	</div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div>
        </li>
      {/foreach}
</ul>

{* --- *}
{include file="customer/main/infinite_products.tpl" show_next_products="N"}
{* --- *}

{*	{include file="customer/main/products.tpl"} *}
{else}

	{if $ajax_navigation_page eq ""}
        	{assign var="ajax_navigation_page" value="1"}
	{/if}

	{math equation="page+1" page=$ajax_navigation_page assign="ajax_navigation_page_next"}

  {if $last_item lt $total_items}
	<div id="show_next_products_block_{$ajax_navigation_page_next}" {* class="show_next_products_block_{$ajax_navigation_page_next}" *}>

<span style="width: 100%; height: 47px; font: 24px/100% Arial,Helvetica,sans-serif; padding: 0px; margin-top: 10px;" class="cidev_new_button cidev_new_white" onclick="javascript: func_load_more_products({$ajax_navigation_page_next});"><div style="padding-top: 10px; font: 24px/100% Arial,Helvetica,sans-serif; padding: 0px; margin-top: 10px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3); color: #606060;">{$lng.lb_LoadMore_button_text}</div></span>


<span style="width: 100%; height: 47px; font: 24px/100% Arial,Helvetica,sans-serif; padding: 0px; margin-top: 10px;" class="cidev_new_button cidev_new_white" onclick="javascript: jQuery('body,html').animate({ldelim}scrollTop: 0 {rdelim}, 300);"><div style="padding-top: 10px; font: 24px/100% Arial,Helvetica,sans-serif; padding: 0px; margin-top: 10px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3); color: #606060;">Back to top</div></span>


{*
<div align="left" onclick="javascript: func_load_more_products({$ajax_navigation_page_next});">{include file="modules/Xcart_Mobile/customer/buttons/button.tpl" button_title=$lng.lb_LoadMore_button_text href=""}</div>
*}

	</div>
  {/if}

{/if}
