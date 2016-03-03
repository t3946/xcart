{*
$Id: products.tpl 78 2012-12-28 13:59:37Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $products}
  {if $active_modules.Feature_Comparison and $products_has_fclasses and not $featured}
    {include file="modules/Feature_Comparison/compare_selected_button.tpl"}
  {/if}
  <div class="content-secondary">
    <ul data-role="listview" data-type="products-list" data-divider-theme="c">
      {if $title}
        <li data-role="list-divider" role="heading">
          <h2>{$title}</h2>
        </li>
      {/if}


{if ($main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search") && $do_not_use_load_more_function ne 'Y'}


<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>


<script type="text/javascript">
//<![CDATA[
{literal}
        function func_load_more_products(ajax_navigation_page_next){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var e_products_found = '{/literal}{if $e_products_found eq "Y"}Y{/if}{literal}';
                                var cidev_filter_mode = 'load_more_products';
                                var ga_page_name = '{/literal}{if $ga_page_name ne ""}{$ga_page_name}{/if}{literal}';
        
                                if (e_products_found == "Y"){
                                        cidev_filter_mode = 'load_more_e_products';
                                }
                                
                                var cat = {/literal}{if $cat ne ""}{$cat}{else}{literal}''{/literal}{/if}{literal};

/* --- if cached --- 
                                if (cat == ''){
                                  cat = $('#hidden_cat').val();
                                }

                                if (ga_page_name == ''){
                                  ga_page_name = $('#hidden_ga_page_name').val();
                                }
 --- --- */

                                var cidev_parameters = 'cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next='+ajax_navigation_page_next+'&cat='+cat+'&ga_page_name='+ga_page_name;

//-Start-//
                                var LN_total_items = $('#LN_total_items').attr('data-value');
                                var load_next_productids = $('#load_next_productids').attr('data-value');
                                load_next_productids = load_next_productids.trim();

                                if (load_next_productids != ""){
                                        cidev_parameters = cidev_parameters + '&load_next_productids='+load_next_productids+'&total_items='+LN_total_items;
                                }
//-End-//

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("show_next_products_block_"+ajax_navigation_page_next).innerHTML=cidev_xmlHttp.responseText;

//-Start-//
                                                        $('#load_next_productids').attr('data-value','');
                                                        ajax_navigation_page_next++;
                                                        var cidev_parameters_load_next = 'mode_load_next_productids=Y&cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next='+ajax_navigation_page_next+'&cat='+cat;
                                                        func_load_more_next_productids(cidev_parameters_load_next, 'N');
//-End-//

                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                var tmp_rand = Math.random();

//alert(tmp_rand);

                                cidev_xmlHttp.open('POST','infinite_products.php?rand='+tmp_rand,true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-cache');
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-store');
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('func_load_more_products()', 1000);
                        }
        }
//-Start-//
        function func_load_more_next_productids(cidev_parameters, first_on_load){

                        if (first_on_load == "Y"){
                                var current_storefront = '{/literal}{$current_storefront}{literal}';
                                var e_products_found = '{/literal}{if $e_products_found eq "Y"}Y{/if}{literal}';
                                var cidev_filter_mode = 'load_more_products';
        
                                if (e_products_found == "Y"){
                                        cidev_filter_mode = 'load_more_e_products';
                                }
                                
                                var cat = {/literal}{if $cat ne ""}{$cat}{else}{literal}''{/literal}{/if}{literal};


//alert(cat);

/* --- if cached --- 
                                if (cat == ''){
                                  cat = $('#hidden_cat').val();
                                }
 --- --- */

                                cidev_parameters = 'mode_load_next_productids=Y&cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next=2&cat='+cat;


                        }

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){

//alert(cidev_xmlHttp.responseText);

                                                        $('#load_next_productids').attr('data-value',cidev_xmlHttp.responseText);
                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                var tmp_rand = Math.random();

                                cidev_xmlHttp.open('POST','infinite_products.php?rand='+tmp_rand,true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-cache');
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-store');
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('func_load_more_next_productids()', 1000);
                        }
        }
//-End-//

{/literal}
//]]>
</script>


<div style="display: none;" id="load_next_productids" data-value="{include file="customer/main/infinite_products_load_next_productids.tpl"}"></div>
<div style="display: none;" id="LN_total_items" data-value="{$total_items}"></div>

{*
<div style="display: none;" id="hidden_cat" data-value="{$cat}"></div>
<div style="display: none;" id="hidden_ga_page_name" data-value="{$ga_page_name}"></div>
*}


<script type="text/javascript">
//<![CDATA[
func_load_more_next_productids('','Y');
//]]>
</script>

{/if}





      {foreach from=$products item=product}


 {if $N_key eq ""}
        {if $first_item ne "" && $first_item gt 0}
                 {math assign="N_key" equation="x-1" x=$first_item}
        {else}
                {assign var="N_key" value="0"}
        {/if}
 {/if}
 {math assign="N_key" equation="x+1" x=$N_key}


{if $product.new_notify_in_stock_price ne ""}
        {assign var="current_price" value=$product.new_notify_in_stock_price}
{else}
        {if $product.map_price gt $product.taxed_price}
                {assign var="current_price" value=$product.map_price}
        {else}
                {assign var="current_price" value=$product.taxed_price}
        {/if}
{/if}

        <li>
          <a {include file="on_product_click.tpl"} href="{$current_location}/product.php?productid={$product.productid}">
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
        </li>
      {/foreach}
    </ul>

{* --- *}
{include file="customer/main/infinite_products.tpl" show_next_products="N"}
{* --- *}

    <div class="clearing"></div>
  </div>

  {if $active_modules.Feature_Comparison and $products_has_fclasses and not $featured}
    {include file="modules/Feature_Comparison/compare_selected_button.tpl"}
  {/if}

{/if}
