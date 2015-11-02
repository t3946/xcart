/*
+----------------------------------------------------------------------+
| Advanced Filter Mod                                                  |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*/

function func_cidev_manuf_checkbox_is_clicked(cidev_number_active_manuf, manufacturerid){

	var full_id_value = "cidev_id_manuf_" + cidev_number_active_manuf;

	var className = $("#" + full_id_value).attr('class');

	if (className == "cidev_checkbox"){
		$("#" + full_id_value).removeClass("cidev_checkbox");
		$("#" + full_id_value).addClass("cidev_checkbox_checked");
		cidev_id$('cidev_manuf_' + cidev_number_active_manuf).value = manufacturerid;
	} else if (className == "cidev_checkbox_checked"){
                $("#" + full_id_value).removeClass("cidev_checkbox_checked");
                $("#" + full_id_value).addClass("cidev_checkbox");
		cidev_id$('cidev_manuf_' + cidev_number_active_manuf).value = "";
        }
	
	cidev_send_filter_values();
}

function func_cidev_reset_filter(){
        $("#cidev_narrowed_result").addClass("cidev_ajax_loading");

        cidev_xmlHttp=cidev_createHttpRequestObject();
        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                var cidev_form_action = document.cidev_product_filter_form.action;
                var cidev_parameters = 'mode=search&cidev_filter_mode=search';
                var cidev_selected_filter_values = '';
                var cidev_selected_manuf_values = '';

                if (cidev_id$('cidev_manufacturerid')){
                        cidev_parameters += '&cidev_selected_manuf_values[' + cidev_id$('cidev_manufacturerid').value + ']=Y';
                }

                cidev_xmlHttp.onreadystatechange=function(){
                        if(cidev_xmlHttp.readyState==4){
                                if(cidev_xmlHttp.status==200){
                                        document.getElementById("cidev_narrowed_result").innerHTML=cidev_xmlHttp.responseText;
                                        $("#cidev_narrowed_result").removeClass("cidev_ajax_loading");

                                        cidev_show_filter_values(cidev_selected_filter_values, cidev_selected_manuf_values);
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
                setTimeout('func_cidev_reset_filter()', 1000);
        }

}

function cidev_send_filter_values(){

	$("#cidev_narrowed_result").addClass("cidev_ajax_loading");

	cidev_xmlHttp=cidev_createHttpRequestObject();
	if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

		var cidev_form_action = document.cidev_product_filter_form.action;

		var cidev_parameters = 'mode=search&cidev_filter_mode=search';

		var cidev_selected_filter_values = '';
		for (x = 0; x < cidev_id$('cidev_count_active_fv').value; x++) {
			try {
				var cidev_tmpField = cidev_id$('cidev_fv_' + x).value;
			} catch (e) {}
			if (cidev_tmpField && cidev_id$('cidev_fv_' + x).checked){
				cidev_selected_filter_values += '&cidev_selected_filter_values[' + cidev_tmpField + ']=Y';
			}
			cidev_tmpField = false;
		}
		cidev_parameters += cidev_selected_filter_values;

		var cidev_selected_manuf_values = '';
		if (cidev_id$('cidev_count_active_manuf')){
        	        for (x = 0; x < cidev_id$('cidev_count_active_manuf').value; x++) {
                	        try {
                        	        var cidev_tmpField = cidev_id$('cidev_manuf_' + x).value;
	                        } catch (e) {}
//        	                if (cidev_tmpField && cidev_id$('cidev_manuf_' + x).checked){
        	                if (cidev_tmpField != ""){
                	                cidev_selected_manuf_values += '&cidev_selected_manuf_values[' + cidev_tmpField + ']=Y';
                        	}
	                        cidev_tmpField = false;
        	        }
                	cidev_parameters += cidev_selected_manuf_values;
		}

		if (cidev_id$('cidev_manufacturerid')){
			cidev_parameters += '&cidev_selected_manuf_values[' + cidev_id$('cidev_manufacturerid').value + ']=Y';
		}
	
		cidev_xmlHttp.onreadystatechange=function(){
			if(cidev_xmlHttp.readyState==4){
				if(cidev_xmlHttp.status==200){
					document.getElementById("cidev_narrowed_result").innerHTML=cidev_xmlHttp.responseText;
					$("#cidev_narrowed_result").removeClass("cidev_ajax_loading");

					cidev_show_filter_values(cidev_selected_filter_values, cidev_selected_manuf_values);
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

function cidev_show_filter_values(cidev_selected_filter_values, cidev_selected_manuf_values){

	$("#cidev_filter_menu").addClass("cidev_ajax_loading");

	var cidev_selected_fv_str = cidev_id$('cidev_selected_fv_str').value;

        cidev_xmlHttp=cidev_createHttpRequestObject();
        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                var cidev_parameters = 'cidev_filter_mode=show_filter_values&cidev_selected_fv_str=' + cidev_selected_fv_str + cidev_selected_filter_values + cidev_selected_manuf_values;

		if (cidev_id$('cidev_selected_manuf_str')){
			var cidev_selected_manuf_str = cidev_id$('cidev_selected_manuf_str').value;
			cidev_parameters += '&cidev_selected_manuf_str=' + cidev_selected_manuf_str;
		}

                cidev_xmlHttp.onreadystatechange=function(){
                        if(cidev_xmlHttp.readyState==4){
                                if(cidev_xmlHttp.status==200){
                                        document.getElementById("cidev_filter_menu").innerHTML=cidev_xmlHttp.responseText;
					$("#cidev_filter_menu").removeClass("cidev_ajax_loading");

					func_cidev_recalc_total_items();

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

function func_cidev_recalc_total_items(){

    if (document.getElementById("cidev_recalc_total_items")){

        $("#cidev_recalc_total_items").addClass("cidev_ajax_loading");

        var cidev_search_result_total_items = cidev_id$('cidev_search_result_total_items').value;
        var cidev_search_result_first_item = cidev_id$('cidev_search_result_first_item').value;
        var cidev_search_result_last_item = cidev_id$('cidev_search_result_last_item').value;

        cidev_xmlHttp=cidev_createHttpRequestObject();
        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                var cidev_parameters = 'cidev_search_result_total_items=' + cidev_search_result_total_items + '&cidev_search_result_first_item=' + cidev_search_result_first_item + '&cidev_search_result_last_item=' + cidev_search_result_last_item;

                cidev_xmlHttp.onreadystatechange=function(){
                        if(cidev_xmlHttp.readyState==4){
                                if(cidev_xmlHttp.status==200){
                                        document.getElementById("cidev_recalc_total_items").innerHTML=cidev_xmlHttp.responseText;
                                        $("#cidev_recalc_total_items").removeClass("cidev_ajax_loading");
                                }else{
                                        cidev_Error('no_server', 'Y');
                                }
                        }
                };

                cidev_xmlHttp.open('POST','cidev_recalc_total_items.php',true);
                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                cidev_xmlHttp.setRequestHeader('Connection','close');
                cidev_xmlHttp.send(cidev_parameters);
        }
        else {
                setTimeout('func_cidev_recalc_total_items()', 1000);
        }
    }
}
