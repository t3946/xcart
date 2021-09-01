
function add_row(index) {

	row_max_index = row_max_index + 1;
	
	$('#template_row' + index).after(
		'<tr id="template_row' + row_max_index + '">' +

                        '<td valign="top" align="center" width="10">' +
'<input type="text" name="fraud_checks[' + row_max_index + '][question_code]" size="8" style="width: 96%;" />' +                
                        '</td>' +

                        '<td valign="top" align="center" width="10">' +
'<input type="checkbox" name="fraud_checks[' + row_max_index + '][auto]" value="Y" style="padding: 0px; margin: -2px 0 0 0;" />' + 
                        '</td>' +

                        '<td valign="top" align="center" width="4%">' +
'<input type="text" size="8" name="fraud_checks[' + row_max_index + '][importance_factor]" value="" style="width: 96%;" />' +
                        '</td>' +

                        '<td valign="top" align="center" width="4%">' +
'<input type="text" size="2" name="fraud_checks[' + row_max_index + '][orderby]" value="" style="width: 96%;" />' +
                        '</td>' +

                        '<td valign="top" align="center" width="*">' +
'<textarea cols="45" rows="8" name="fraud_checks[' + row_max_index + '][question_template_body]" style="width: 96%;" /></textarea>' +
                        '</td>' +

			'<td valign="top" align="center" width="20">' +
'<a href="javascript: void(0);" onclick="javascript: remove_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +
			'<td valign="top" align="center" width="20">' +
'<a href="javascript: void(0);" onclick="javascript: add_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
			'</td>' +

		'</tr>'
	);
}

function remove_row(index) {
	$('#template_row' + index).remove();
}
