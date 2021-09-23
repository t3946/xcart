
function add_row(index) {
	
	row_max_index = row_max_index + 1;
	
	$('#dep_' + index).after(
                '<tr id="dep_' + row_max_index + '">' +

                        '<td>' +
'<input type="text" size="20" name="request_availability_options[' + row_max_index + '][name]" value="" style="width: 96%;" />' +
                        '</td>' +

                        '<td>' +

'<script type="text/javascript" language="JavaScript 1.2">' +
'  $(function() {' +
'    $("#date_mm_dd_yyyy_'+ row_max_index +'").datepicker();' +
'  });' +
'</script>' +

'<input type="text" size="25" name="request_availability_options[' + row_max_index + '][date_mm_dd_yyyy]" value="" style="width: 96%;" id="date_mm_dd_yyyy_'+ row_max_index +'" />' +
                        '</td>' +

                        '<td>' +
'<input type="checkbox" name="request_availability_options[' + row_max_index + '][active]" value="Y" checked="checked" style="padding: 0px; margin: -2px 0 0 0;" id="date_mm_dd_yyyy_'+ row_max_index +'" />' +
                        '</td>' +

                        '<td>' +
'<a href="javascript: void(0);" onclick="javascript: add_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/plus.gif" alt="' + lbl_add + '" /></a>' +
                        '</td>' +

                        '<td>' +
'<a href="javascript: void(0);" onclick="javascript: remove_row(\'' + row_max_index + '\');"><img src="' + ImagesDir + '/minus.gif" alt="' + lbl_remove_row + '" /></a>' +
                        '</td>' +

                '</tr>'
	);
}

function remove_row(index) {
	$('#dep_' + index).remove();
}
