// $Id: product_page_options.js,v 1.0 2011/01/18 15:09:01 kate Exp $

function add_replacement_row(index) {
	
	row_max_index = row_max_index + 1;
	
	$('#rep_' + index).after(
		'<tr id="rep_' + row_max_index + '">' +
			'<td>&nbsp;</td>' +
			'<td colspan="2">' +
				lbl_replace + '&nbsp;<input type="text" size="20" name="rep[' + row_max_index + '][what]" value="" />&nbsp;' +
				lbl_by + '&nbsp;<input type="text" size="20" name="rep[' + row_max_index + '][by]" value="" />&nbsp;' +
				'<a href="javascript: void(0);" onclick="javascript: add_replacement_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
				'&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_replacement_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +
			'</td>' +
		'</tr>'
	);
}

function remove_replacement_row(index) {
	$('#rep_' + index).remove();
}
