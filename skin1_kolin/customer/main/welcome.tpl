{* $Id: welcome.tpl,v 1.28.2.1 2006/07/12 04:51:17 svowl Exp $ *}

{*
{if ($active_modules.Greet_Visitor ne "") and ($smarty.cookies.GreetingCookie ne "") and $logout_user eq ''}
{assign var="_name" value=$smarty.cookies.GreetingCookie|replace:"\'":"'"}
<h3>{$lng.lbl_welcome_back|substitute:"name":$_name} </h3>
{elseif $lng.lbl_site_title}
<h3>{$lng.lbl_welcome_to|substitute:"company":$lng.lbl_site_title}</h3>
{else}
<h3>{$lng.lbl_welcome_to} {$config.Company.company_name}</h3>
{/if}
*}

{if $e_products_found eq "Y"}

	{if $current_storefront eq "41"}
        	{include file="customer/main/products_new_style.tpl" products=$products}
	{else}
        	{include file="customer/main/products.tpl" products=$products}
	{/if}

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

{*
{if ($active_modules.Greet_Visitor ne "") and ($smarty.cookies.GreetingCookie ne "") and $logout_user eq ''}
{assign var="_name" value=$smarty.cookies.GreetingCookie|replace:"\'":"'"}
<h3>{$lng.lbl_welcome_back|substitute:"name":$_name} </h3> 
{elseif $lng.lbl_site_title}
<h3>{$lng.lbl_welcome_to|substitute:"company":$lng.lbl_site_title}</h3>
{else}
<h3>{$lng.lbl_welcome_to} {$config.Company.company_name}</h3>
{/if}
{$lng.txt_welcome}
{if $active_modules.Bestsellers ne "" and $config.Bestsellers.bestsellers_menu ne "Y"}
{include file="modules/Bestsellers/bestsellers.tpl"}
{/if}
{if $current_html_banner ne ""}{include file=$current_html_banner}{/if}
<br />
<br />
{include file="customer/main/featured.tpl" f_products=$f_products}
*}
