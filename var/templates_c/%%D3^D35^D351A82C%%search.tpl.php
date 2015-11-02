<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/search.tpl', 1, false),array('modifier', 'substitute', 'customer/search.tpl', 132, false),array('modifier', 'stripslashes', 'customer/search.tpl', 209, false),array('modifier', 'escape', 'customer/search.tpl', 209, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/search.tpl"), $this); endif; ?>
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/US_City_List/jquery.autocomplete.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
<?php echo '

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
        dataType: \'json\',
        extraParams: {
            twotabsearchtextbox: function () {
                return $("#twotabsearchtextbox:focus").val();
            }
        },
        parse: function (data) {
            var a = [];
            for(var i = 0;i < data.length; i++)
                a.push({ data: data[i],
                         value: data[i].twotabsearchtextbox,
                         result: data[i].twotabsearchtextbox
                       });
            return a;
        },
        formatItem: function (item) {
            return "<span class=\'ac_textbox\'>" + item.twotabsearchtextbox + "</span>";
        },
        highlight: function(value, term) {
                return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + term.replace(/([\\^\\$\\(\\)\\[\\]\\{\\}\\*\\.\\+\\?\\|\\\\])/gi, "\\\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "$1");
        },
    });

    $("#twotabsearchtextbox").result(function (event, item) {
//        $("#twotabsearchtextbox").val(item.twotabsearchtextbox);
	var suggest;
	suggest = item.twotabsearchtextbox;

	suggest = suggest.split("<em>").join("");
	suggest = suggest.split("</em>").join("");

        suggest = suggest.split("<strong>").join("");
        suggest = suggest.split("</strong>").join("");

        $("#twotabsearchtextbox").val(suggest);
    });

});

'; ?>

//]]>
</script>



<script type="text/javascript">
<!--
<?php echo '

function google_custom_search(control) {
    $(\'#google_search_result_block\').hide();

    control.setSearchCompleteCallback(control, function(el) {
        $(\'#main\').hide();
        $(\'#google_search_result_block\').show();
    });
    
    $(\'.gsst_a .gscb_a\').live(\'click\', function() {
        $(\'#google_search_result_block\').hide();
        $(\'#main\').show();
    });

    $(\'td.gsib_a input\').css(\'margin\', \'4px\');

/* ---------------------- */

        $(\'#gsc-i-id1\').val('; ?>
'<?php echo $this->_tpl_vars['config']['Company']['cidev_header_code']; ?>
'<?php echo ').css("color" , "#ccc");
        $(\'#gsc-iw-id1\').css("border", "1px solid #818181");
        $(\'#gs_st0\').css("padding", "0 3px");
//        $(\'.gsst_a\').css("padding-top", "2px");
        $(\'#gsc-i-id1\').attr("title","");

        $(\'#gsc-i-id1\')
          .focus(function(){if ($(this).val() == '; ?>
'<?php echo $this->_tpl_vars['config']['Company']['cidev_header_code']; ?>
'<?php echo ') {$(this).val(\'\').css("color" , "#000");} })
          .blur(function(){if ($(this).val() == \'\') {$(this).val('; ?>
'<?php echo $this->_tpl_vars['config']['Company']['cidev_header_code']; ?>
'<?php echo ').css("color" , "#ccc");} });

/* ---------------------- */

}

$(document).ready(function() {
    $(\'.g_td\').hover(function() {
        $(this).addClass(\'g_td_hover\');
    }, 
    function() {
        $(this).removeClass(\'g_td_hover\');
    });
    $(\'.g_td input\').focus(function() {
        $(\'.g_td\').addClass(\'g_td_focus\');
    })
    .blur(function() {
        $(\'.g_td\').removeClass(\'g_td_focus\');
    });
});

'; ?>

-->
</script>

<div class="SearchContainer">
<table class="SearchTable">
<tr>	
	<td align="right">

		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>	
			<td align="center" nowrap="nowrap">

<?php if ($this->_tpl_vars['config']['Company']['search_products_unique_id_checkbox'] == 'Y'): ?>

				<?php echo ((is_array($_tmp=$this->_tpl_vars['config']['Search_products']['search_products_box_code'])) ? $this->_run_mod_handler('substitute', true, $_tmp, "gcse-id", $this->_tpl_vars['config']['Search_products']['search_products_unique_id'], "pre-query", $GLOBALS['_GET']['substring'], "is-sku-search-null", $this->_tpl_vars['config']['Search_All']['transfer_to_gcs_if_sku_search_null'], "gcse-extend", 'google_custom_search(customSearchControl);') : smarty_modifier_substitute($_tmp, "gcse-id", $this->_tpl_vars['config']['Search_products']['search_products_unique_id'], "pre-query", $GLOBALS['_GET']['substring'], "is-sku-search-null", $this->_tpl_vars['config']['Search_All']['transfer_to_gcs_if_sku_search_null'], "gcse-extend", 'google_custom_search(customSearchControl);')); ?>


<?php else: ?>





        <div class="nav-sprite">

          <form class="nav-searchbar-inner" id="nav-searchbar" method="post" action="home.php" name="productsearchform">
            <input type="hidden" name="e_mode" value="e_search" />

   	    <?php if ($this->_tpl_vars['cat'] > 0 || $this->_tpl_vars['clean_url_data']['resource_type'] == 'K'): ?>
            <input type="hidden" name="e_current_url" value="<?php if ($this->_tpl_vars['main'] == 'product'): ?>/home.php?cat=<?php echo $this->_tpl_vars['cat'];  else:  echo $this->_tpl_vars['action_notify_url'];  endif; ?>" />
	    <?php endif; ?>

            <div class="nav-submit-button nav-sprite">
              <input type="submit" title="Go" class="nav-submit-input" value="Go">
            </div>

	    <?php if ($this->_tpl_vars['e_search_data']['substring'] != ""): ?>
            <div class="nav-submit-button-x" id="nav-submit-button-x">
			<span id="nav-submit-button-x-span" class="nav-submit-button-x-span">
			<a href="javascript: void(0);" class="nav-submit-button-x-link" onclick="javascript: $('#twotabsearchtextbox').val(''); document.productsearchform.submit();" class="VertMenuItems">X</a>
			</span>
	    </div>
	    <?php endif; ?>

		<input type="hidden" name="cat" value="0" />

            <div class="nav-searchfield-width">
              <div id="nav-iss-attach">
                <input type="text" autocomplete="off" name="e_posted_data[substring]" 
value="<?php if ($this->_tpl_vars['e_search_data']['orig_substring'] != ""):  echo $this->_tpl_vars['e_search_data']['orig_substring'];  elseif ($this->_tpl_vars['e_search_data']['substring'] != ""):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['e_search_data']['substring'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  endif; ?>" 
		title="Search For" id="twotabsearchtextbox" placeholder="<?php echo $this->_tpl_vars['config']['Company']['cidev_header_code']; ?>
" />
              </div>
            </div>

          </form>

<div style="height: 20px; paddign-top: 2px; text-align: left;">
<?php if ($this->_tpl_vars['e_search_data']['substring'] != ""): ?>


<?php endif; ?>
</div>
        </div>

<script type="text/javascript">
//<![CDATA[
<?php echo '

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

'; ?>

//]]>
</script>

<?php endif; ?>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</div>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/search.tpl"), $this); endif; ?>