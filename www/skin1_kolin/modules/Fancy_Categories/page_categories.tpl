{* $Id: categories.tpl,v 1.6 2006/03/17 12:30:35 svowl Exp $ *}
{if $fancy_cat_prefix eq ''}{assign var="fancy_cat_prefix" value="cat"}{/if}

<script type="text/javascript">
<!--
{if $config.Fancy_Categories.fancy_cache eq 'Y'}
var fcBlockHref = "{$var_dirs_web.cache}/fc.{$config.Fancy_Categories.fancy_categories_skin}.{$user_account.membershipid|default:0}.{$shop_language}.__CAT__.js";
{else}
var fcBlockHref = "{$current_location}/fcategory.php?membershipid={$user_account.membershipid|default:0}&code={$shop_language}&cat=__CAT__";
{/if}
var fcLinkHref = "{$current_location}/home.php?cat=";
var catPrefix = "{$fancy_cat_prefix}";
var skinWebPath = "{$fc_skin_web_path}";

var fcConfig = new Object();
fcConfig.download = {if $config.Fancy_Categories.fancy_download eq 'Y'}true{else}false{/if};
fcConfig.preload = true;
fcConfig.js = {if $config.Fancy_Categories.fancy_js eq 'Y'}true{else}false{/if};
fcConfig.delay = 500;
fcConfig.x_offset = -5;
fcConfig.y_offset = 5;
{foreach from=$fc_config item=v key=k}
{assign var="value" value=$config.Fancy_Categories[$v.name]}
{if $v.type eq 'numeric'}
fcConfig.{$k} = {$value};
{elseif $v.type eq 'multiselector'}
fcConfig.{$k} = new Array({foreach from=$value key=vk item=vv}{if $vk > 0},{/if}{$vv}{/foreach});
{elseif $v.type eq 'checkbox'}
fcConfig.{$k} = {if $value eq 'Y'}true{else}false{/if};
{else}
fcConfig.{$k} = "{$value|replace:'"':'\"'}";
{/if}
{/foreach}

var lbl_loading_menu = "{$lng.lbl_loading_menu|default:"Loading ..."}";
-->
</script>

{include file="main/include_js.tpl" src="`$fcat_module_path`/page_layers.js"}
{include file="`$fc_skin_path`/fancy_page_categories.tpl"}
