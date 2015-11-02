
function add_distributor_link_row(index, m_id, invoice_number) {
	
	var row_max_index = $('#row_max_index_' + m_id + '_' + invoice_number).val();
	row_max_index++;
	$('#row_max_index_' + m_id + '_' + invoice_number).val(row_max_index);

	$('#distributor_link_row_' + m_id + '_' + invoice_number + '_' + index).after(
		'<tr id="distributor_link_row_' + m_id + '_' + invoice_number + '_' + row_max_index + '">' +
			'<td style="padding: 2px 0px;">' +
				'Link to distributor invoice&nbsp;<input type="text" size="40" name="links_to_distributor_invoices[' + m_id + '][' + invoice_number + '][' + row_max_index + '][link_to_distributor_invoice]" value="" />&nbsp;&nbsp;' +
				'<a href="javascript: void(0);" onclick="javascript: add_distributor_link_row(\'' + row_max_index + '\', \'' + m_id + '\', \'' + invoice_number + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
				'&nbsp;&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_distributor_link_row(\'' + row_max_index + '\', \'' + m_id + '\', \'' + invoice_number + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +


			'</td>' +
		'</tr>'
	);
}

function remove_distributor_link_row(index, m_id, invoice_number) {
	$('#distributor_link_row_' + m_id +'_' + invoice_number + '_' + index).remove();
}
