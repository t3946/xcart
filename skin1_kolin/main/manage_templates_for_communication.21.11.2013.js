
function add_template(index) {

	row_max_index = row_max_index + 1;
	$('#tr_header_row').show();
	$('#tr_submit_row').show();
	
	$('#template_row' + index).after(
		'<tr id="template_row' + row_max_index + '">' +

                        '<td valign="top" align="center" width="10">' +
'<input type="checkbox" name="templates_for_communication[' + row_max_index + '][active]" value="Y" checked="checked" style="padding: 0px; margin: -2px 0 0 0;" />' +                
                        '</td>' +

                        '<td valign="top" align="center" width="4%">' +
'<input type="text" size="2" name="templates_for_communication[' + row_max_index + '][pos]" value="" />' +
                        '</td>' +

			'<td valign="top" align="center" width="20%">' +
'<input type="text" size="20" name="templates_for_communication[' + row_max_index + '][template_name]" value="" style="width: 96%;" />' +
                        '</td>' +

                        '<td valign="top" align="center" width="20%">' +
'<input type="text" size="25" name="templates_for_communication[' + row_max_index + '][send_to_email]" value="" style="width: 96%;" />' +
                        '</td>' +

                        '<td valign="top" align="center" width="20%">' +
'<input type="text" size="25" name="templates_for_communication[' + row_max_index + '][subject_line]" value="" style="width: 96%;" />' +
                        '</td>' +

                        '<td valign="top" align="center" width="*">' +
'<textarea cols="45" rows="8" name="templates_for_communication[' + row_max_index + '][message_body]" style="width: 96%;" /></textarea>' +
                        '</td>' +

			'<td valign="top" align="center" width="20">' +
'<a href="javascript: void(0);" onclick="javascript: remove_template(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +
			'</td>' +

		'</tr>'
	);
}

function remove_template(index) {
	$('#template_row' + index).remove();
}
