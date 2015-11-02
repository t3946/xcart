{* $Id: check_zipcode_js.tpl,v 1.10.2.1 2006/11/07 11:13:13 twice Exp $ *}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var config_default_country = "{$config.General.default_country}";

// used in check_zip_code_field() from check_zipcode.js
// note: you should update language variables after change this table
{literal}
var check_zip_code_rules = {
AT: { lens:{4:true} },
	CA: { lens:{6:true,7:true} },
	CH: { lens:{4:true} },
	DE: { lens:{5:true}, re:/\D/ },
	LU: { lens:{4:true} },
	US: { lens:{5:true}, re:/\D/ }
};

function check_zip_code_field(cnt, zip) {
	var c_code;
	var zip_error = false;

	if (!zip || zip.value == "")
		return true;

	c_code = cnt ? cnt.options[cnt.selectedIndex].value : config_default_country;

	if (check_zip_code_rules[c_code] != undefined) {
		var rules = check_zip_code_rules[c_code];

		if (rules.lens != undefined && rules.lens[zip.value.length] == undefined)
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
	return check_zip_code_field(document.forms["manufacturer"].b_country, document.forms["manufacturer"].b_zipcode);
}
{/literal}

check_zip_code_rules.AT.error='{$lng.txt_error_at_zip_code|strip_tags|escape:javascript}';
check_zip_code_rules.CA.error='{$lng.txt_error_ca_zip_code|strip_tags|escape:javascript}';
check_zip_code_rules.CH.error='{$lng.txt_error_ch_zip_code|strip_tags|escape:javascript}';
check_zip_code_rules.DE.error='{$lng.txt_error_de_zip_code|strip_tags|escape:javascript}';
check_zip_code_rules.LU.error='{$lng.txt_error_lu_zip_code|strip_tags|escape:javascript}';
check_zip_code_rules.US.error='{$lng.txt_error_us_zip_code|strip_tags|escape:javascript}';

-->
</script>
