// $Id: wizard_step_w_list.js,v 1.2 2006/02/15 13:04:55 mclap Exp $
function box_visible(index, visible) {
	var def, mark, item, box, status, row;

	def = items_def[index];

	mark = document.getElementById(def[1]);
	item = document.getElementById(def[2]);
	box  = document.getElementById(def[3]);
	status_box  = document.getElementById(def[4]+'_box');

	if (visible) {
		box.style.display = '';
		item.style.fontWeight = 'bold';
		status_box.style.display = 'none';
	}
	else {
		box.style.display = 'none';
		item.style.fontWeight = 'normal';

		if (mark.checked) {
			status_box.style.display = '';
		}
		else {
			status_box.style.display = 'none';
		}
	}
}

function select_item(id, index, event) {
	var def, mark, i;
	var select_current = false;

	
	def = items_def[index];
	mark = document.getElementById(def[1]);

	document.forms.wizardform.last_item_type.value=index;
	select_current = true;

	for (i in items_def) {
		if (i == index) {
			box_visible(index, select_current);
			continue;
		}

		box_visible(i, false);
	}

	change_selected_row(active_row_id, 'item_row_'+index, select_current);

	return true;
}

function select_row(row,select) {
	var classname = '';

	if (row == undefined) return;

	if (row.id == active_row_id)
		classname = 'SubHeaderGreyLine';
	else if (select) {
		classname = 'TableSubHead';
	}

	row.className = classname;
}

function change_selected_row(id_old, id_new, final_select) {
	var row;

	if (final_select && id_new != '') {
		active_row_id = id_new;
	}
	else
		active_row_id = '';

	if (id_old != '') {
		row  = document.getElementById(id_old);
		select_row(row, false);
	}

	if (id_new != '') {
		row  = document.getElementById(id_new);
		select_row(row, true);
	}
}
