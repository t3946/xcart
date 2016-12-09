{* $Id: meta.tpl,v 1.26.2.1 2006/10/10 07:35:18 max Exp $ *}
<meta http-equiv="Content-Type" content="text/html; charset={$default_charset|default:"iso-8859-1"}" />

<!-- Google verification META tags -->
<meta name="google-site-verification" content="PK6Exg58lxvKvOxDTtMymHgTCmUipFuJS9O9ZrYYiVg" />
<meta name="google-site-verification" content="6k-TabU_BDiTSvqSlFcEi8vkUrUObseKUFaOWlJJ1E4" />
<meta name="google-site-verification" content="MYF20ERG7ywK7wPxsqofeXlj-sDmTMYUUsbhTC6NYKo" />
<meta name="google-site-verification" content="XyLJfEHvSqDZ9w6AoydM87Zy-1FWNhqOewD9jzJaKjI" />
<meta name="google-site-verification" content="tj9nRSIwEyGYhijxN1_IoXNVDQ_PNzmdi6-vgAW9GXQ" />
<meta name="google-site-verification" content="EKTx1KNnsWDhqFHJAIvxYPbtW3N16DVQLIcHy6gkAOw" />
<meta name="google-site-verification" content="h2qdwaSe3hT0TwJm717fc_5U5StP_sGhE1JP2xgm8UA" />
<meta name="google-site-verification" content="H95zBo19LBNZGR4ff3eO2y09A1Es5xke3St2yVxvZFs" />
<meta name="google-site-verification" content="Nux2sodJiVrMIFGY7YfCfZWD2EMhE8OEEMQZPcep-9g" />
<meta name="google-site-verification" content="vM3-Elmvi0TR9VO_WAvobjwmH4o7PhfppZ9BdKb1PDQ" />

<!-- Pinterest verification META tags-->
<meta name="p:domain_verify" content="5ff39d33efcb0710fb45e8addaf474e5"/>

<!-- Google verification META tags -->

<meta name="AB_relations" content="{$variant_id_for_point10}" />
<meta name="AB_search" content="{$variant_id_for_point11}" />

<script type="text/javascript" lang="Javascript" id="sorting-info">
    console.groupCollapsed('Modification category output');
    console.group('A/B');
    console.log('AB relations',{$variant_id_for_point10});
    console.log('AB search',{$variant_id_for_point11});
    console.groupEnd();

{if $variant_id_for_point10 or $variant_id_for_point11}
    {if $t2_arr}
    console.group('Founded in last search');
    console.table({$t2_arr});
    console.groupEnd();
    {/if}

    {if $t1_arr}
    console.group('Relations for last viewed');
    console.table({$t1_arr});
    console.groupEnd();
    {/if}

    {if $t3_arr}
    console.group('Append in sorting');
    console.table({$t3_arr});
    console.groupEnd();
    {/if}

    console.groupEnd();

{/if}
</script>


<!-- bench time -->
<meta name="{$bench_name}" content="{$bench_time}" />

<!-- vewport test -->
<meta name="viewport" content="width=device-width, initial-scale=2"/>

{include file="presets_js.tpl"}

{if (($main eq "product" || $main eq "fast_lane_checkout") || $usertype ne "C")}
{* igor_async *}
{include file="main/include_js.tpl" src="common.js"} 
{/if}

{if $config.Adaptives.isJS eq '' && $config.Adaptives.is_first_start eq 'Y'}
<script type="text/javascript">
<!--
var usertype = "{$usertype}";
-->
</script>
<script id="adaptives_script" type="text/javascript" language="JavaScript 1.2"></script>

{* igor_async {include file="main/include_js.tpl" src="browser_identificator.js"} *}

{/if}

{if $usertype eq "C" && ($product.robots_noindex eq "Y" || $current_category.prevent_index_category_page eq "Y" || $brand.prevent_search_indexing_brand_page eq "Y")}
<meta name="robots" content="noindex">
{/if}

{if $usertype eq "P" or $usertype eq "A"}
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
{else}
{assign var="_meta_descr" value=""}
{assign var="_meta_keywords" value=""}
{if $product.meta_descr ne "" and $config.SEO.include_meta_products eq "Y"}
{assign var="_meta_descr" value="`$product.meta_descr`"}
{assign var="_meta_keywords" value="`$product.meta_keywords`"}
{/if}
{if $current_category.meta_descr ne "" and $config.SEO.include_meta_categories eq "Y" and !$product.productid}
{assign var="_meta_descr" value="$_meta_descr`$current_category.meta_descr`"}
{assign var="_meta_keywords" value="$_meta_keywords`$current_category.meta_keywords`"}
{/if}
{if $brand.meta_descr ne "" && $config.Brands.include_meta_brands eq "Y"}
{assign var="_meta_descr" value="$_meta_descr`$brand.meta_descr`"}
{assign var="_meta_keywords" value="$_meta_keywords`$brand.meta_keywords`"}
{/if}
{if $_meta_descr eq ''}
{assign var="_meta_descr" value=" "}
{/if}
{if $_meta_keywords eq ''}
{assign var="_meta_keywords" value="$_meta_keywords`$brand.meta_keywords` "}
{/if}
{assign var="_meta_descr" value="$_meta_descr`$config.SEO.meta_descr`"}
{assign var="_meta_keywords" value="$_meta_keywords`$config.SEO.meta_keywords`"}

{if $config.Company.cidev_keywords ne "" && (($main eq "catalog" && $current_category.category eq "") || ($_meta_keywords eq "")) }
{assign var="_meta_keywords" value=$config.Company.cidev_keywords}
{/if}

{if $config.Company.cidev_description ne "" && (($main eq "catalog" && $current_category.category eq "") || ($_meta_descr eq "")) }
{assign var="_meta_descr" value=$config.Company.cidev_description}
{/if}

{if $main eq "product"}

	{if $product.map_price gt $product.taxed_price}
		{assign var="meta_current_price" value=$product.map_price}
	{else}
		{assign var="meta_current_price" value=$product.taxed_price}
	{/if}

        {if $product.seo_meta_descr ne ""}
                <meta name="description" content="{$product.seo_meta_descr|truncate:"500":"...":false|escape|strip}" />
        {else}
                <meta name="description" content="Buy online or call {$config.Company.company_phone}. {$_meta_descr|truncate:"500":"...":false|escape|strip}" />
        {/if}

{elseif $main eq "catalog" && $current_category.category ne ""}

	{if $current_category.meta_descr_orig ne ""}
		<meta name="description" content="{$current_category.meta_descr_orig|escape|strip}" />
	{elseif $current_category.description ne ""}
                <meta name="description" content="{$current_category.description|escape|strip}" />
        {else}
                <meta name="description" content="{$_meta_descr|truncate:"500":"...":false|escape|strip}" />
        {/if}

	{if $current_category.meta_keywords_orig ne ""}
		<meta name="keywords" content="{$current_category.meta_keywords_orig|truncate:"500":"":false|escape|strip}" />
	{/if}
{else}
		<meta name="description" content="{$_meta_descr|truncate:"500":"...":false|escape|strip}" />
{/if}

 {if $config.Company.config_keywords_meta_tag ne "" && (($main eq "catalog" && $current_category.category eq ""))}
  {assign var="_meta_keywords" value=$config.Company.config_keywords_meta_tag}
  <meta name="keywords" content="{$_meta_keywords|truncate:"500":"":false|escape|strip}" />
 {else}

 {*
 <meta name="keywords" content="{$_meta_keywords|truncate:"500":"":false|escape|strip}" />
 *}
 {/if}
{/if}
{if $webmaster_mode eq "editor"}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var store_language = "{if ($usertype eq "P" or $usertype eq "A") and $current_language ne ""}{$current_language}{else}{$store_language}{/if}";
var catalogs = new Object();
catalogs.admin = "{$catalogs.admin}";
catalogs.provider = "{$catalogs.provider}";
catalogs.customer = "{$catalogs.customer}";
catalogs.partner = "{$catalogs.partner}";
catalogs.images = "{$ImagesDir}";
catalogs.skin = "{$SkinDir}";
var lng_labels = [];
{foreach key=lbl_name item=lbl_val from=$webmaster_lng}
lng_labels['{$lbl_name}'] = '{$lbl_val}';
{/foreach}
var page_charset = "{$default_charset|default:"iso-8859-1"}";
-->
</script>
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/editor_common.js"></script>
{if $user_agent eq "ns"}
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/editorns.js"></script>
{else}
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/editor.js"></script>
{/if}
{/if}

{*
{if $main eq "product"}
*}
{* igor_async *}
 <script src="{$SkinDir}/jquery.min.1.7.1.js" type="text/javascript"></script>
{*
{/if}
*}
{* <script src="{$SkinDir}/jquery-1.4.3.min.js" type="text/javascript"></script> *}

{*temporary (until solid update) insertion of Facebook tracking code*}
