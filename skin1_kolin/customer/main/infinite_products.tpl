
{* ---- 
<br />
{if $POST_VARS ne ""}
{foreach from=$POST_VARS item=v key=k}
{$k} ---- {$v}<br />
{/foreach}
{/if}
  ---- *}

{if $show_next_products eq "Y" }

	<span style="display: none;">{$ajax_load_time}</span>

	Page: {$ajax_navigation_page}

	<br />
	{if $total_items gt "1"}


	  {if $ga_page_name ne "" && $products ne ""}
<script>
//<![CDATA[
                {foreach from=$products item=v key=k}


                 {if $N_key eq ""}
                        {if $first_item ne "" && $first_item gt 0}
                         {math assign="N_key" equation="x-1" x=$first_item}
                        {else}
                                {assign var="N_key" value="0"}
                        {/if}
                 {/if}
                 {math assign="N_key" equation="x+1" x=$N_key}


ga('ec:addImpression', {ldelim}
  'id': '{$v.productid}',                   // Product details are provided in an impressionFieldObject.
  'name': '{$v.product|escape:quotes}',
  'category': '{$v.category|escape:quotes}',
  'brand': '{$v.brand|escape:quotes}',
  'list': '{$ga_page_name}',
  'price': {$v.price},
  'position': {$N_key}                     // 'position' indicates the product position in the list.
{rdelim});
                {/foreach}
ga('send', 'pageview');
//]]>
</script>
          {/if}




		{*
		{$lng.txt_N_results_found|substitute:"items":$total_items}<br />
		*}
		{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
	{elseif $total_items eq "0"}
		{$lng.txt_N_results_found|substitute:"items":0}
	{/if}

	{if $products_template eq "products_new_style"}
		{include file="customer/main/products_new_style.tpl"}
	{else}
		{include file="customer/main/products.tpl"}
	{/if}
{else}

	{if $ajax_navigation_page eq ""}
        	{assign var="ajax_navigation_page" value="1"}
	{/if}

	{math equation="page+1" page=$ajax_navigation_page assign="ajax_navigation_page_next"}

	{if $last_item lt $total_items}
		<div class="load_more_wrapper">
			<span class="infinte_scroll_span cidev_new_button cidev_new_white">
				<div class="infinte_scroll_link" data-href="infinite_products.php?ajax_navigation_page_next={$ajax_navigation_page_next}&cat={$cat}&cidev_filter_mode=load_more_products"
						id="lb_LoadMore_button_text_{$ajax_navigation_page_next}">{$lng.lb_LoadMore_button_text}</div>
			</span>

		</div>
	{/if}

{/if}
