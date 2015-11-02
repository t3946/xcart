{* $Id: index.tpl,v 1.0.0.0 2012/06/12 10:41:21 kirill Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>
{section name=position loop=$location step=-1}
{$location[position].0|strip_tags|escape}
{if not %position.last%} :: {/if}
{/section}
</title>
{include file="meta.tpl" }
{* <link rel="shortcut icon" href="{$ImagesDir}/favicon.ico" type="image/vnd.microsoft.icon" /> *}
{*<link rel="shortcut icon" href="http://www.artistsupplysource.com/image.php?id=0&amp;type=F" type="image/vnd.microsoft.icon" />*}
<link rel="shortcut icon" href="http://www.artistsupplysource.com/skin1_kolin/images/S3-favicon.png" type="image/vnd.microsoft.icon" />
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<link rel="stylesheet" href="{$SkinDir}/skin1_index.css" />
</head>
<body>
<script type="text/javascript">
var search_all_website_transfer_from_sku_search = '{$config.Search_All.search_all_website_transfer_from_sku_search}';
var arg_sku = '{$smarty.get.sku}';
{literal}

  function google_custom_search(control) {
    $('#google_search_result_block').hide();

    control.setSearchCompleteCallback(control, function(el) {
        $('#content').hide();
        $('#google_search_result_block').show();
    });
    
    $('.gsst_a .gscb_a').live('click', function() {
        $('#google_search_result_block').hide();
        $('#content').show();
    });

/*    $('td.gsib_a input').css('margin', '4px'); */
    
  }
  var inputQuery = '';
  google.load('search', '1', {language : 'en', style : google.loader.themes.V2_DEFAULT});
  google.setOnLoadCallback(function() {
    var customSearchOptions = {};
    customSearchOptions['adoptions'] = {'layout': 'noTop'};
    var customSearchControl = new google.search.CustomSearchControl('{/literal}{$config.Search_All.search_all_website_gcs_id}{literal}', customSearchOptions);
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
    var options = new google.search.DrawOptions();
    options.setSearchFormRoot('cse-search-form');
    options.setAutoComplete(true);
    customSearchControl.setAutoCompletionId('{$config.Search_all.search_all_website_gcs_id}');
    customSearchControl.setSearchStartingCallback(this, function (control, searcher, query) {
        var expSKU = /^[a-z]{3}-/i;
        if (inputQuery != query && expSKU.test(query)) { 
            control.cancelSearch();
            $.get('index.php', 'sku=' + query + '&mode=check_all', function (ans) {
                if (search_all_website_transfer_from_sku_search == "Y" && ans == 0) {
                    inputQuery = query;
                    control.execute(query);
                } else {
                    window.location = 'index.php?mode=search&sku=' + query;
                }
            }); 
        }
    });
    customSearchControl.draw('cse', options);
    customSearchControl.prefillQuery(arg_sku);
    google_custom_search(customSearchControl);
  }, true);
{/literal}
</script>
<table width="960" align="center" cellspacing="0" cellpadding="0">
<tr>
<td>
<div class="page" style="background: #ffffff;">
  <div class="wrap">
  
    <div class="header">
        <div class="tabs">
            <a href="{$xcart_web_dir}/index.php"><span>{$lng.lbl_list_of_stores}</span></a>
            {foreach item=v from=$tabs}
            <a href="{$xcart_web_dir}/index.php?pageid={$v.pageid}"><span>{$v.title}</span></a>
            {/foreach}
        </div>
        <div class="search">
            <table class="search-table">
              <tr>
                <td>
{if $smarty.get.pageid ne ""}
<a href="index.php">
{/if}
<img alt="{$config.Search_All.search_all_website_name}" class="logo" src="{$ImagesDir}/S3-Stores-Logo-S2.png" />
{if $pageid ne ""}
</a>
{/if}
		</td>
                <td width="100%"><div id="cse-search-form">{$lng.lbl_loading}</div></td>
              <tr>
            </table>
        </div>
    </div>
    
    <div class="main">
        <div id="google_search_result_block">
            <div id="cse" style="width:100%;"></div>
        </div>
        <div id="content">
        {if $sf_links ne '' && $config.Search_All.search_all_website_number_columns > 0}

		<table cellspacing="20" cellpadding="5" align="center" style="background: #ffffff;">
		{assign var=cell_counter value=0}
		{foreach from=$sf_links item=v}
		{if $cell_counter eq "0"}
		<tr>
		{/if}
		{assign var=cell_counter value=$cell_counter+1}

		<td width="33%">
		{if $v.storefrontid gte "0"}
		<a href="http://{$v.domain}" target="_blank"><img src="http://www.artistsupplysource.com/image.php?id={$v.storefrontid}&amp;type=S" alt="" style="border: #F0F0F0 1px solid; box-shadow: 5px 5px 5px 0  #cccccc;" /></a>
		{else}
		{$v.name}
		{/if}
		</td>

		{if $cell_counter eq "3"}
		</tr>
		{assign var=cell_counter value=0}
		{/if}
		{/foreach}

		{if $cell_counter gt "0"}
		<td {if $cell_counter eq "1"}colspan="2"{/if} width="*"></td></tr>
		{/if}

		</table>

	{elseif $stock_availability_page eq "Y"}
		{include file="customer/main/stock_availability.tpl"}
	{elseif $stock_availability_page eq "sent"}
		<div class="confirmation-hedgehog">
		    <img src="{$ImagesDir}/confirmation-hedgehog.png">
		    <p>Survey data received. Thank you very much for your input!</p>
		    <p>We appreciate your support!</p>
		</div>
        {elseif $page_content ne ""}
            <h1>{$page_data.title}</h1>
            {$page_content}
        {elseif $mode eq "search"}
            {include file="customer/main/products.tpl" products=$products}
            <br />
            {include file="customer/main/navigation.tpl"}
        {/if}
        </div>
    </div>
    
  </div>
</div>
{assign var="year_end" value=$smarty.now|date_format:"%Y"}
<div class="footer">
    <span class="copyright">{$lng.txt_copyright|substitute:"year_start":$config.Search_All.search_all_website_year:"year_end":$year_end}</span>
    &nbsp;&nbsp;
    <a href="http://www.artistsupplysource.com/help.php?section=conditions" class="NavigationPath">{$lng.lbl_terms_n_conditions}</a> | <a href="http://www.artistsupplysource.com/help.php?section=business" class="NavigationPath">{$lng.lbl_privacy_statement}</a>
</div>
</td>
</tr>
</table>
</body>
</html>
