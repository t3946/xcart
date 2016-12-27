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
        var expSKU = /^[a-z0-9]{3,4}-/i;
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


<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

        function func_load_more_products(ajax_navigation_page_next){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_filter_mode = 'load_more_products_SKU';
        
                                
                                var sku = '{/literal}{if $smarty.get.sku ne ""}{$smarty.get.sku}{/if}{literal}';

                                var cidev_parameters = 'cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next='+ajax_navigation_page_next+'&sku='+sku;

//-Start-//
                                var LN_total_items = $('#LN_total_items').attr('data-value');
                                var load_next_productids = $('#load_next_productids').attr('data-value');
                                load_next_productids = load_next_productids.trim();

                                if (load_next_productids != ""){
                                        cidev_parameters = cidev_parameters + '&load_next_productids='+load_next_productids+'&total_items='+LN_total_items;
                                }
//-End-//

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("show_next_products_block_"+ajax_navigation_page_next).innerHTML=cidev_xmlHttp.responseText;

//-Start-//
                                                        $('#load_next_productids').attr('data-value','');
                                                        ajax_navigation_page_next++;
							var cidev_parameters_load_next = 'mode_load_next_productids=Y&cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next='+ajax_navigation_page_next+'&sku='+sku;
                                                        func_load_more_next_productids(cidev_parameters_load_next, 'N');
//-End-//

                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','infinite_products.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('func_load_more_products()', 1000);
                        }
        }

//-Start-//
        function func_load_more_next_productids(cidev_parameters, first_on_load){

                        if (first_on_load == "Y"){
                                var cidev_filter_mode = 'load_more_products_SKU';
				var sku = '{/literal}{if $smarty.get.sku ne ""}{$smarty.get.sku}{/if}{literal}';
                                cidev_parameters = 'mode_load_next_productids=Y&cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next=2&sku='+sku;
                        }

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        $('#load_next_productids').attr('data-value',cidev_xmlHttp.responseText);
                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','infinite_products.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('func_load_more_next_productids()', 1000);
                        }
        }
//-End-//

{/literal}
//]]>
</script>

<div style="display: none;" id="load_next_productids" data-value="{include file="customer/main/infinite_products_load_next_productids.tpl"}"></div>
<div style="display: none;" id="LN_total_items" data-value="{$total_items}"></div>

{* Start *}
{if $smarty.get.sku ne ""}
<script type="text/javascript">
//<![CDATA[
func_load_more_next_productids('','Y');
//]]>
</script>
{/if}
{* End *}


<table width="960" align="center" cellspacing="0" cellpadding="0">
<tr>
<td>
<div class="page" style="background: #ffffff;">
  <div class="wrap">
  
    <div class="header">
        <div class="tabs">
            <span style="color: #cccccc;">{$lng.lbl_list_of_stores}</span>
            {foreach item=v from=$tabs}
            <a target="_blank" href="http://www.artistsupplysource.com/{if $v.link ne ""}{$v.link}{else}index.php?pageid={$v.pageid}{/if}"><span>{$v.title}</span></a>
            {/foreach}
        </div>
        <div class="search">
            <table class="search-table">
              <tr>
                <td>
{if $smarty.get.pageid ne ""}
<a href="{$xcart_web_dir}">
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
    <a target="_blank" href="http://www.artistsupplysource.com/page/39/terms-of-use/" class="NavigationPath">{$lng.lbl_terms_n_conditions}</a> | <a href="http://www.artistsupplysource.com/page/40/privacy-policy/" class="NavigationPath" target="_blank">{$lng.lbl_privacy_statement}</a>
</div>
</td>
</tr>
</table>


<script type="text/javascript">
//<![CDATA[
{literal}

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-952715-27']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

{/literal}
//]]>
</script>


</body>
</html>
