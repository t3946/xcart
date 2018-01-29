{assign var='_meta_showed' value="1"}

{if $_meta_helper->compose()}
    {$_meta_helper->render()}
{else}

    <title>{strip}
            {if $brand.title ne "" && $main eq "brand_products"}
                {$brand.title}
            {else}
                {if $main eq "product" && $product.title_tag ne ""}
                    {$product.title_tag}
                {else}
                    {if $clean_url_data.resource_type eq "K" && $e_search_data.substring ne ""}
                        {if $e_search_data.orig_substring ne ""}{$e_search_data.orig_substring|stripslashes|escape}{else}{$e_search_data.substring|stripslashes|escape}{/if} at&nbsp;
                    {/if}
                    {if $config.Company.config_title_meta_tag ne "" && (($main eq "catalog" && $current_category.category eq ""))}
                        {$config.Company.config_title_meta_tag}
                    {elseif $current_category.title_tag ne "" && $main eq "catalog"}
                        {$current_category.title_tag} {*| {$location[0].0*}
                    {else}
                        {if $current_storefront == 41 && $main eq "product"}
                            {capture name=title}
                                {assign var="seo_product_title" value="`$product.product` Online | `$config.Company.company_name`"}
                                {$seo_product_title|truncate:"80":"":false|escape|strip}
                            {/capture}
                        {else}
                            {capture name=title}
                                {if $config.SEO.page_title_format eq "A"}
                                    {section name=position loop=$location}
                                        {if not %position.first%}&nbsp;::&nbsp;{/if}
                                        {$location[position].0|strip_tags|escape}
                                    {/section}
                                {else}
                                    {section name=position loop=$location step=-1}
                                        {if not %position.first%}&nbsp;::&nbsp;{/if}
                                        {$location[position].0|strip_tags|escape}
                                    {/section}
                                {/if}
                            {/capture}
                        {/if}
                        {if $config.SEO.page_title_limit <= 0}
                            {$smarty.capture.title|replace:"&amp;":"&"}
                        {else}
                            {$smarty.capture.title|replace:"&nbsp;":" "|truncate:$config.SEO.page_title_limit|replace:" ":"&nbsp;"}
                        {/if}
                    {/if}
                {/if}
            {/if}
        {/strip}</title>


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
            {assign var="_meta_descr" value="`$_meta_descr``$current_category.meta_descr`"}
            {assign var="_meta_keywords" value="`$_meta_keywords``$current_category.meta_keywords`"}
        {/if}

        {if $brand.meta_descr ne "" && $config.Brands.include_meta_brands eq "Y"}
            {assign var="_meta_descr" value="`$_meta_descr``$brand.meta_descr`"}
            {assign var="_meta_keywords" value="`$_meta_keywords``$brand.meta_keywords`"}
        {/if}

        {if $_meta_descr eq ''}
            {assign var="_meta_descr" value=" "}
        {/if}

        {if $_meta_keywords eq ''}
            {assign var="_meta_keywords" value="`$_meta_keywords``$brand.meta_keywords` "}
        {/if}

        {assign var="_meta_descr" value="`$_meta_descr``$config.SEO.meta_descr`"}
        {assign var="_meta_keywords" value="`$_meta_keywords``$config.SEO.meta_keywords`"}

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
                {if $current_storefront == 41}
                    {assign var="seo_meta_descr" value="Buy `$product.product` online at `$config.Company.company_name`. `$current_category.category` at cheap prices. Sale up to 50%"}
                    <meta name="description" content="{$seo_meta_descr|truncate:"160":"...":false|escape|strip}" />
                {else}
                    <meta name="description" content="Buy online or call {$config.Company.company_phone}. {$_meta_descr|truncate:"500":"...":false|escape|strip}" />
                {/if}
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
{/if}

{* $Id: meta.tpl,v 1.26.2.1 2006/10/10 07:35:18 max Exp $ *}
<meta http-equiv="Content-Type" content="text/html; charset={$default_charset|default:"iso-8859-1"}" />

{$config.Company.html_into_head}

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

<!-- bench time -->
<meta name="{$bench_name}" content="{$bench_time}" />
