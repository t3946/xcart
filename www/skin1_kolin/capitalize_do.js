// $Id: capitalize_do.js,v 1.0 2011/07/18 9:57:01 kate Exp $

function cap_first() {
	return arguments[0].toUpperCase();
}

function capitalize(id) {
	var text = $('#' + id).val();
    text = text.replace(/^\s+/, '');
    text = text.replace(/\s+$/, '');
    text = text.replace(/\s{2,}/g, ' ');
	text = text.replace(/\b[a-z]/g, cap_first);
	for (i = 0; i < reps.length; i++) {
		pattern = new RegExp(reps[i][0], 'g');
		text = text.replace(pattern, reps[i][1]);
	}
	$('#' + id).val(text);
}
