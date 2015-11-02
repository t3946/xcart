{* $Id: home.tpl,v 1.88.2.4 2006/10/13 10:41:21 svowl Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link rel="shortcut icon" href="{$ImagesDir}/favicon.ico" type="image/vnd.microsoft.icon" />
{if $printable ne ''}
{include file="customer/home_printable.tpl"}
{else}
{config_load file="$skin_config"}
<html>
<head>
<title>
{if $config.SEO.page_title_format eq "A"}
{section name=position loop=$location}
{$location[position].0|strip_tags|escape}
{if not %position.last%} :: {/if}
{/section}
{else}
{section name=position loop=$location step=-1}
{$location[position].0|strip_tags|escape}
{if not %position.last%} :: {/if}
{/section}
{/if}
</title>
{include file="meta.tpl" }
<script src="{$SkinDir}/jquery-1.4.3.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/jquery.tooltip.js" type="text/javascript"></script>
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
<link rel="stylesheet" href="{$SkinDir}/jquery.tooltip.css" />
<!--[if IE]>
	<link rel="stylesheet" href="{$SkinDir}/skin1.IE.css" type="text/css" media="all" />
<![endif]-->
</head>
<body{$reading_direction_tag}{if $body_onload ne ''} onload="javascript: {$body_onload}"{/if}>
{literal}
<style>
#tooltip {
    max-width: {/literal}{$config.Product_Page.max_width_map_text}{literal}px;
}
</style>
{/literal}
{include file="rectangle_top.tpl" }
{include file="head.tpl" }
{if $active_modules.SnS_connector}
{include file="modules/SnS_connector/header.tpl"}
{/if}
<!-- main area -->
{include file="customer/search.tpl"}
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="VertMenuLeftColumn">
<br>
{if $categories ne "" and ($active_modules.Fancy_Categories ne "" or $config.General.root_categories eq "Y" or $subcategories ne "")}
{include file="customer/categories.tpl" }
<br />
{/if}
{if $active_modules.Bestsellers ne "" and $config.Bestsellers.bestsellers_menu eq "Y"}
{include file="modules/Bestsellers/menu_bestsellers.tpl" }
{/if}
{*if $active_modules.Brands ne "" and $config.Brands.brands_menu eq "Y"}
{include file="modules/Brands/menu_brands.tpl" }
{elseif $active_modules.Manufacturers ne "" and $config.Manufacturers.manufacturers_menu eq "Y"}
{include file="modules/Manufacturers/menu_manufacturers.tpl" }
{/if*}
{*include file="customer/special.tpl"*}
{if $active_modules.Survey && $menu_surveys}
{foreach from=$menu_surveys item=menu_survey}
{include file="modules/Survey/menu_survey.tpl"}
<br />
{/foreach}
{/if}
{*include file="help.tpl" *}
{*include file="customer/search.tpl"*}
<img src="{$ImagesDir}/spacer.gif" width="150" height="1" alt="" />
</td>
<td valign="top">
<!-- central space -->
{if !($main eq "catalog" && $current_category.category eq "" && $current_seed_category eq '')}
    {include file="location.tpl"}
{/if}
<br>

{if $gcheckout_enabled and $main ne "cart" and $main ne "checkout" and $main ne "anonymous_checkout" and $main ne "order_message"}
<div align="right">{include file="modules/Google_Checkout/gcheckout_button.tpl"}</div>
{/if}

{include file="dialog_message.tpl"}

{if $active_modules.Special_Offers ne ""}
{include file="modules/Special_Offers/customer/new_offers_message.tpl"}
{/if}

<table cellpadding="0" cellspacing="0" width="100%">
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="{$ImagesDir}/top-left.jpg"></td>
<td style="background-color: #fbfbf3; text-align: center;" height="10" width="100%"><img src="{$ImagesDir}/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="{$ImagesDir}/top-right.jpg"></td></tr>
<tr>
<td bgcolor="#fbfbf3" colspan=3 style="padding-left: 10px; padding-right: 10px;">
<div id="google_search_result_block">
{$config.Search_products.search_products_result_code}
</div>
<div id="main">
{include file="customer/home_main.tpl"}
</div>
</td>
</tr>
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="{$ImagesDir}/bottom-left.jpg"></td>
<td width="100%" style="background-color: #fbfbf3; text-align: center;"><img src="{$ImagesDir}/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="{$ImagesDir}/bottom-right.jpg"></td></tr>
</table>

{*include file="customer/home_main.tpl"*}


<!-- /central space -->
</td>
<td class="VertMenuRightColumn">
<br>
{if $active_modules.SnS_connector && $config.SnS_connector.sns_display_button eq 'Y' && $sns_collector_path_url ne ''}
{include file="modules/SnS_connector/button.tpl"}
<br />
{/if}
{if $active_modules.Feature_Comparison ne "" && $comparison_products ne ''}
{include file="modules/Feature_Comparison/product_list.tpl" }
<br />
{/if}
{include file="customer/menu_cart.tpl" }
{*if $login eq "" }
{include file="auth.tpl" }
{else}
{include file="authbox.tpl" }
{/if*}
{if $active_modules.XAffiliate ne ""}
<br />
{include file="partner/menu_affiliate.tpl" }
{/if}
{if $active_modules.Interneka ne ""}
<br />
{include file="modules/Interneka/menu_interneka.tpl" }
{/if}
<!--br /-->
{include file="poweredby.tpl" }
<br />
{include file="help.tpl"}
<br />
{include file="customer/special.tpl"}
<br />
<br />
<div style="padding-left: 8px"><a href="{$xcart_web_dir}/home.php?cat=248"><img src="{$ImagesDir}/Art-Brushes-Ad.jpg" alt="" /></a></div>
<br />
{if $active_modules.Mailchimp_Subscription}
{include file="modules/Mailchimp_Subscription/news.tpl" }
{else}
{include file="news.tpl" }
{/if}
<br>
<img src="{$ImagesDir}/spacer.gif" width="150" height="1" alt="" />
</td>
</tr>
</table>
{if $active_modules.Brands ne "" and $config.Brands.brands_menu eq "Y" and $main ne "sitemap_customer"  }
<div style="margin: 20px 10px 0px 10px; padding: 8px; background-color: #EFEDDF;">{include file="modules/Brands/menu_brands_footer.tpl"}</div>
{/if}
{include file="rectangle_bottom.tpl" }
{include file="ga_code.tpl" }
{if $config.Product_Page.map_bridge_text_background ne ''}
{literal}
<style>
#tooltip, .tooltip_helper {
	background-color: #{/literal}{$config.Product_Page.map_bridge_text_background};{literal}
}
</style>
{/literal}
{/if}
<script type="text/javascript">
//<![CDATA[
var txt_tooltip_helper = '{$map_bridge_mouseover_text|escape:javascript}';
{literal}
$(document).ready(function() {
	$('.map_price_help').tooltip({
		delay: 0,
		showURL: false,
		track: false,
		bodyHandler: function() {
			return txt_tooltip_helper;
	}});
});
{/literal}
//]]>
</script>
</body>
</html>
{/if}
