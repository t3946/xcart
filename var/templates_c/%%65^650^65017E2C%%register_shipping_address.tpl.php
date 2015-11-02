<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:28
         compiled from main/register_shipping_address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/register_shipping_address.tpl', 1, false),array('modifier', 'amp', 'main/register_shipping_address.tpl', 94, false),array('modifier', 'replace', 'main/register_shipping_address.tpl', 552, false),array('modifier', 'default', 'main/register_shipping_address.tpl', 847, false),)), $this); ?>
<?php func_load_lang($this, "main/register_shipping_address.tpl","lbl_shipping_address,txt_fields_are_mandatory,lbl_title,lbl_first_name,lbl_CHECKOUT_FIELD_DESCRIPTION_s_firstname,lbl_fill_in_examples_firstname,lbl_last_name,lbl_address,lbl_CHECKOUT_FIELD_DESCRIPTION_s_address,lbl_fill_in_examples_address,lbl_address_2,lbl_CHECKOUT_FIELD_DESCRIPTION_s_address2,lbl_fill_in_examples_address2,lbl_county,lbl_country,lbl_CHECKOUT_FIELD_DESCRIPTION_s_country,lbl_CHECKOUT_FIELD_DESCRIPTION_s_country,lbl_country,lbl_CHECKOUT_FIELD_DESCRIPTION_s_country,lbl_CHECKOUT_FIELD_DESCRIPTION_s_country,lbl_zip_code,lbl_CHECKOUT_FIELD_DESCRIPTION_s_zipcode,lbl_CHECKOUT_FIELD_DESCRIPTION_s_zipcode,lbl_fill_in_examples_zip,lbl_state,lbl_CHECKOUT_FIELD_DESCRIPTION_s_state,lbl_CHECKOUT_FIELD_DESCRIPTION_s_state,lbl_state,lbl_CHECKOUT_FIELD_DESCRIPTION_s_state,lbl_CHECKOUT_FIELD_DESCRIPTION_s_state,lbl_city,lbl_CHECKOUT_FIELD_DESCRIPTION_s_city,lbl_CHECKOUT_FIELD_DESCRIPTION_s_city,lbl_fill_in_examples_city"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/register_shipping_address.tpl"), $this); endif;  if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>

<script type="text/javascript">
//<![CDATA[
<?php echo '

$(document).ready(function() {

    $("#s_zipcode, #s_city").autocomplete("zip_json.php", {
        minChars: 3,
        selectFirst: true,
        matchSubset: true,
//        width: 220,
        scrollHeight: 300,
        max: 1024,
        dataType: \'json\',
        extraParams: {
            zip: function () {
                return $("#s_zipcode:focus").val();
            },
            city: function () {
                var c = $("#s_city:focus").val();
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
	  if ($("#s_countryname").val() == "United States"){
            return "<span class=\'ac_zip\'>" + item.zip + "</span>" +
                              "<span class=\'ac_city\'>" + item.city +
                              ", " + item.state + "</span>";
	  } else {
            return false;
	  }
        },
    });

    $("#s_zipcode, #s_city").result(function (event, item) {
        $("#s_zipcode").val(item.zip);
        $("#s_city").val(item.city);
        $("#s_state").val(item.state);
        $("#s_statename").val(item.state_name);
    });

});


$(function(){
  $("#s_firstname").focusout(function(event){

	if (document.forms["registerform"].s_firstname.value != "" && document.forms["registerform"].firstname){
        	document.forms["registerform"].firstname.value = document.forms["registerform"].s_firstname.value;

                document.getElementById("firstname_verified").style.display = \'\';                      
                document.getElementById("firstname_error").style.display = \'none\';  
	}

	event.preventDefault();
  });
});

'; ?>

//]]>
</script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '

	function cidev_strtoupper (str) {
		return (str + \'\').toUpperCase();
	}

        function cidev_get_country_code (countryname_id){

		var countryname_value = $(\'#\'+countryname_id).val();
                countryname_value = $.trim(countryname_value); 

                var countrycode_value = countryname_value;

                '; ?>

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
                <?php echo '
                if (cidev_strtoupper(countryname_value) == cidev_strtoupper("';  echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '")){
                        countrycode_value = "';  echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '";
                }
                '; ?>

                <?php endfor; endif; ?>
                <?php echo '

        	return countrycode_value;
        }

        function cidev_get_state_code (statename_id, countryname_id){

                var statename_value = $(\'#\'+statename_id).val(); 
                statename_value = $.trim(statename_value); 
                var statecode_value = statename_value;
                var countrycode_value = cidev_get_country_code(countryname_id);

                '; ?>

                <?php unset($this->_sections['state_idx']);
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
?>
                <?php echo '
                if (cidev_strtoupper(statename_value) == cidev_strtoupper("';  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '") && cidev_strtoupper(countrycode_value) == cidev_strtoupper("';  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['country_code'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '")){
                        statecode_value = "';  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state_code'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '";
                }
                '; ?>

                <?php endfor; endif; ?>
                <?php echo '
		
		return statecode_value;
        }

	function ltrim(stringToTrim) {
		return stringToTrim.replace(/^\\s+/,"");
	}

        function check_zip_code_ship(zipcode_id, countryname_id) {

		var zipcode = $(\'#\'+zipcode_id).val();
//		zipcode = $.trim(zipcode);
		zipcode = ltrim(zipcode);

		$(\'#\'+zipcode_id).val(zipcode);

		var countrySelected = cidev_get_country_code(countryname_id);

                return cidev_new_check_zip_code_field(countrySelected, cidev_id$(zipcode_id), zipcode_id);
        }

        function cidev_check_zip(){

          cidev_check_verified_image_for_field(\'s_statename\');
          cidev_check_verified_image_for_field(\'s_zipcode\');
          cidev_check_verified_image_for_field(\'s_city\');
	  return true; ///////////////////////////////////

                var s_city_in_registerform = document.forms["registerform"].s_city.value;
                var s_state_in_registerform = cidev_get_state_code("s_statename", "s_countryname");
//                var s_zipcode_in_registerform_length = document.forms["registerform"].s_zipcode.value.length;
                var s_zipcode_in_registerform = document.forms["registerform"].s_zipcode.value;

                var s_country_in_registerform = cidev_get_country_code("s_countryname");
                if (s_country_in_registerform == "US"){
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = \'cidev_filter_mode=show_zip_reg_form&s_city_in_registerform=\' + s_city_in_registerform + \'&s_state_in_registerform=\' + s_state_in_registerform + \'&s_zipcode_in_registerform=\' + s_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_show_zip").innerHTML=cidev_xmlHttp.responseText;


							if (cidev_id$("s_zip_show_text")){
								document.forms["registerform"].s_zipcode.value = cidev_id$("s_zip_show_text").value;

						                if (cidev_id$("s_zip_show_text").value != ""){
						                        document.getElementById("s_zipcode_verified").style.display = \'\';                      
						                        document.getElementById("s_zipcode_error").style.display = \'none\';     
						                        document.getElementById("s_zipcode_error_text").style.display = \'none\';     
						                        document.getElementById("s_zipcode_error_text_div").innerHTML=\'\';     
					        	        }
					                	else {
					                        	document.getElementById("s_zipcode_verified").style.display = \'none\';                      
						                        document.getElementById("s_zipcode_error").style.display = \'\';  
						                }
							}

							cidev_check_verified_image_for_field("s_city");

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
                                setTimeout(\'cidev_check_zip()\', 1000);
                        }
                }
        }

        function cidev_check_address() {
                var s_country_in_registerform = cidev_get_country_code("s_countryname");


                if (s_country_in_registerform == "US"){
			document.forms["registerform"].s_zipcode.value = document.forms["registerform"].s_zipcode.value.replace(/[^\\w]/g, "");

                        if (document.forms["registerform"].s_zipcode.value.length == "5"){
                                document.getElementById("s_zipcode_error_text").style.display = \'none\';
                        }

//                        cidev_show_state_city();

                } else {
			document.forms["registerform"].s_zipcode.value = document.forms["registerform"].s_zipcode.value.replace(/[^\\w\\s]/g, "");
		}

                if (s_country_in_registerform == "CA"){
                        if (document.forms["registerform"].s_zipcode.value.length == "6"){
                                document.getElementById("s_zipcode_error_text").style.display = \'none\';
                        }
		}
        }

        function cidev_show_state_city(){
			document.forms["registerform"].s_zipcode.value = ltrim(document.forms["registerform"].s_zipcode.value);
                        var s_zipcode_in_registerform = document.forms["registerform"].s_zipcode.value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = \'cidev_filter_mode=show_state_city_reg_form&s_zipcode_in_registerform=\' + s_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_state_city_values").innerHTML=cidev_xmlHttp.responseText;

							if (cidev_id$("s_state_show_text")){
	
                	                                        document.forms["registerform"].s_statename.value = cidev_id$("s_state_show_text").value;

                                                                if (cidev_id$("s_state_show_text").value != ""){
                                                                        document.getElementById("s_statename_verified").style.display = \'\';                        
                                                                        document.getElementById("s_statename_error").style.display = \'none\';       
                                                                }
                                                                else {
                                                                        document.getElementById("s_statename_verified").style.display = \'none\';                        
                                                                        document.getElementById("s_statename_error").style.display = \'\';    
                                                                }
							}

							if (cidev_id$("s_city_show_text")){
								document.forms["registerform"].s_city.value = cidev_id$("s_city_show_text").value;

						                if (cidev_id$("s_city_show_text").value != ""){
						                        document.getElementById("s_city_verified").style.display = \'\';                         
						                        document.getElementById("s_city_error").style.display = \'none\';        
					        	        }
					                	else {
					                        	document.getElementById("s_city_verified").style.display = \'none\';                         
						                        document.getElementById("s_city_error").style.display = \'\';  
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
                                setTimeout(\'cidev_show_state_city()\', 1000);
                        }
        }

        function onSelectChange() {

return true; ///////////////////////////////////

                var cityFilePath = \'\';
                var stateSelected = cidev_get_state_code("s_statename", "s_countryname");

                $(\'#s_city\').unautocomplete();

                var countrySelected = cidev_get_country_code("s_countryname"); 
        
                if (countrySelected == "US"){

                        cityFilePath = "skin1_kolin/US_City_List/" +stateSelected.toLowerCase()+".js";

                        $.getScript(cityFilePath, function() {

                                $(\'#s_city\').autocomplete(city, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        };

        function cidev_load_countries() {
                var countryFilePath = "skin1_kolin/US_City_List/all_countries.js";
                
                        $.getScript(countryFilePath, function() {

                                $(\'#s_countryname\').autocomplete(country_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
        }

        function cidev_load_states() {

                var stateFilePath = "";
                var country_code = cidev_get_country_code("s_countryname");
        
                if (country_code == "US"){
                        stateFilePath = "skin1_kolin/US_City_List/us_states.js";
                }
                if (country_code == "CA"){
                        stateFilePath = "skin1_kolin/US_City_List/ca_states.js";
                }
                
                $(\'#s_statename\').unautocomplete();

                if (country_code == "US" || country_code == "CA"){
                        $.getScript(stateFilePath, function() {

                                $(\'#s_statename\').autocomplete(state_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        }


	function cidev_check_verified_image_for_field(field_id){

                if ($(\'#\'+field_id).val() != ""){
			if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
	                        document.getElementById(field_id+"_verified").style.display = \'\';                      
        	                document.getElementById(field_id+"_error").style.display = \'none\';     
			}
                }
                else {
			if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
	                        document.getElementById(field_id+"_verified").style.display = \'none\';                      
        	                document.getElementById(field_id+"_error").style.display = \'\';  
			}
                }
	}


  $(document).ready(function() {  

	$(\'#s_firstname\').focusout(function() {
		cidev_check_verified_image_for_field("s_firstname");
	});

        $(\'#s_address\').focusout(function() {
		cidev_check_verified_image_for_field(\'s_address\');
        });

	$(\'#s_address_2\').focusout(function() {
                if ($(\'#s_address_2\').val() != ""){
                        if (document.getElementById("s_address_2") && document.getElementById("s_address_2_error")){
                                document.getElementById("s_address_2_verified").style.display = \'\';                      
                                document.getElementById("s_address_2_error").style.display = \'none\';     
                        }
                }
                else {
                        if (document.getElementById("s_address_2_verified") && document.getElementById("s_address_2_error")){
                                document.getElementById("s_address_2_verified").style.display = \'none\';                      
                                document.getElementById("s_address_2_error").style.display = \'none\';  
                        }
                }
	});

        $(\'#s_zipcode\').focusout(function() {
		cidev_check_verified_image_for_field(\'s_zipcode\');
		onSelectChange();
        });

        $(\'#s_city\').focusout(function() {
                cidev_check_verified_image_for_field(\'s_statename\');
                cidev_check_verified_image_for_field(\'s_zipcode\');
		cidev_check_verified_image_for_field(\'s_city\');
        });

        $(\'#s_city\').change(function() {
                cidev_check_verified_image_for_field(\'s_statename\');
                cidev_check_verified_image_for_field(\'s_zipcode\');
                cidev_check_verified_image_for_field(\'s_city\');
        });

        $(\'#s_statename\').change(function() {
                cidev_check_verified_image_for_field(\'s_statename\');
                cidev_check_verified_image_for_field(\'s_zipcode\');
	});

        $(\'#s_countryname\').focusout(function() {

                var countrySelected = cidev_get_country_code("s_countryname");

                if (countrySelected == "US" || countrySelected == "CA"){
			cidev_load_states();
                        onSelectChange();
                } 

		if (countrySelected != "US") {
			$(\'#s_city\').unautocomplete();
		}

		if (countrySelected != "US" && countrySelected != "CA") {
			$(\'#s_statename\').unautocomplete();
		}

		cidev_check_verified_image_for_field(\'s_countryname\');

		if ($(\'#s_zipcode\').val() != ""){
                        document.getElementById("s_zipcode_error_text").style.display = \'none\';     
                        document.getElementById("s_zipcode_error_text_div").innerHTML=\'\'; 
		}

        });

        $(\'#s_statename\').focusout(function() {
                onSelectChange();

		cidev_check_verified_image_for_field(\'s_statename\');
		cidev_check_verified_image_for_field(\'s_zipcode\');

        });

	function start() {
		cidev_load_countries();
		cidev_load_states();
        	onSelectChange();
	}

        window.onload = start();
  });


'; ?>

<?php if ($this->_tpl_vars['login'] == ""):  echo '

        var geo_litecity_location_city = "';  echo $this->_tpl_vars['geo_litecity_location']['city'];  echo '";
        var geo_litecity_location_region = "';  echo $this->_tpl_vars['geo_litecity_location']['region'];  echo '";
        var geo_litecity_location_country = "';  echo $this->_tpl_vars['geo_litecity_location']['country'];  echo '";

	'; ?>

	<?php unset($this->_sections['state_idx']);
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
?>
	<?php echo '
        if (geo_litecity_location_region == "';  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state_code'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '" && geo_litecity_location_country == "';  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['country_code'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '"){
        	var geo_litecity_location_region_name = "';  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '";
        }
        '; ?>

        <?php endfor; endif; ?>
	<?php echo '

'; ?>

<?php else:  echo '
        var geo_litecity_location_city = "";
        var geo_litecity_location_region = "";
        var geo_litecity_location_country = "";
	var geo_litecity_location_region_name = "";
'; ?>

<?php endif;  echo '



'; ?>

//]]>
</script>

<?php else: ?>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '
        var geo_litecity_location_city = "";
        var geo_litecity_location_region = "";
        var geo_litecity_location_country = "";
	var geo_litecity_location_region_name = "";
'; ?>

//]]>
</script>

<?php endif; ?>

<?php if ($this->_tpl_vars['is_areas']['S'] == 'Y'):  if ($this->_tpl_vars['hide_header'] == ""): ?>
<tr>
<td colspan="3" class="RegSectionTitle"><?php echo $this->_tpl_vars['lng']['lbl_shipping_address']; ?>
<hr size="1" noshade="noshade" /></td>
</tr>
<?php endif; ?>

<tr>
<td colspan="3"><?php echo $this->_tpl_vars['lng']['txt_fields_are_mandatory']; ?>


<div id="cidev_reg_form_state_city_values">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/cidev_reg_form_state_city_values.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div id="cidev_reg_form_show_zip">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/cidev_reg_form_show_zip.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<input type="hidden" name="clear_city_in_Change_states_js" id="clear_city_in_Change_states_js" value="Y">

</td>
</tr>

<?php if ($this->_tpl_vars['default_fields']['s_title']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_title']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_title']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap"> 
<select name="s_title" id="s_title">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/title_selector.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['userinfo']['s_titleid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</select> 
</td> 
</tr> 
 <?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_firstname']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" width="49%" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_firstname']; ?>
</div><?php endif; ?>
</td>
<td valign="top" width="5"><?php if ($this->_tpl_vars['default_fields']['s_firstname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['usertype'] == 'C'): ?>width="*"<?php endif; ?>> 

<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_firstname" name="s_firstname" size="32" maxlength="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['s_firstname'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&amp;#039;", "'") : smarty_modifier_replace($_tmp, "&amp;#039;", "'")); ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_firstname']; ?>
" onkeyup="cidev_check_field_name('s_firstname')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="s_firstname_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['s_firstname'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_firstname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_firstname'] == "" && $this->_tpl_vars['default_fields']['s_firstname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
 <?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_lastname']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_lastname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_lastname" name="s_lastname" size="32" maxlength="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['s_lastname'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&amp;#039;", "'") : smarty_modifier_replace($_tmp, "&amp;#039;", "'")); ?>
" onkeyup="cidev_check_field_name('s_lastname')" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="s_lastname_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['s_lastname'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_lastname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_lastname'] == "" && $this->_tpl_vars['default_fields']['s_lastname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_additional_info.tpl", 'smarty_include_vars' => array('section' => 'S')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['default_fields']['s_address']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_address']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_address']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_address" name="s_address" size="32" maxlength="64" value="<?php if ($this->_tpl_vars['userinfo']['s_address'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'):  echo $this->_tpl_vars['config']['Company']['location_address'];  else:  echo $this->_tpl_vars['userinfo']['s_address'];  endif; ?>" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_address']; ?>
" onkeyup="cidev_check_field_address('s_address')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="s_address_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['s_address'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_address_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_address'] == "" && $this->_tpl_vars['default_fields']['s_address']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_address_2']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_address_2']; ?>
 <?php if ($this->_tpl_vars['default_fields']['s_address_2']['required'] != 'Y'): ?><font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font><?php endif;  if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_address2']; ?>
</div><?php endif; ?></td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_address_2']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap" valign="top">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_address_2" name="s_address_2" size="32" maxlength="64" value="<?php echo $this->_tpl_vars['userinfo']['s_address_2']; ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_address2']; ?>
" onkeyup="cidev_check_field_address('s_address_2')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="s_address_2_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['s_address_2'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_address_2_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_address_2'] == "" && $this->_tpl_vars['default_fields']['s_address_2']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_county']['avail'] == 'Y' && $this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_county']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/counties.tpl", 'smarty_include_vars' => array('counties' => $this->_tpl_vars['counties'],'name' => 's_county','default' => $this->_tpl_vars['userinfo']['s_county'],'country_name' => 's_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if (( $this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_county'] == "" && $this->_tpl_vars['default_fields']['s_county']['required'] == 'Y' ) || $this->_tpl_vars['error'] == 's_county'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['default_fields']['s_country']['avail'] == 'Y'): ?>

<?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_country'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_country']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_country']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_countryname" name="s_countryname" size="32" maxlength="64" value="<?php if ($this->_tpl_vars['geo_litecity_location']['country'] != ""):  unset($this->_sections['country_idx']);
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
 if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']):  if ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'] != ""):  echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  $this->assign('cidev_is_country', 'Y');  endif;  endif;  endfor; endif;  else:  if ($this->_tpl_vars['userinfo']['s_countryname'] != ""):  echo $this->_tpl_vars['userinfo']['s_countryname'];  $this->assign('cidev_is_country', 'Y');  endif;  endif; ?>" 
onkeyup="cidev_check_country_usa('s_countryname'); cidev_check_field_country('s_countryname'); cidev_check_zip();"  onchange="cidev_check_field_country('s_countryname'); cidev_check_zip();"
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
<td id="s_countryname_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['cidev_is_country'] != 'Y'): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_countryname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_country'] == "" && $this->_tpl_vars['default_fields']['s_country']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>


<input type="hidden" id="s_country" name="s_country" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['s_country']; ?>
" />


</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_country'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_country']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_country']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<select name="s_country" id="s_country" size="1" onchange="check_zip_code();"
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
" <?php if ($this->_tpl_vars['userinfo']['s_country'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'): ?> <?php if ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['config']['Company']['location_country']): ?> selected="selected"<?php endif; ?> <?php else:  if ($this->_tpl_vars['geo_litecity_location']['country'] != ""):  if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php endif;  else:  if ($this->_tpl_vars['userinfo']['s_country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php elseif ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['config']['General']['default_country'] && $this->_tpl_vars['userinfo']['s_country'] == ""): ?> selected="selected"<?php endif;  endif;  endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</option>
<?php endfor; endif; ?>
</select>
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_country'] == "" && $this->_tpl_vars['default_fields']['s_country']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php endif; ?>



<?php if ($this->_tpl_vars['default_fields']['s_zipcode']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_zipcode'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_zipcode']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_zipcode']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">

<input type="text" id="s_zipcode" name="s_zipcode" size="32" maxlength="32" value="<?php if ($this->_tpl_vars['userinfo']['s_zipcode'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'):  echo $this->_tpl_vars['config']['Company']['location_zipcode'];  else:  if ($this->_tpl_vars['geo_litecity_location']['country'] != "" && $this->_tpl_vars['geo_litecity_location']['country'] == 'US'):  echo $this->_tpl_vars['geo_litecity_location']['postalCode'];  else:  echo $this->_tpl_vars['userinfo']['s_zipcode'];  endif;  endif; ?>" <?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?> onkeyup="cidev_check_field('s_zipcode'); cidev_check_address();" onchange="cidev_new_check_zip_code(); check_zip_code_ship('s_zipcode', 's_countryname');" <?php endif; ?> autocomplete="off" placeholder="<?php if ($this->_tpl_vars['geo_litecity_location']['postalCode'] != ""):  echo $this->_tpl_vars['geo_litecity_location']['postalCode'];  else:  echo $this->_tpl_vars['lng']['lbl_fill_in_examples_zip'];  endif; ?>" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="s_zipcode_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['geo_litecity_location']['postalCode'] == "" && $this->_tpl_vars['userinfo']['s_zipcode'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_zipcode_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>

<td id="s_zipcode_error_text" valign="top" style="display: none;">
<div class="cidev_NoteBox" id="s_zipcode_error_text_div"></div>
</td>

<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_zipcode'] == "" && $this->_tpl_vars['default_fields']['s_zipcode']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['default_fields']['s_state']['avail'] == 'Y'): ?>

<?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_state'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_state']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_state']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_statename" name="s_statename" size="32" maxlength="64" 
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
 if ($this->_tpl_vars['geo_litecity_location']['country'] == $this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['country_code'] && $this->_tpl_vars['geo_litecity_location']['region'] == $this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state_code']):  if ($this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state'] != ""):  echo ((is_array($_tmp=$this->_tpl_vars['states'][$this->_sections['state_idx']['index']]['state'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  $this->assign('cidev_is_state', 'Y');  endif;  endif;  endfor; endif;  else:  if ($this->_tpl_vars['userinfo']['s_statename'] != ""):  echo $this->_tpl_vars['userinfo']['s_statename'];  $this->assign('cidev_is_state', 'Y');  endif;  endif; ?>
" 
onkeyup="cidev_check_field_country('s_statename'); cidev_check_zip(); cidev_check_verified_image_for_field('s_zipcode');" 
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

<?php endif;  endfor; endif;  else:  echo $this->_tpl_vars['userinfo']['s_statename']; ?>

<?php endif; ?>
" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="s_statename_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['cidev_is_state'] != 'Y'): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_statename_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_state'] == "" && $this->_tpl_vars['default_fields']['s_state']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>


<input type="hidden" id="s_state" name="s_state" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['s_state']; ?>
" />

</td>
</tr>
<?php endif; ?>



<?php if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_state'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_state']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_state']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
  <?php if ($this->_tpl_vars['userinfo']['s_state'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => 's_state','default' => $this->_tpl_vars['config']['Company']['location_state'],'default_country' => $this->_tpl_vars['config']['Company']['location_country'],'country_name' => 's_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => 's_state','default' => $this->_tpl_vars['userinfo']['s_state'],'default_country' => ((is_array($_tmp=@$this->_tpl_vars['userinfo']['s_country'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['General']['default_country']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['General']['default_country'])),'country_name' => 's_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif;  if (( $this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_state'] == "" && $this->_tpl_vars['default_fields']['s_state']['required'] == 'Y' ) || $this->_tpl_vars['error'] == 's_statecode'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_state']['avail'] == 'Y' && $this->_tpl_vars['default_fields']['s_country']['avail'] == 'Y' && $this->_tpl_vars['js_enabled'] == 'Y' && $this->_tpl_vars['config']['General']['use_js_states'] == 'Y'): ?>
<tr style="display: none;">
	<td valign="top">
  <?php if ($this->_tpl_vars['userinfo']['s_state'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => 's_state','country_name' => 's_country','county_name' => 's_county','state_value' => $this->_tpl_vars['config']['Company']['location_state'],'county_value' => $this->_tpl_vars['userinfo']['s_county'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => 's_state','country_name' => 's_country','county_name' => 's_county','state_value' => $this->_tpl_vars['userinfo']['s_state'],'county_value' => $this->_tpl_vars['userinfo']['s_county'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
	</td>
</tr>
<?php endif;  endif; ?>



<?php if ($this->_tpl_vars['default_fields']['s_city']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_city'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_s_city']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['s_city']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_city" name="s_city" size="32" maxlength="64" value="<?php if ($this->_tpl_vars['userinfo']['s_city'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'):  echo $this->_tpl_vars['config']['Company']['location_city'];  else:  if ($this->_tpl_vars['geo_litecity_location']['country'] != ""):  echo $this->_tpl_vars['geo_litecity_location']['city'];  else:  echo $this->_tpl_vars['userinfo']['s_city'];  endif;  endif; ?>" <?php if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?> onkeyup="cidev_check_field('s_city'); cidev_check_zip(); cidev_check_verified_image_for_field('s_zipcode');" <?php endif; ?> placeholder="<?php if ($this->_tpl_vars['geo_litecity_location']['city'] != ""):  echo $this->_tpl_vars['geo_litecity_location']['city'];  else:  echo $this->_tpl_vars['lng']['lbl_fill_in_examples_city'];  endif; ?>" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="s_city_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['geo_litecity_location']['city'] == "" && $this->_tpl_vars['userinfo']['s_city'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="s_city_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_city'] == "" && $this->_tpl_vars['default_fields']['s_city']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/register_shipping_address.tpl"), $this); endif; ?>