/* $Id: billing_autofill.js,v 1.0 2011/01/04 17:27:14 kate Exp $ */
var s2d;

function copy_changed_state() {
	if ($('#ship2diff').attr('checked') == true && s2d == 'changed') {
		if ($('#s_state').is('select')) {
			val = $('#s_state').children('option:selected').val();
			$('#b_state option[value="'+val+'"]').attr('selected', 'selected');
		}
		if ($('#s_state').is('input')) {
			$('#b_state').val($('#s_state').val());
		}
	}
	s2d = 'no';
}

$('#ship2diff').change( function () {
	s2d = 'changed';
	if ($('#ship2diff').attr('checked') == true) {
		$('#autofillform :input').filter('*[id^="s_"], #additional_values_2').each(function (index) {
			id = $(this).attr('id');
			id = id.substr(2,id.length-2);
			if ($(this).is('input[type="text"][id^="s_"]')) {
				$('#b_'+id).val($('#s_'+id).val());
			} 
			if ($(this).is('select')){
				val = $(this).children('option:selected').val();
				$('#b_'+id+' option[value="'+val+'"]').attr('selected', 'selected');
			}
			if ($(this).is('#additional_values_2')) {
				$('#additional_values_1').val($('#additional_values_2').val());
			}
		});

		onSelectChange_b();
	}
});

