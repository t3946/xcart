// $Id: manage_departments.js,v 1.0 2011/03/30 12:59:01 kate Exp $

function add_department_row(index) {
	
	row_max_index = row_max_index + 1;
	
	$('#dep_' + index).after(
		'<tr id="dep_' + row_max_index + '">' +
			'<td style="padding: 2px 0px;">' +
				lbl_department + ':&nbsp;<input style="background-color: #ffffff;" type="text" size="30" name="deps[' + row_max_index + '][name]" value="" />&nbsp;&nbsp;&nbsp;' +
				lbl_email + ':&nbsp;<input style="background-color: #ffffff;" type="text" size="30" name="deps[' + row_max_index + '][email]" value="" onchange="javascript: checkEmailAddress(this);" />&nbsp;' +
				'<a href="javascript: void(0);" onclick="javascript: add_department_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
				'&nbsp;&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_department_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +

				'&nbsp;&nbsp;<B>Frozen</B> <input type="checkbox" name="deps[' + row_max_index + '][frozen]" value="Y" disabled="disabled" />' +

			'</td>' +
		'</tr>'
	);
}

function remove_department_row(index) {
	$('#dep_' + index).remove();
}
