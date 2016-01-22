{*
$Id: home.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if !$is_ajax_request}
<!DOCTYPE html>
  {config_load file="$skin_config"}
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    {func_mobile_clear_modules}
    {include file="customer/service_head_mobile.tpl"}
  </head>
  <body>
{/if}

{if $main eq "product" || $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>
{/if}

{if $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}

<script type="text/javascript">
//<![CDATA[
{literal}
        function func_load_more_products(ajax_navigation_page_next){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var e_products_found = '{/literal}{if $e_products_found eq "Y"}Y{/if}{literal}';
                                var cidev_filter_mode = 'load_more_products';
                                var ga_page_name = '{/literal}{if $ga_page_name ne ""}{$ga_page_name}{/if}{literal}';
        
                                if (e_products_found == "Y"){
                                        cidev_filter_mode = 'load_more_e_products';
                                }
                                
                                var cat = {/literal}{if $cat ne ""}{$cat}{else}{literal}''{/literal}{/if}{literal};

                                var cidev_parameters = 'cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next='+ajax_navigation_page_next+'&cat='+cat+'&ga_page_name='+ga_page_name;

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
                                                        var cidev_parameters_load_next = 'mode_load_next_productids=Y&cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next='+ajax_navigation_page_next+'&cat='+cat;
                                                        func_load_more_next_productids(cidev_parameters_load_next, 'N');
//-End-//

                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                var tmp_rand = Math.random();

                                cidev_xmlHttp.open('POST','infinite_products.php?rand='+tmp_rand,true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-cache');
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-store');
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
                                var current_storefront = '{/literal}{$current_storefront}{literal}';
                                var e_products_found = '{/literal}{if $e_products_found eq "Y"}Y{/if}{literal}';
                                var cidev_filter_mode = 'load_more_products';
        
                                if (e_products_found == "Y"){
                                        cidev_filter_mode = 'load_more_e_products';
                                }
                                
                                var cat = {/literal}{if $cat ne ""}{$cat}{else}{literal}''{/literal}{/if}{literal};

                                cidev_parameters = 'mode_load_next_productids=Y&cidev_filter_mode='+cidev_filter_mode+'&ajax_navigation_page_next=2&cat='+cat;
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

                                var tmp_rand = Math.random();

                                cidev_xmlHttp.open('POST','infinite_products.php?rand='+tmp_rand,true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-cache');
                                cidev_xmlHttp.setRequestHeader('Cache-Control','no-store');
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

{/if}


    {include file="customer/page.tpl"}

{* Start *}
{if $main eq "catalog" || $main eq "brand_products" || $main eq "search" || $main eq "advanced_search"}
<script type="text/javascript">
//<![CDATA[
func_load_more_next_productids('','Y');
//]]>
</script>
{/if}
{* End *}

{if !$is_ajax_request}
    {load_defer_code type="js"}
    {load_defer_code type="css"}

{*
<script type="text/javascript">
//<![CDATA[
{literal}

    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 's3stores';
    
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
{/literal}
//]]>
</script>
*}
  </body>
</html>
{/if}
