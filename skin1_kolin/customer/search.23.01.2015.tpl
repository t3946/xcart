{* $Id: search.tpl,v 1.9.2.1 2006/11/27 14:28:38 max Exp $ *}
<script type="text/javascript">
<!--
{literal}

function google_custom_search(control) {
    $('#google_search_result_block').hide();

    control.setSearchCompleteCallback(control, function(el) {
        $('#main').hide();
        $('#google_search_result_block').show();
    });
    
    $('.gsst_a .gscb_a').live('click', function() {
        $('#google_search_result_block').hide();
        $('#main').show();
    });

    $('td.gsib_a input').css('margin', '4px');

/* ---------------------- */

        $('#gsc-i-id1').val({/literal}'{$config.Company.cidev_header_code}'{literal}).css("color" , "#ccc");
        $('#gsc-iw-id1').css("border", "1px solid #818181");
        $('#gs_st0').css("padding", "0 3px");
//        $('.gsst_a').css("padding-top", "2px");
        $('#gsc-i-id1').attr("title","");

        $('#gsc-i-id1')
          .focus(function(){if ($(this).val() == {/literal}'{$config.Company.cidev_header_code}'{literal}) {$(this).val('').css("color" , "#000");} })
          .blur(function(){if ($(this).val() == '') {$(this).val({/literal}'{$config.Company.cidev_header_code}'{literal}).css("color" , "#ccc");} });

/* ---------------------- */

}

$(document).ready(function() {
    $('.g_td').hover(function() {
        $(this).addClass('g_td_hover');
    }, 
    function() {
        $(this).removeClass('g_td_hover');
    });
    $('.g_td input').focus(function() {
        $('.g_td').addClass('g_td_focus');
    })
    .blur(function() {
        $('.g_td').removeClass('g_td_focus');
    });
});

{/literal}
-->
</script>

<div class="SearchContainer">
<table class="SearchTable">
<tr>	
	<td align="right">

		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>	
			<td align="center" nowrap="nowrap">

{if $config.Company.search_products_unique_id_checkbox eq "Y"}

				{$config.Search_products.search_products_box_code|substitute:"gcse-id":$config.Search_products.search_products_unique_id:"pre-query":$smarty.get.substring:"is-sku-search-null":$config.Search_All.transfer_to_gcs_if_sku_search_null:"gcse-extend":'google_custom_search(customSearchControl);'}

{else }

{*
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<input type="text" id="searchstring" name="e_posted_data[substring]" size="40%" value="{$e_search_data.substring}" placeholder="{$config.Company.cidev_header_code}" style="background-color: #FFFFFF; margin-right: 0px; padding-right: 0px" />

{if $e_search_data.total ne ""}
<br />

Total: {$e_search_data.total}

{/if}

					</td>
					<td nowrap="nowrap">
						<a href="javascript: void(0);" onclick="javascript: document.productsearchform.submit();" class="VertMenuItems" title="{$lng.lbl_go}"><b>{$lng.lbl_go}</b></a>
						{if $e_search_data.substring ne ""}
						&nbsp; <a href="javascript: void(0);" onclick="javascript: $('#searchstring').val(''); document.productsearchform.submit();" class="VertMenuItems"><b>Clear search</b></a>
						{/if}
					</td>
				</tr>
				</table>
*}




{* ------------------------------ *}
        <div class="nav-sprite">

{*
          <form class="nav-searchbar-inner" id="nav-searchbar" method="post" action="{if $cat gt 0}{$action_notify_url}{else}home.php{/if}" name="productsearchform">
*}
          <form class="nav-searchbar-inner" id="nav-searchbar" method="post" action="home.php" name="productsearchform">
            <input type="hidden" name="e_mode" value="e_search" />

   	    {if $cat gt 0 || $clean_url_data.resource_type eq "K"}
            <input type="hidden" name="e_current_url" value="{if $main eq "product"}/home.php?cat={$cat}{else}{$action_notify_url}{/if}" />
	    {/if}

            <div class="nav-submit-button nav-sprite">
              <input type="submit" title="Go" class="nav-submit-input" value="Go">
            </div>

	    {if $e_search_data.substring ne ""}
            <div class="nav-submit-button-x" id="nav-submit-button-x">
			<span id="nav-submit-button-x-span" class="nav-submit-button-x-span">
			<a href="javascript: void(0);" class="nav-submit-button-x-link" onclick="javascript: $('#twotabsearchtextbox').val(''); document.productsearchform.submit();" class="VertMenuItems">X</a>
			</span>
	    </div>
	    {/if}

		<input type="hidden" name="cat" value="0" />

{*
            <span class="nav-sprite nav-facade-active" id="nav-search-in" style="width: auto;">

              <span id="nav-search-in-content" style="width: auto; overflow: visible;">{if $current_category.categoryid gt 0}{$current_category.category}{else}Search all website{/if}</span>

              <span class="nav-down-arrow"></span>

              <select title="Search in" name="cat" id="searchDropdownBox" class="searchSelect" style="top: 3px;">
                <option value="0">Search all website</option>
		{if $current_category.categoryid gt 0}
	                <option value="{$current_category.categoryid}" selected="selected">{$current_category.category}</option>
		{/if}
              </select>
            </span>
*}
            <div class="nav-searchfield-width">
              <div id="nav-iss-attach">
                <input type="text" autocomplete="off" name="e_posted_data[substring]" value="{$e_search_data.substring|stripslashes|escape}" title="Search For" id="twotabsearchtextbox" placeholder="{$config.Company.cidev_header_code}" />
              </div>
            </div>

          </form>

<div style="height: 20px; paddign-top: 2px; text-align: left;">
{if $e_search_data.substring ne ""}
{if $cat gt 0}{$lng.lbl_total_found_cat_page}{else}{$lng.lbl_total_found_home_page}{/if}: {$e_search_data.total}
{/if}
</div>
        </div>

<script type="text/javascript">
//<![CDATA[
{literal}

$(document).ready(function() {  

  $("#nav-search-in").click(function(event){
        $("#nav-search-in").attr("class", "nav-sprite nav-facade-active nav-focus");
  });

/*
  $("#searchDropdownBox").change(function() {
        var nav_search_in_content_value = $("#searchDropdownBox option:selected").text();
        $("#nav-search-in-content").html(nav_search_in_content_value);
        $("#twotabsearchtextbox").focus();
        $("#nav-search-in").attr("class", "nav-sprite nav-facade-active");
	$("#nav-submit-button-x").attr("class", "nav-submit-button-x-active");
  });
*/

  $("#twotabsearchtextbox").focusout(function(event){
        $("#nav-searchbar").attr("class", "nav-searchbar-inner");
	$("#nav-search-in").attr("class", "nav-sprite nav-facade-active");
	$("#nav-submit-button-x").attr("class", "nav-submit-button-x");
  });

  $("#twotabsearchtextbox").focus(function(event){
        $("#nav-searchbar").attr("class", "nav-searchbar-inner nav-active");
	$("#nav-search-in").attr("class", "nav-sprite nav-facade-active");
	$("#nav-submit-button-x").attr("class", "nav-submit-button-x-active");
  });

  $("#twotabsearchtextbox").keyup(function(event){
        $("#nav-search-in").attr("class", "nav-sprite nav-facade-active");
	$("#nav-submit-button-x").attr("class", "nav-submit-button-x-active");
  });

});

{/literal}
//]]>
</script>
{* ------------------------------ *}

{/if}
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</div>
