// $Id: check_zipcode.js,v 1.3 2006/03/09 11:24:01 max Exp $

// check_zip_code_rules is defined in check_zipcode_js.tpl

function check_zip_code_field(cnt, zip) {
	var c_code;
	var zip_error = false;

	if (!zip || zip.value == "")
		return true;

//	if (cnt.options){
		c_code = cnt ? cnt.options[cnt.selectedIndex].value : config_default_country;
//	}

	if (check_zip_code_rules[c_code] != undefined) {
		var rules = check_zip_code_rules[c_code];

		if (rules.lens != undefined	&& rules.lens[zip.value.length] == undefined)
			zip_error = true;

		if (rules.re != undefined && zip.value.search(rules.re) != -1)
			zip_error = true;

		if (zip_error) {
			if (rules.error && rules.error.length > 0)
				alert(rules.error);
			zip.focus();
			return false;
		}
	}

	return !zip_error;
}

function check_zip_code() {
	if($('#ship2diff').attr('checked')) {
	return check_zip_code_field(document.forms["registerform"].b_country, document.forms["registerform"].b_zipcode) && check_zip_code_field(document.forms["registerform"].s_country, document.forms["registerform"].s_zipcode); 
	} else {
		return check_zip_code_field(document.forms["registerform"].s_country, document.forms["registerform"].s_zipcode); 
	}
}

function cidev_new_check_zip_code() {

	var countrySelected = cidev_get_country_code("s_countryname");

        if($('#ship2diff').attr('checked')) {

		var countrySelected_b = cidev_get_country_code("b_countryname");

	        return cidev_new_check_zip_code_field(countrySelected_b, document.forms["registerform"].b_zipcode, 'b_zipcode') && cidev_new_check_zip_code_field(countrySelected, document.forms["registerform"].s_zipcode, 's_zipcode');
        } else {
                return cidev_new_check_zip_code_field(countrySelected, document.forms["registerform"].s_zipcode, 's_zipcode');
        }
}

function cidev_new_check_zip_code_field(cnt, zip, zipcode_id) {
        var c_code;
        var zip_error = false;

        if (!zip || zip.value == "")
                return true;

	c_code = cnt;

        if (check_zip_code_rules[c_code] != undefined) {
                var rules = check_zip_code_rules[c_code];

                if (rules.lens != undefined     && rules.lens[zip.value.length] == undefined)
                        zip_error = true;

                if (rules.re != undefined && zip.value.search(rules.re) != -1)
                        zip_error = true;

                if (zip_error) {
                        if (rules.error && rules.error.length > 0){
//                                alert(rules.error);

//alert(zipcode_id);

		                if (document.getElementById(zipcode_id+'_verified')){
                	        	document.getElementById(zipcode_id+"_error").style.display = '';  
                	        	document.getElementById(zipcode_id+"_error_text").style.display = '';  
                	        	document.getElementById(zipcode_id+"_error_text_div").innerHTML=rules.error;  
		                }

			}
                        zip.focus();
                        return false;
                }
        }

        return !zip_error;
}


function cidev_check_field (id) {

	var field_val = $('#'+id).val();

	// delete all charaters (exept: "_", " ")
	field_val = field_val.replace(/[^\w\s]/g, '');
	// delete all spaces from the beggining of the strings
	field_val = field_val.replace(/^\s*(\S*)/gm, '$1'); 
	// delete multiple spaces
	field_val = field_val.replace(/[ \t]+/gm, ' ');
	// delete "_"
	field_val = field_val.replace(/[_]+/gm, '');

	$('#'+id).val(field_val);
}

function cidev_check_field_name (id) {

        var field_val = $('#'+id).val();

        // delete all charaters (exept: "_", " ")
        field_val = field_val.replace(/[^\w\s\.\-]/g, '');
        // delete all spaces from the beggining of the strings
        field_val = field_val.replace(/^\s*(\S*)/gm, '$1');
        // delete multiple spaces
        field_val = field_val.replace(/[ \t]+/gm, ' ');
        // delete "_"
        field_val = field_val.replace(/[_]+/gm, '');

        $('#'+id).val(field_val);
}

function cidev_check_field_country (id) {

        var field_val = $('#'+id).val();

        // delete all charaters (exept: "_", " ")
        field_val = field_val.replace(/[^\w\s\.\,\(\)]/g, '');
        // delete all spaces from the beggining of the strings
        field_val = field_val.replace(/^\s*(\S*)/gm, '$1');
        // delete multiple spaces
        field_val = field_val.replace(/[ \t]+/gm, ' ');
        // delete "_"
        field_val = field_val.replace(/[_]+/gm, '');

        $('#'+id).val(field_val);
}


function cidev_check_field_address (id) {

        var field_val = $('#'+id).val();

        // delete all charaters (exept: "_", " ")
        field_val = field_val.replace(/[^\w\s\-\.\,\#\(\)\/\\]/g, '');
        // delete all spaces from the beggining of the strings
        field_val = field_val.replace(/^\s*(\S*)/gm, '$1');
        // delete multiple spaces
        field_val = field_val.replace(/[ \t]+/gm, ' ');
        // delete "_"
        field_val = field_val.replace(/[_]+/gm, '');

/*
	if ($('#'+id+'_verified')){
		if (field_val != ""){

		}
		else {

		}
	}
*/

        $('#'+id).val(field_val);
}

function cidev_set_check_state_field(country_val, state_id) {
                if (country_val != "US" && country_val != "CA" && country_val != "AT" && country_val != "DE" && country_val != "AU" && country_val != "BE" && country_val != "ES" && country_val != "FR" && country_val != "IT" && country_val != "LU" && country_val != "NL"){
                        $("#"+state_id).keyup(function(){
                                cidev_check_field(state_id);
                        });
                }
}

function cidev_showNote(id, next_to) {
  var div = $('#'+id).get();
  $('#'+id).remove();
  $('body').append(div);

  $('#'+id).show();
  var sw = cidev_getRealWidth('#'+id);

  $('#'+id).css('left', $(next_to).offset().left + $(next_to).width() + 'px');
  $('#'+id).css('top', $(next_to).offset().top + 'px');
  $('#'+id).css('width', sw + 'px');
  $('#'+id).show();
}

function cidev_getRealWidth(jsel) {
  var sw = $(jsel).attr('scrollWidth');
  var pl = parseInt($(jsel).css('padding-left'));
  if (!isNaN(pl))
    sw -= pl;
  var pr = parseInt($(jsel).css('padding-right'));
  if (!isNaN(pr))
    sw -= pr;
  return sw;
}

function cidev_check_field_phone (id) {

        var field_val = $('#'+id).val();

        field_val = field_val.replace(/[^0-9\s\-\(\)\+]/g, '');
        // delete all spaces from the beggining of the strings
        field_val = field_val.replace(/^\s*(\S*)/gm, '$1');
        // delete multiple spaces
        field_val = field_val.replace(/[ \t]+/gm, ' ');

        $('#'+id).val(field_val);
}

function cidev_check_field_if_empty (id) {

        var field_val = $('#'+id).val();

        // delete all spaces from the beggining of the strings
        field_val = field_val.replace(/^\s*(\S*)/gm, '$1');
        // delete multiple spaces
        field_val = field_val.replace(/[ \t]+/gm, ' ');

        $('#'+id).val(field_val);
}

function cidev_check_country_usa(id) {

        var field_val = $('#'+id).val().toUpperCase();

	if (field_val == "USA"){
		$('#'+id).val("United States");
	}
}
