
function add_distributor_memo_link_row(index, m_id, memo_number) {
	
	var row_max_index = $('#row_max_index_' + m_id + '_' + memo_number).val();
	row_max_index++;
	$('#row_max_index_' + m_id + '_' + memo_number).val(row_max_index);

	$('#distributor_memo_link_row_' + m_id + '_' + memo_number + '_' + index).after(
		'<tr id="distributor_memo_link_row_' + m_id + '_' + memo_number + '_' + row_max_index + '">' +
			'<td style="padding: 2px 0px;">' +
				'Link to distributor memo&nbsp;<input type="text" size="40" name="links_to_distributor_memos[' + m_id + '][' + memo_number + '][' + row_max_index + '][link_to_distributor_memo]" value="" />&nbsp;&nbsp;' +
				'<a href="javascript: void(0);" onclick="javascript: add_distributor_memo_link_row(\'' + row_max_index + '\', \'' + m_id + '\', \'' + memo_number + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
				'&nbsp;&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_distributor_memo_link_row(\'' + row_max_index + '\', \'' + m_id + '\', \'' + memo_number + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +


			'</td>' +
		'</tr>'
	);
}

function remove_distributor_memo_link_row(index, m_id, memo_number) {
	$('#distributor_memo_link_row_' + m_id +'_' + memo_number + '_' + index).remove();
}
