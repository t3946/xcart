/*
+----------------------------------------------------------------------+
| Advanced Filter Mod                                                  |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

function cidev_send_filter_values(){

	$("#cidev_narrowed_result").addClass("cidev_ajax_loading");

	cidev_xmlHttp=cidev_createHttpRequestObject();
	if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

		var cidev_form_action = document.cidev_product_filter_form.action;

		var cidev_parameters = 'mode=search&cidev_filter_mode=search';

		var cidev_checked_filter_values = '';
		for (x = 0; x < cidev_id$('cidev_count_active_fv').value; x++) {
			try {
				var cidev_tmpField = cidev_id$('cidev_fv_' + x).value;
			} catch (e) {}
			if (cidev_tmpField && cidev_id$('cidev_fv_' + x).checked){
				cidev_checked_filter_values += '&cidev_filter_values[' + cidev_tmpField + ']=Y';
			}
			cidev_tmpField = false;
		}
		cidev_parameters += cidev_checked_filter_values;

		cidev_xmlHttp.onreadystatechange=function(){
			if(cidev_xmlHttp.readyState==4){
				if(cidev_xmlHttp.status==200){
					document.getElementById("cidev_narrowed_result").innerHTML=cidev_xmlHttp.responseText;
					$("#cidev_narrowed_result").removeClass("cidev_ajax_loading");

					cidev_show_filter_values(cidev_checked_filter_values);
				}else{
					cidev_Error('no_server', 'Y');
				}
			}
		};

		cidev_xmlHttp.open('POST',cidev_form_action,true);
		cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
		cidev_xmlHttp.setRequestHeader('Connection','close');
		cidev_xmlHttp.send(cidev_parameters);
	}
	else {
		setTimeout('cidev_send_filter_values()', 1000);
	}
}

function cidev_show_filter_values(cidev_filter_values){

	$("#cidev_filter_menu").addClass("cidev_ajax_loading");

	var cidev_fv_active_found_str = cidev_id$('cidev_fv_active_found_str').value;

        cidev_xmlHttp=cidev_createHttpRequestObject();
        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                var cidev_parameters = 'cidev_filter_mode=show_filter_values&cidev_fv_active_found_str=' + cidev_fv_active_found_str + cidev_filter_values;

                cidev_xmlHttp.onreadystatechange=function(){
                        if(cidev_xmlHttp.readyState==4){
                                if(cidev_xmlHttp.status==200){
                                        document.getElementById("cidev_filter_menu").innerHTML=cidev_xmlHttp.responseText;
					$("#cidev_filter_menu").removeClass("cidev_ajax_loading");
                                }else{
                                        cidev_Error('no_server', 'Y');
                                }
                        }
                };

                cidev_xmlHttp.open('POST','cidev_filter_values.php',true);
                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                cidev_xmlHttp.setRequestHeader('Connection','close');
                cidev_xmlHttp.send(cidev_parameters);
        }
        else {
                setTimeout('cidev_show_filter_values()', 1000);
        }
}
