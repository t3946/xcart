

{include file="meta_titles.tpl" }

<!-- vewport test -->
<meta name="viewport" content="width=device-width, initial-scale=1"/>

{include file="presets_js.tpl"}

{if $config.Adaptives.isJS eq '' && $config.Adaptives.is_first_start eq 'Y'}
    <script type="text/javascript">
        <!--
        var usertype = "{$usertype}";
        -->
    </script>
    <script id="adaptives_script" type="text/javascript" language="JavaScript 1.2"></script>

{/if}

{if $usertype eq "C" && ($product.robots_noindex eq "Y" || $current_category.prevent_index_category_page eq "Y" || $brand.prevent_search_indexing_brand_page eq "Y")}
    <meta name="robots" content="noindex">
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



{*
{/if}
*}
{* <script src="{$SkinDir}/jquery-1.4.3.min.js" type="text/javascript"></script> *}

{*temporary (until solid update) insertion of Facebook tracking code*}
