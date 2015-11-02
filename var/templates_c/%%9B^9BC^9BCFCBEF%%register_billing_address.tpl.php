<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:29
         compiled from main/register_billing_address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/register_billing_address.tpl', 1, false),array('modifier', 'replace', 'main/register_billing_address.tpl', 487, false),array('modifier', 'amp', 'main/register_billing_address.tpl', 626, false),array('modifier', 'default', 'main/register_billing_address.tpl', 781, false),)), $this); ?>
<?php func_load_lang($this, "main/register_billing_address.tpl","txt_newbie_registration_bottom_small_billing,lbl_title,lbl_first_name,lbl_CHECKOUT_FIELD_DESCRIPTION_b_firstname,lbl_fill_in_examples_firstname,lbl_last_name,lbl_address,lbl_CHECKOUT_FIELD_DESCRIPTION_b_address,lbl_fill_in_examples_address,lbl_address_2,lbl_CHECKOUT_FIELD_DESCRIPTION_b_address2,lbl_fill_in_examples_address2,lbl_county,lbl_country,lbl_CHECKOUT_FIELD_DESCRIPTION_b_country,lbl_CHECKOUT_FIELD_DESCRIPTION_b_country,lbl_country,lbl_CHECKOUT_FIELD_DESCRIPTION_b_country,lbl_CHECKOUT_FIELD_DESCRIPTION_b_country,lbl_zip_code,lbl_CHECKOUT_FIELD_DESCRIPTION_b_zipcode,lbl_CHECKOUT_FIELD_DESCRIPTION_b_zipcode,lbl_fill_in_examples_zip,lbl_state,lbl_CHECKOUT_FIELD_DESCRIPTION_b_state,lbl_CHECKOUT_FIELD_DESCRIPTION_b_state,lbl_state,lbl_CHECKOUT_FIELD_DESCRIPTION_b_state,lbl_CHECKOUT_FIELD_DESCRIPTION_b_state,lbl_city,lbl_CHECKOUT_FIELD_DESCRIPTION_b_city,lbl_CHECKOUT_FIELD_DESCRIPTION_b_city,lbl_fill_in_examples_city"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/register_billing_address.tpl"), $this); endif;  if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '

$(document).ready(function() {

    $("#b_zipcode, #b_city").autocomplete("zip_json.php", {
        minChars: 3,
        selectFirst: true,
        matchSubset: true,
//        width: 220,
        scrollHeight: 300,
        max: 1024,
        dataType: \'json\',
        extraParams: {
            zip: function () {
                return $("#b_zipcode:focus").val();
            },
            city: function () {
                var c = $("#b_city:focus").val();
                return c && c + \'%\'
            }
        },
        parse: function (data) {
            var a = [];
            for(var i = 0;i < data.length; i++)
                a.push({ data: data[i],
                         value: data[i].zip,
                         result: data[i].zip
                       });
            return a;
        },
        formatItem: function (item) {
          if ($("#b_countryname").val() == "United States"){
            return "<span class=\'ac_zip\'>" + item.zip + "</span>" +
                              "<span class=\'ac_city\'>" + item.city +
                              ", " + item.state + "</span>";
          } else {
            return false;
          }
        },
    });

    $("#b_zipcode, #b_city").result(function (event, item) {
        $("#b_zipcode").val(item.zip);
        $("#b_city").val(item.city);
        $("#b_state").val(item.state);
        $("#b_statename").val(item.state_name);
    });
});


        function cidev_check_zip_b(){

          cidev_check_verified_image_for_field(\'b_statename\');
          cidev_check_verified_image_for_field(\'b_zipcode\');
	  cidev_check_verified_image_for_field(\'b_city\');
	  return true; ///////////////////////////////////

                var b_city_in_registerform = document.forms["registerform"].b_city.value;
                var b_state_in_registerform = cidev_get_state_code("b_statename", "b_countryname");
//                var b_zipcode_in_registerform_length = document.forms["registerform"].b_zipcode.value.length;
                var b_zipcode_in_registerform = document.forms["registerform"].b_zipcode.value;

                var b_country_in_registerform = cidev_get_country_code("b_countryname");
                if (b_country_in_registerform == "US"){
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = \'cidev_filter_mode=show_zip_reg_form_b&b_city_in_registerform=\' + b_city_in_registerform + \'&b_state_in_registerform=\' + b_state_in_registerform + \'&b_zipcode_in_registerform=\' + b_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_show_zip_b").innerHTML=cidev_xmlHttp.responseText;

							if (cidev_id$("b_zip_show_text")){
        	                                                document.forms["registerform"].b_zipcode.value = cidev_id$("b_zip_show_text").value;

	                                                        if (cidev_id$("b_zip_show_text").value != ""){
                                                                	document.getElementById("b_zipcode_verified").style.display = \'\';                      
                                                        	        document.getElementById("b_zipcode_error").style.display = \'none\';    
                                                	                document.getElementById("b_zipcode_error_text").style.display = \'none\';     
                                        	                        document.getElementById("b_zipcode_error_text_div").innerHTML=\'\';   
                                	                        }
                        	                                else {
                	                                                document.getElementById("b_zipcode_verified").style.display = \'none\';                      
        	                                                        document.getElementById("b_zipcode_error").style.display = \'\';  
	                                                        }
							}

							cidev_check_verified_image_for_field("b_city");
                                                }else{
                                                        cidev_Error(\'no_server\', \'Y\');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open(\'POST\',\'cidev_popup_shipquote.php\',true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout(\'cidev_check_zip_b()\', 1000);
                        }
                }
        }

        function cidev_check_address_b() {
                var b_country_in_registerform = cidev_get_country_code("b_countryname");


                if (b_country_in_registerform == "US"){
                	document.forms["registerform"].b_zipcode.value = document.forms["registerform"].b_zipcode.value.replace(/[^\\w]/g, "");

                        if (document.forms["registerform"].b_zipcode.value.length == "5"){
                                document.getElementById("b_zipcode_error_text").style.display = \'none\';
                        }

//                        cidev_show_state_city_b();
                } else {
	                document.forms["registerform"].b_zipcode.value = document.forms["registerform"].b_zipcode.value.replace(/[^\\w\\s]/g, "");
		}

                if (b_country_in_registerform == "CA"){
                        if (document.forms["registerform"].b_zipcode.value.length == "6"){
                                document.getElementById("b_zipcode_error_text").style.display = \'none\';
                        }
                }
        }

        function cidev_show_state_city_b(){
			document.forms["registerform"].b_zipcode.value = ltrim(document.forms["registerform"].b_zipcode.value);
                        var b_zipcode_in_registerform = document.forms["registerform"].b_zipcode.value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = \'cidev_filter_mode=show_state_city_reg_form_b&b_zipcode_in_registerform=\' + b_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_state_city_values_b").innerHTML=cidev_xmlHttp.responseText;

							if (cidev_id$("b_state_show_text")){
	                                                        document.forms["registerform"].b_statename.value = cidev_id$("b_state_show_text").value;

                                                                if (cidev_id$("b_state_show_text").value != ""){
                                                                        document.getElementById("b_statename_verified").style.display = \'\';                        
                                                                        document.getElementById("b_statename_error").style.display = \'none\';       
                                                                }
                                                                else {
                                                                        document.getElementById("b_statename_verified").style.display = \'none\';                        
                                                                        document.getElementById("b_statename_error").style.display = \'\';    
                                                                }
							}

							if (cidev_id$("b_city_show_text")){
        	                                                document.forms["registerform"].b_city.value = cidev_id$("b_city_show_text").value;
	
        	                                                if (cidev_id$("b_city_show_text").value != ""){
                	                                                document.getElementById("b_city_verified").style.display = \'\';                         
                        	                                        document.getElementById("b_city_error").style.display = \'none\';        
                                	                        }
                                        	                else {
                                                	                document.getElementById("b_city_verified").style.display = \'none\';                         
                                                        	        document.getElementById("b_city_error").style.display = \'\';  
	                                                        }
							}

                                                }else{
                                                        cidev_Error(\'no_server\', \'Y\');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open(\'POST\',\'cidev_popup_shipquote.php\',true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout(\'cidev_show_state_city_b()\', 1000);
                        }
        }

        function onSelectChange_b() {

return true; ///////////////////////////////////

                var cityFilePath = \'\';
                var stateSelected = cidev_get_state_code("b_statename", "b_countryname");

                $(\'#b_city\').unautocomplete();

                var countrySelected = cidev_get_country_code("b_countryname"); 
        
                if (countrySelected == "US"){

                        cityFilePath = "skin1_kolin/US_City_List/" +stateSelected.toLowerCase()+".js";

                        $.getScript(cityFilePath, function() {

                                $(\'#b_city\').autocomplete(city, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        };

        function cidev_load_countries_b() {
                var countryFilePath = "skin1_kolin/US_City_List/all_countries.js";
                
                        $.getScript(countryFilePath, function() {

                                $(\'#b_countryname\').autocomplete(country_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
        }

        function cidev_load_states_b() {

                var stateFilePath = "";
                var country_code = cidev_get_country_code("b_countryname");
        
                if (country_code == "US"){
                        stateFilePath = "skin1_kolin/US_City_List/us_states.js";
                }
                if (country_code == "CA"){
                        stateFilePath = "skin1_kolin/US_City_List/ca_states.js";
                }
                
                $(\'#b_statename\').unautocomplete();

                if (country_code == "US" || country_code == "CA"){
                        $.getScript(stateFilePath, function() {

                                $(\'#b_statename\').autocomplete(state_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        }


  $(document).ready(function() {  

        $(\'#b_firstname\').focusout(function() {
		cidev_check_verified_image_for_field("b_firstname");
        });

        $(\'#b_address\').focusout(function() {
		cidev_check_verified_image_for_field(\'b_address\');
        });

        $(\'#b_address_2\').focusout(function() {
                if ($(\'#b_address_2\').val() != ""){
                        if (document.getElementById("b_address_2") && document.getElementById("b_address_2_error")){
                                document.getElementById("b_address_2_verified").style.display = \'\';                      
                                document.getElementById("b_address_2_error").style.display = \'none\';     
                        }
                }
                else {
                        if (document.getElementById("b_address_2_verified") && document.getElementById("b_address_2_error")){
                                document.getElementById("b_address_2_verified").style.display = \'none\';                      
                                document.getElementById("b_address_2_error").style.display = \'none\';  
                        }
                }
        });

        $(\'#b_zipcode\').focusout(function() {
		cidev_check_verified_image_for_field(\'b_zipcode\');
		onSelectChange_b();
        });

        $(\'#b_city\').focusout(function() {
                cidev_check_verified_image_for_field(\'b_statename\');
                cidev_check_verified_image_for_field(\'b_zipcode\');
                cidev_check_verified_image_for_field(\'b_city\');
        });

        $(\'#b_city\').change(function() {
                cidev_check_verified_image_for_field(\'b_statename\');
                cidev_check_verified_image_for_field(\'b_zipcode\');
                cidev_check_verified_image_for_field(\'b_city\');
        });

        $(\'#b_statename\').change(function() {
                cidev_check_verified_image_for_field(\'b_statename\');
                cidev_check_verified_image_for_field(\'b_zipcode\');
        });

        $(\'#b_countryname\').focusout(function() {

                var countrySelected = cidev_get_country_code("b_countryname");

                if (countrySelected == "US" || countrySelected == "CA"){
                        cidev_load_states_b();
                        onSelectChange_b();
                } 

                if (countrySelected != "US") {
                        $(\'#b_city\').unautocomplete();
                }

                if (countrySelected != "US" && countrySelected != "CA") {
                        $(\'#b_statename\').unautocomplete();
                }

		cidev_check_verified_image_for_field(\'b_countryname\');

                if ($(\'#b_zipcode\').val() != ""){
                        document.getElementById("b_zipcode_error_text").style.display = \'none\';     
                        document.getElementById("b_zipcode_error_text_div").innerHTML=\'\'; 
                }

        });

        $(\'#b_statename\').focusout(function() {
                onSelectChange_b();

                cidev_check_verified_image_for_field(\'b_statename\');
                cidev_check_verified_image_for_field(\'b_zipcode\');
        });

        function start_b() {
                cidev_load_countries_b();
                cidev_load_states_b();
                onSelectChange_b();
        }

        window.onload = start_b();
  });

'; ?>

//]]>
</script>
<?php endif; ?>



<?php if ($this->_tpl_vars['is_areas']['B'] == 'Y'):  if ($this->_tpl_vars['hide_header'] == ""): ?>
<tr>
	<td height="20" colspan="3">
<script type="text/javascript">
<!--
<?php echo '
function ship2diffOpen() {
	var obj = document.getElementById(\'ship2diff\');
	var box = document.getElementById(\'ship_box\');
	if (!obj || !box)
		return;

	box.style.display = obj.checked ? "" : "none";


	if (obj.checked){

                if ($(\'#s_firstname\').val() != ""){
                        document.getElementById("b_firstname_verified").style.display = \'\';                      
                        document.getElementById("b_firstname_error").style.display = \'none\';     
                }

                if ($(\'#s_address\').val() != ""){
                        document.getElementById("b_address_verified").style.display = \'\';                      
                        document.getElementById("b_address_error").style.display = \'none\';     
                }

                if ($(\'#s_address_2\').val() != ""){
                        document.getElementById("b_address_2_verified").style.display = \'\';                      
                        document.getElementById("b_address_2_error").style.display = \'none\';     
                }

                if ($(\'#s_zipcode\').val() != ""){
                        document.getElementById("b_zipcode_verified").style.display = \'\';                      
                        document.getElementById("b_zipcode_error").style.display = \'none\';     
                        document.getElementById("b_zipcode_error_text").style.display = \'none\';     
                        document.getElementById("b_zipcode_error_text_div").innerHTML=\'\';  
                }

                if ($(\'#s_city\').val() != ""){
                        document.getElementById("b_city_verified").style.display = \'\';                         
                        document.getElementById("b_city_error").style.display = \'none\';        
                }

                if ($(\'#s_countryname\').val() != ""){
                        document.getElementById("b_countryname_verified").style.display = \'\';                      
                        document.getElementById("b_countryname_error").style.display = \'none\';     
                }

                if ($(\'#s_statename\').val() != ""){
                        document.getElementById("b_statename_verified").style.display = \'\';                        
                        document.getElementById("b_statename_error").style.display = \'none\';       
                }

		if (document.getElementById("additional_values_1") && document.getElementById("additional_values_2")){
	                if ($(\'#additional_values_2\').val() != ""){
        	                document.getElementById("additional_values_1_verified").style.display = \'\';                        
                	        document.getElementById("additional_values_1_error").style.display = \'none\';       
	                }
		}
	}

	if (obj.checked && window.start_js_states && document.getElementById(\'b_country\') && localBFamily == \'Opera\')
		setTimeout(new Function(\'\', "start_js_states(document.getElementById(\'b_country\'));"), 200);
}
'; ?>

-->
</script>
	<B>Bill to a Different Address</B>
	<hr size="1" noshade="noshade" />
	</td>
</tr>
<tr>
		<td align="right"><label for="ship2diff">My billing address is different from my shipping address</label></td>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="ship2diff" name="ship2diff" value="Y" onclick="javascript: ship2diffOpen();"<?php if ($this->_tpl_vars['ship2diff']): ?> checked="checked"<?php endif; ?> /></td>
</tr>
<?php endif; ?>

</tbody>
<tbody id="ship_box">

<?php if ($this->_tpl_vars['action'] == 'cart'): ?>
<tr style="display: none;">
<td>
<input type="hidden" name="action" value="cart" />
<input type="hidden" name="paymentid" value="<?php echo $this->_tpl_vars['paymentid']; ?>
" />
</td>
</tr>
<?php endif; ?>

<tr>
<td colspan="3"><?php echo $this->_tpl_vars['lng']['txt_newbie_registration_bottom_small_billing']; ?>


<div id="cidev_reg_form_state_city_values_b">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/cidev_reg_form_state_city_values_b.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div id="cidev_reg_form_show_zip_b">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/cidev_reg_form_show_zip_b.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<input type="hidden" name="clear_city_in_Change_states_js" id="clear_city_in_Change_states_js" value="Y">


</td>
</tr>

<?php if ($this->_tpl_vars['default_fields']['b_title']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_title']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_title']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<select name="b_title" id="b_title">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/title_selector.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['userinfo']['b_titleid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</select>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['b_firstname']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_firstname']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_firstname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" name="b_firstname" id="b_firstname" size="32" maxlength="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['b_firstname'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&amp;#039;", "'") : smarty_modifier_replace($_tmp, "&amp;#039;", "'")); ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_firstname']; ?>
" onkeyup="cidev_check_field_name('b_firstname')"  />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_firstname_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['b_firstname'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_firstname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_firstname'] == "" && $this->_tpl_vars['default_fields']['b_firstname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['b_lastname']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_lastname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" name="b_lastname" id="b_lastname" size="32" maxlength="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['b_lastname'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&amp;#039;", "'") : smarty_modifier_replace($_tmp, "&amp;#039;", "'")); ?>
" onkeyup="cidev_check_field_name('b_lastname')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_lastname_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['b_lastname'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_lastname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_lastname'] == "" && $this->_tpl_vars['default_fields']['b_lastname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_additional_info.tpl", 'smarty_include_vars' => array('section' => 'B')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['default_fields']['b_address']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_address']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_address']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_address" name="b_address" size="32" maxlength="64" value="<?php echo $this->_tpl_vars['userinfo']['b_address']; ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_address']; ?>
" onkeyup="cidev_check_field_address('b_address')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_address_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['b_address'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_address_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_address'] == "" && $this->_tpl_vars['default_fields']['b_address']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['b_address_2']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_address_2'];  if ($this->_tpl_vars['default_fields']['b_address_2']['required'] != 'Y'): ?><font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font><?php endif;  if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_address2']; ?>
</div><?php endif; ?>
</td>

<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_address_2']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_address_2" name="b_address_2" size="32" maxlength="64" value="<?php echo $this->_tpl_vars['userinfo']['b_address_2']; ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_address2']; ?>
" onkeyup="cidev_check_field_address('b_address_2')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_address_2_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['b_address_2'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_address_2_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_address_2'] == "" && $this->_tpl_vars['default_fields']['b_address_2']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['b_county']['avail'] == 'Y' && $this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_county']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/counties.tpl", 'smarty_include_vars' => array('counties' => $this->_tpl_vars['counties'],'name' => 'b_county','default' => $this->_tpl_vars['userinfo']['b_county'],'country_name' => 'b_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if (( $this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_county'] == "" && $this->_tpl_vars['default_fields']['b_county']['required'] == 'Y' ) || $this->_tpl_vars['error'] == 'b_county'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['default_fields']['b_country']['avail'] == 'Y'): ?>

<?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_country'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_country']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_country']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_countryname" name="b_countryname" size="32" maxlength="64" value="<?php if ($this->_tpl_vars['geo_litecity_location']['country'] != ""):  unset($this->_sections['country_idx']);
$this->_sections['country_idx']['name'] = 'country_idx';
$this->_sections['country_idx']['loop'] = is_array($_loop=$this->_tpl_vars['countries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['country_idx']['show'] = true;
$this->_sections['country_idx']['max'] = $this->_sections['country_idx']['loop'];
$this->_sections['country_idx']['step'] = 1;
$this->_sections['country_idx']['start'] = $this->_sections['country_idx']['step'] > 0 ? 0 : $this->_sections['country_idx']['loop']-1;
if ($this->_sections['country_idx']['show']) {
    $this->_sections['country_idx']['total'] = $this->_sections['country_idx']['loop'];
    if ($this->_sections['country_idx']['total'] == 0)
        $this->_sections['country_idx']['show'] = false;
} else
    $this->_sections['country_idx']['total'] = 0;
if ($this->_sections['country_idx']['show']):

            for ($this->_sections['country_idx']['index'] = $this->_sections['country_idx']['start'], $this->_sections['country_idx']['iteration'] = 1;
                 $this->_sections['country_idx']['iteration'] <= $this->_sections['country_idx']['total'];
                 $this->_sections['country_idx']['index'] += $this->_sections['country_idx']['step'], $this->_sections['country_idx']['iteration']++):
$this->_sections['country_idx']['rownum'] = $this->_sections['country_idx']['iteration'];
$this->_sections['country_idx']['index_prev'] = $this->_sections['country_idx']['index'] - $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['index_next'] = $this->_sections['country_idx']['index'] + $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['first']      = ($this->_sections['country_idx']['iteration'] == 1);
$this->_sections['country_idx']['last']       = ($this->_sections['country_idx']['iteration'] == $this->_sections['country_idx']['total']);
 if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']):  if ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'] != ""):  echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  $this->assign('cidev_is_country_b', 'Y');  endif;  endif;  endfor; endif;  else:  if ($this->_tpl_vars['userinfo']['b_countryname'] != ""):  echo $this->_tpl_vars['userinfo']['b_countryname'];  $this->assign('cidev_is_country_b', 'Y');  endif;  endif; ?>" 
onkeyup="cidev_check_country_usa('b_countryname'); cidev_check_field_country('b_countryname'); cidev_check_zip_b();"  onchange="cidev_check_field_country('b_countryname'); cidev_check_zip_b();"
autocomplete="off" placeholder="<?php if ($this->_tpl_vars['geo_litecity_location']['country'] != ""):  unset($this->_sections['country_idx']);
$this->_sections['country_idx']['name'] = 'country_idx';
$this->_sections['country_idx']['loop'] = is_array($_loop=$this->_tpl_vars['countries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['country_idx']['show'] = true;
$this->_sections['country_idx']['max'] = $this->_sections['country_idx']['loop'];
$this->_sections['country_idx']['step'] = 1;
$this->_sections['country_idx']['start'] = $this->_sections['country_idx']['step'] > 0 ? 0 : $this->_sections['country_idx']['loop']-1;
if ($this->_sections['country_idx']['show']) {
    $this->_sections['country_idx']['total'] = $this->_sections['country_idx']['loop'];
    if ($this->_sections['country_idx']['total'] == 0)
        $this->_sections['country_idx']['show'] = false;
} else
    $this->_sections['country_idx']['total'] = 0;
if ($this->_sections['country_idx']['show']):

            for ($this->_sections['country_idx']['index'] = $this->_sections['country_idx']['start'], $this->_sections['country_idx']['iteration'] = 1;
                 $this->_sections['country_idx']['iteration'] <= $this->_sections['country_idx']['total'];
                 $this->_sections['country_idx']['index'] += $this->_sections['country_idx']['step'], $this->_sections['country_idx']['iteration']++):
$this->_sections['country_idx']['rownum'] = $this->_sections['country_idx']['iteration'];
$this->_sections['country_idx']['index_prev'] = $this->_sections['country_idx']['index'] - $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['index_next'] = $this->_sections['country_idx']['index'] + $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['first']      = ($this->_sections['country_idx']['iteration'] == 1);
$this->_sections['country_idx']['last']       = ($this->_sections['country_idx']['iteration'] == $this->_sections['country_idx']['total']);
 if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']):  echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  endif;  endfor; endif;  endif; ?>" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_countryname_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['cidev_is_country_b'] != 'Y'): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_countryname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>


<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_country'] == "" && $this->_tpl_vars['default_fields']['b_country']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>


<input type="hidden" id="b_country" name="b_country" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['b_country']; ?>
" />


</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A'): ?>
<tr <?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>style="display: none;"<?php endif; ?>>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_country'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_country']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_country']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<select name="b_country" id="b_country" size="1" onchange="check_zip_code();">
<?php unset($this->_sections['country_idx']);
$this->_sections['country_idx']['name'] = 'country_idx';
$this->_sections['country_idx']['loop'] = is_array($_loop=$this->_tpl_vars['countries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['country_idx']['show'] = true;
$this->_sections['country_idx']['max'] = $this->_sections['country_idx']['loop'];
$this->_sections['country_idx']['step'] = 1;
$this->_sections['country_idx']['start'] = $this->_sections['country_idx']['step'] > 0 ? 0 : $this->_sections['country_idx']['loop']-1;
if ($this->_sections['country_idx']['show']) {
    $this->_sections['country_idx']['total'] = $this->_sections['country_idx']['loop'];
    if ($this->_sections['country_idx']['total'] == 0)
        $this->_sections['country_idx']['show'] = false;
} else
    $this->_sections['country_idx']['total'] = 0;
if ($this->_sections['country_idx']['show']):

            for ($this->_sections['country_idx']['index'] = $this->_sections['country_idx']['start'], $this->_sections['country_idx']['iteration'] = 1;
                 $this->_sections['country_idx']['iteration'] <= $this->_sections['country_idx']['total'];
                 $this->_sections['country_idx']['index'] += $this->_sections['country_idx']['step'], $this->_sections['country_idx']['iteration']++):
$this->_sections['country_idx']['rownum'] = $this->_sections['country_idx']['iteration'];
$this->_sections['country_idx']['index_prev'] = $this->_sections['country_idx']['index'] - $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['index_next'] = $this->_sections['country_idx']['index'] + $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['first']      = ($this->_sections['country_idx']['iteration'] == 1);
$this->_sections['country_idx']['last']       = ($this->_sections['country_idx']['iteration'] == $this->_sections['country_idx']['total']);
?>
<option value="<?php echo $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']; ?>
" <?php if ($this->_tpl_vars['geo_litecity_location']['country'] != ""):  if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php endif;  else:  if ($this->_tpl_vars['userinfo']['b_country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php elseif ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['config']['General']['default_country'] && $this->_tpl_vars['userinfo']['b_country'] == ""): ?> selected="selected"<?php endif;  endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</option>
<?php endfor; endif; ?>
</select>
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_country'] == "" && $this->_tpl_vars['default_fields']['b_country']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php endif; ?>



<?php if ($this->_tpl_vars['default_fields']['b_zipcode']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_zipcode'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_zipcode']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_zipcode']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_zipcode" name="b_zipcode" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['b_zipcode']; ?>
" <?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?> onchange="if ($('#ship2diff').attr('checked') )cidev_new_check_zip_code(); check_zip_code_ship('b_zipcode', 'b_countryname');" onkeyup="cidev_check_field('b_zipcode'); cidev_check_address_b();" <?php endif; ?> autocomplete="off" placeholder="<?php if ($this->_tpl_vars['geo_litecity_location']['postalCode'] != ""):  echo $this->_tpl_vars['geo_litecity_location']['postalCode'];  else:  echo $this->_tpl_vars['lng']['lbl_fill_in_examples_zip'];  endif; ?>" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_zipcode_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['geo_litecity_location']['postalCode'] == "" && $this->_tpl_vars['userinfo']['s_zipcode'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_zipcode_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>

<td id="b_zipcode_error_text" valign="top" style="display: none;">
<div class="cidev_NoteBox" id="b_zipcode_error_text_div"></div>
</td>

<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_zipcode'] == "" && $this->_tpl_vars['default_fields']['b_zipcode']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['default_fields']['b_state']['avail'] == 'Y'): ?>

<?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_state'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_state']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_state']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_statename" name="b_statename" size="32" maxlength="64" 
value="
<?php if ($this->_tpl_vars['geo_litecity_location']['region'] != ""):  unset($this->_sections['state_idx']);
$this->_sections['state_idx']['name'] = 'state_idx';
$this->_sections['state_idx']['loop'] = is_array($_loop=$this->_tpl_vars['states']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['state_idx']['show'] = true;
$this->_sections['state_idx']['max'] = $this->_sections['state_idx']['loop'];
$this->_sections['state_idx']['step'] = 1;
$this->_sections['state_idx']['start'] = $this->_sections['state_idx']['step'] > 0 ? 0 : $this->_sections['state_idx']['loop']-1;
if ($this->_sections['state_idx']['show']) {
    $this->_sections['state_idx']['total'] = $this->_sections['state_idx']['loop'];
    if ($this->_sections['state_idx']['total'] == 0)
        $this->_sections['state_idx']['show'] = false;
} else
    $this->_sections['state_idx']['total'] = 0;
if ($this->_sections['state_idx']['show']):

            for ($this->_sections['state_idx']['index'] = $this->_sections['state_idx']['start'], $this->_sections['state_idx']['iteration'] = 1;
                 $this->_sections['state_idx']['iteration'] <= $this->_sections['state_idx']['total'];
                 $this->_sections['state_idx']['index'] += $this->_sections['state_idx']['step'], $this->_sections['state_idx']['iteration']++):
$this->_sections['state_idx']['rownum'] = $this->_sections['state_idx']['iteration'];
$this->_sections['state_idx']['index_prev'] = $this->_sections['state_idx']['index'] - $this->_sections['state_idx']['step'];
$this->_sections['state_idx']['index_next'] = $this->_sections['state_idx']['index'] + $this->_sections['state_idx']['step'];
$this->_sections['state_idx']['first']      = ($this->_sections['state_idx']['iteration'] == 1);
$this->_sections['state_idx']['last']       = ($this->_sections['state_idx']['iteration'] == $this->_sections['state_idx']['total']);
 if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['country_code'] && $this->_tpl_vars['geo_litecity_location']['region'] == $this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state_code']):  if ($this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state'] != ""):  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  $this->assign('cidev_is_state_b', 'Y');  endif;  endif;  endfor; endif;  else:  if ($this->_tpl_vars['userinfo']['b_statename'] != ""):  echo $this->_tpl_vars['userinfo']['b_statename'];  $this->assign('cidev_is_state_b', 'Y');  endif;  endif; ?>
" 
onkeyup="cidev_check_field_country('b_statename'); cidev_check_zip_b();" 
autocomplete="off" 
placeholder="
<?php if ($this->_tpl_vars['geo_litecity_location']['region'] != ""):  unset($this->_sections['state_idx']);
$this->_sections['state_idx']['name'] = 'state_idx';
$this->_sections['state_idx']['loop'] = is_array($_loop=$this->_tpl_vars['states']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['state_idx']['show'] = true;
$this->_sections['state_idx']['max'] = $this->_sections['state_idx']['loop'];
$this->_sections['state_idx']['step'] = 1;
$this->_sections['state_idx']['start'] = $this->_sections['state_idx']['step'] > 0 ? 0 : $this->_sections['state_idx']['loop']-1;
if ($this->_sections['state_idx']['show']) {
    $this->_sections['state_idx']['total'] = $this->_sections['state_idx']['loop'];
    if ($this->_sections['state_idx']['total'] == 0)
        $this->_sections['state_idx']['show'] = false;
} else
    $this->_sections['state_idx']['total'] = 0;
if ($this->_sections['state_idx']['show']):

            for ($this->_sections['state_idx']['index'] = $this->_sections['state_idx']['start'], $this->_sections['state_idx']['iteration'] = 1;
                 $this->_sections['state_idx']['iteration'] <= $this->_sections['state_idx']['total'];
                 $this->_sections['state_idx']['index'] += $this->_sections['state_idx']['step'], $this->_sections['state_idx']['iteration']++):
$this->_sections['state_idx']['rownum'] = $this->_sections['state_idx']['iteration'];
$this->_sections['state_idx']['index_prev'] = $this->_sections['state_idx']['index'] - $this->_sections['state_idx']['step'];
$this->_sections['state_idx']['index_next'] = $this->_sections['state_idx']['index'] + $this->_sections['state_idx']['step'];
$this->_sections['state_idx']['first']      = ($this->_sections['state_idx']['iteration'] == 1);
$this->_sections['state_idx']['last']       = ($this->_sections['state_idx']['iteration'] == $this->_sections['state_idx']['total']);
 if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['country_code'] && $this->_tpl_vars['geo_litecity_location']['region'] == $this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state_code']):  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>

<?php endif;  endfor; endif;  else:  echo $this->_tpl_vars['userinfo']['b_statename']; ?>

<?php endif; ?>
" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_statename_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['cidev_is_state_b'] != 'Y'): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_statename_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_state'] == "" && $this->_tpl_vars['default_fields']['b_state']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>


<input type="hidden" id="b_state" name="b_state" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['b_state']; ?>
" />

</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_state'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_state']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_state']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => 'b_state','default' => $this->_tpl_vars['userinfo']['b_state'],'default_country' => ((is_array($_tmp=@$this->_tpl_vars['userinfo']['b_country'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['General']['default_country']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['General']['default_country'])),'country_name' => 'b_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if (( $this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_state'] == "" && $this->_tpl_vars['default_fields']['b_state']['required'] == 'Y' ) || $this->_tpl_vars['error'] == 'b_statecode'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['b_state']['avail'] == 'Y' && $this->_tpl_vars['default_fields']['b_country']['avail'] == 'Y' && $this->_tpl_vars['js_enabled'] == 'Y' && $this->_tpl_vars['config']['General']['use_js_states'] == 'Y'): ?>
<tr style="display: none;">
        <td valign="top">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => 'b_state','country_name' => 'b_country','county_name' => 'b_county','state_value' => $this->_tpl_vars['userinfo']['b_state'],'county_value' => $this->_tpl_vars['userinfo']['b_county'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </td>
</tr>
<?php endif;  endif; ?>


<?php if ($this->_tpl_vars['default_fields']['b_city']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_city'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_b_city']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['b_city']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_city" name="b_city" size="32" maxlength="64" value="<?php if ($this->_tpl_vars['geo_litecity_location']['country'] != ""):  echo $this->_tpl_vars['geo_litecity_location']['city'];  else:  echo $this->_tpl_vars['userinfo']['b_city'];  endif; ?>" <?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?> onkeyup="cidev_check_field('b_city'); cidev_check_zip_b();" <?php endif; ?> placeholder="<?php if ($this->_tpl_vars['geo_litecity_location']['city'] != ""):  echo $this->_tpl_vars['geo_litecity_location']['city'];  else:  echo $this->_tpl_vars['lng']['lbl_fill_in_examples_city'];  endif; ?>" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="b_city_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['geo_litecity_location']['city'] == "" && $this->_tpl_vars['userinfo']['b_city'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="b_city_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['b_city'] == "" && $this->_tpl_vars['default_fields']['b_city']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>



<?php if (! $this->_tpl_vars['ship2diff']): ?>
<tr style="display: none;">
    <td>
<script type="text/javascript">
<!--
if (document.getElementById('ship_box'))
    document.getElementById('ship_box').style.display = 'none';
-->
</script>
    </td>
</tr>
<?php endif; ?>
</tbody>
<tbody>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/register_billing_address.tpl"), $this); endif; ?>