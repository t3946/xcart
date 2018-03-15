{* $Id: search.tpl,v 1.9.2.1 2006/11/27 14:28:38 max Exp $ *}

<script defer src="{$SkinDir}/US_City_List/jquery.autocomplete.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

$(document).ready(function() {

    $("#twotabsearchtextbox").autocomplete("cidev_phrase_suggester_json.php", {
//        inputClass: "ac_input_textbox",
	resultsClass: "ac_results_textbox",
//        loadingClass: "ac_loading_textbox",
        minChars: 3,
        selectFirst: false,
        matchSubset: true,
        width: 412,
        scrollHeight: 300,
        max: 1024,
        dataType: 'json',
        extraParams: {
            twotabsearchtextbox: function () {
                return $("#twotabsearchtextbox:focus").val();
            }
        },
        parse: function (data) {
            var a = [];
            if ($(data).length > 0)
            for(var i = 0;i < data.length; i++)
                a.push({ data: data[i],
                         value: data[i].twotabsearchtextbox,
                         result: data[i].twotabsearchtextbox
                       });
            return a;
        },
        formatItem: function (item) {
            return "<span class='ac_textbox'>" + item.twotabsearchtextbox + "</span>";
        },
        highlight: function(value, term) {
                return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "$1");
        },
    });

    $("#twotabsearchtextbox").result(function (event, item) {
        var suggest = item.twotabsearchtextbox;
        suggest = suggest.split("<em>").join("");
        suggest = suggest.split("</em>").join("");
        suggest = suggest.split("<strong>").join("");
        suggest = suggest.split("</strong>").join("");
        $(this).val($('<span>').append(suggest).text());
    });

});

{/literal}
//]]>
</script>

{* --------------------------- *}


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

{* ------------------------------ *}
        <div class="nav-sprite">

          <form class="nav-searchbar-inner" id="nav-searchbar" method="post" action="home.php" name="productsearchform">
            <input type="hidden" name="e_mode" value="e_search" />

   	    {if $cat gt 0 || $clean_url_data.resource_type eq "K"}
            <input type="hidden" name="e_current_url" value="{if $main eq "product"}/home.php?cat={$cat}{else}{$action_notify_url}{/if}" />
	    {/if}


            <div class="nav-submit-button nav-sprite">
              <input type="submit" title="Go" class="nav-submit-input" value="">
            </div>

{*
	    {if $e_search_data.substring ne ""}
            <div class="nav-submit-button-x" id="nav-submit-button-x">
			<span id="nav-submit-button-x-span" class="nav-submit-button-x-span">
			<a href="javascript: void(0);" class="nav-submit-button-x-link" onclick="javascript: $('#twotabsearchtextbox').val(''); document.productsearchform.submit();" class="VertMenuItems">X</a>
			</span>
	    </div>
	    {/if}
*}
		<input type="hidden" name="cat" value="0" />

            <div class="nav-searchfield-width">
              <div id="nav-iss-attach">
                <input type="text" autocomplete="off" name="e_posted_data[substring]" 
value="{if $e_search_data.orig_substring ne ""}{$e_search_data.orig_substring|stripslashes|escape}{elseif $e_search_data.substring ne ""}{$e_search_data.substring|stripslashes|escape}{else}{* {$e_search_data_previous_substring} *}{/if}"
		title="Search For" id="twotabsearchtextbox" placeholder="{$config.Company.cidev_header_code}" />
              </div>
            </div>

          </form>
        </div>

<script type="text/javascript">
//<![CDATA[
{literal}

$(document).ready(function() {  

  $("#nav-search-in").click(function(event){
        $("#nav-search-in").attr("class", "nav-sprite nav-facade-active nav-focus");
  });

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
