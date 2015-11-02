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

		<form method="post" action="search.php" name="productsearchform">
		<input type="hidden" name="simple_search" value="Y" />
		<input type="hidden" name="mode" value="search" />
		<input type="hidden" name="posted_data[by_title]" value="Y" />
		<input type="hidden" name="posted_data[by_shortdescr]" value="Y" />
		<input type="hidden" name="posted_data[by_fulldescr]" value="Y" />
		<input type="hidden" name="posted_data[by_sku]" value="Y" />
		<input type="hidden" name="posted_data[including]" value="all" />

		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>	
			<td align="center" nowrap="nowrap">
				{$config.Search_products.search_products_box_code|substitute:"gcse-id":$config.Search_products.search_products_unique_id:"pre-query":$smarty.get.substring:"is-sku-search-null":$config.Search_All.transfer_to_gcs_if_sku_search_null:"gcse-extend":'google_custom_search(customSearchControl);'}
				<!--<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<input type="text" id="searchstring" name="posted_data[substring]" size="80%" value="{$lng.lbl_search_by_keyword}" onfocus="javascript:search_focus('searchstring', '{$lng.lbl_search_by_keyword}');" onblur="javascript: search_blur('searchstring', '{$lng.lbl_search_by_keyword}');" style="background-color: #FFFFFF; margin-right: 0px; padding-right: 0px" />
					</td>
					<td>
						<a href="javascript: document.productsearchform.submit();" class="VertMenuItems" title="{$lng.lbl_go}"><b>{$lng.lbl_go}</b></a>
					</td>
				</tr>
				</table>-->
			</td>
		</tr>
		</table>
		</form>

	</td>
{*
	<td class="SearchTableRightColumn" nowrap="nowrap" align="center" width="172">
		<a href="{$config.Company.company_website}/sitemap.php" class="VertMenuItems">{$lng.lbl_sitemap}</a>
	</td>
*}
</tr>
</table>
</div>
