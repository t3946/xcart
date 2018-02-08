
{if $e_products_found eq "Y"}

	{include file="customer/main/products.tpl" products=$products}

	{ include file="customer/main/navigation.tpl" }

{else}
	<br />

	{include file="customer/main/new_welcome_products.tpl" new_products=$new_products}

	{include file="customer/main/featured.tpl" f_products=$f_products}

	{if $config.Company.cidev_main_page_code ne "" && ($smarty.get.page eq "0" || $smarty.get.page eq "")}
	{$config.Company.cidev_main_page_code}
	{else}
	<br />
	{/if}

	{if $active_modules.Bestsellers ne "" and $config.Bestsellers.bestsellers_menu ne "Y"}
	{include file="modules/Bestsellers/bestsellers.tpl"}
	{/if}

	{if $current_storefront eq "0"}
        	{if $current_html_banner ne ""}{include file=$current_html_banner}{/if}
	{/if}

{/if}

<br />
