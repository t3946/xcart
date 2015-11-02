
function add_distributor_link_row(index) {
	
	row_max_index = row_max_index + 1;
	
	$('#distributor_link_row' + index).after(
		'<tr id="distributor_link_row' + row_max_index + '">' +
			'<td style="padding: 2px 0px;">' +
				'Link to distributor invoice&nbsp;<input type="text" size="40" name="links_to_distributor_invoices[' + row_max_index + '][link_to_distributor_invoice]" value="" />&nbsp;&nbsp;' +
				'<a href="javascript: void(0);" onclick="javascript: add_distributor_link_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
				'&nbsp;&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_distributor_link_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +


			'</td>' +
		'</tr>'
	);
}

function remove_distributor_link_row(index) {
	$('#distributor_link_row' + index).remove();
}
