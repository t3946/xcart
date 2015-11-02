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
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
</head>
<body{$reading_direction_tag}{if $body_onload ne ''} onload="javascript: {$body_onload}"{/if}>
{include file="rectangle_top.tpl" }
{include file="head.tpl" }
{if $active_modules.SnS_connector}
{include file="modules/SnS_connector/header.tpl"}
{/if}
<!-- main area -->
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
{if $active_modules.Brands ne "" and $config.Brands.brands_menu eq "Y"}
{include file="modules/Brands/menu_brands.tpl" }
{elseif $active_modules.Manufacturers ne "" and $config.Manufacturers.manufacturers_menu eq "Y"}
{include file="modules/Manufacturers/menu_manufacturers.tpl" }
{/if}
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
{if $main eq "catalog" and $current_category.category eq ""}{else}
{include file="location.tpl"}{/if}
{*include file="location.tpl"*}
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
<td bgcolor="#fbfbf3" colspan=3 style="padding-left: 10px; padding-right: 10px;">{include file="customer/home_main.tpl"}</td>
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
<br />
{if $login eq "" }
{include file="auth.tpl" }
{else}
{include file="authbox.tpl" }
{/if}
{if $active_modules.XAffiliate ne ""}
<br />
{include file="partner/menu_affiliate.tpl" }
{/if}
{if $active_modules.Interneka ne ""}
<br />
{include file="modules/Interneka/menu_interneka.tpl" }
{/if}
<br />
{include file="poweredby.tpl" }
<br />
{include file="help.tpl"}
</br>
{include file="customer/special.tpl"}
<br />
<br />
<div style="padding-left: 8px"><a href="{$xcart_web_dir}/home.php?cat=248"><img src="{$ImagesDir}/Art-Brushes-Ad.jpg" alt="" /></a></div>
<br />
{include file="news.tpl" }
<br>
<img src="{$ImagesDir}/spacer.gif" width="150" height="1" alt="" />
</td>
</tr>
</table>
{include file="rectangle_bottom.tpl" }
{include file="ga_code.tpl" }
</body>
</html>
{/if}
