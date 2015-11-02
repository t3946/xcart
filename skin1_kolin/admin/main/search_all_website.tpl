{* $Id: search_all_website.tpl,v 1.0.0.0 2012/06/10 08:39:27 kirill Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_search_all_website}

{capture name=dialog}
<form method="post" action="search_all_website.php">
<table cellpadding="3" cellspacing="1" width="100%" class="search_all_website_config_table">
    <tr>
        <td width="37%">{$lng.lbl_search_all_website_name}:</td>
        <td width="63%"><input type="text" name="conf[search_all_website_name]" value="{$config.Search_All.search_all_website_name}" /></td>
    </tr>
    <tr>
        <td width="37%" class="TableSubHead">{$lng.lbl_search_all_website_url}:</td>
        <td width="63%" class="TableSubHead"><input type="text" name="conf[search_all_website_url]" value="{$config.Search_All.search_all_website_url}" /></td>
    </tr>
    <tr>
        <td width="37%">{$lng.lbl_search_all_website_year}:</td>
        <td width="63%"><input type="text" name="conf[search_all_website_year]" value="{$config.Search_All.search_all_website_year}" /></td>
    </tr>
    <tr>
        <td width="37%" class="TableSubHead">{$lng.lbl_search_all_website_gcs_id}:</td>
        <td width="63%" class="TableSubHead"><input type="text" name="conf[search_all_website_gcs_id]" value="{$config.Search_All.search_all_website_gcs_id}" /></td>
    </tr>
    <tr>
        <td width="37%" class="TableSubHead">{$lng.lbl_search_all_website_transfer_from_sku_search}:</td>
        <td width="63%" class="TableSubHead"><input type="checkbox" name="conf[search_all_website_transfer_from_sku_search]" value="Y"{if $config.Search_All.search_all_website_transfer_from_sku_search eq "Y"} checked="checked"{/if}/></td>
    </tr>
    <tr>
        <td width="37%">{$lng.lbl_search_all_website_number_columns}:</td>
        <td width="63%"><input type="text" name="conf[search_all_website_number_columns]" value="{$config.Search_All.search_all_website_number_columns}" class="columns_number" /></td>
    </tr>
    <tr>
        <td width="37%" class="TableSubHead">{$lng.lbl_search_all_website_close}:</td>
        <td width="63%" class="TableSubHead"><input type="checkbox" name="conf[search_all_website_close]" value="Y"{if $config.Search_All.search_all_website_close eq "Y"} checked="checked"{/if}/></td>
    </tr>
    <tr>
        <td colspan="2"><br /><input type="submit" value="{$lng.lbl_save}" /></td>
    </tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_search_all_website content=$smarty.capture.dialog extra='width="100%"'}
