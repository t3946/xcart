// $Id: ajax_add_to_cart.js,v 1.0 2010/11/01 16:30:02 kate Exp $

   
$().ready(function(){
  $.ajaxSetup({
    error:function(x,e){
      if (x.status == 0) {
        alert('You are offline!!\n Please Check Your Network.');
      } else if (x.status == 404) {
        alert('Requested URL not found.');
      } else if (x.status == 500) {
        alert('Internal Server Error.');
      } else if (e == 'parsererror') {
        alert('Error.\nParsing JSON Request failed.');
      } else if (e == 'timeout') {
        alert('Request Time out.');
      } else {
        alert('Unknown Error.\n' + x.responseText);
      }
    }
  });
});

function ajax_add_to_cart(id, add_date, source) {
	var button_html = $('#add2cart_' + id).html();
	$('#add2cart_' + id).addClass('ajax_add2cart_wait_container');
	var waiting_html = '<span class="ajax_add2cart_wait" width="50px"><img src="skin1_kolin/images/spacer.gif" width="50px" height="11px" alt="" /></span>';
	var added_html = '<span height=11px><b>' + lbl_added + '</b></span>';
	var error_html = '<span height=11px><b>' + lbl_error + '</b></span>';
	$('#add2cart_' + id).html(waiting_html);
	if (source == 'product') {
		var formname = 'orderform';
	} else {
		var formname = 'orderform_' + id + '_' + add_date;
	}

	var info = $('form[name="' + formname + '"]').serialize();
	info = info + '&action=' + $('form[name="' + formname + '"]').attr('action');
	$.post('ajax_add_to_cart.php', info, 
		function (data) {
			if (data.error == 'Y') {
				$('#add2cart_' + id).html(error_html);
				if (data.redirect) {
					self.location = data.redirect;
				} else {
					self.location.reload();
				}
				
			} else {
				if (data.redirect) {
					self.location = data.redirect;
				}
			
				if (data.display) {
					$('#ajax_minicart').html(data.display);
				}
	
				$('#add2cart_' + id).html(added_html);

				setTimeout(
					function () {
						$('#add2cart_' + id).removeClass('ajax_add2cart_wait_container');
						$('#add2cart_' + id).html(button_html);
						$('#add2cart_' + id + ' .Button2On').addClass("Button2Off");
						$('#add2cart_' + id + ' .Button2On').removeClass("Button2On");
					}, 3000);
			}
		}, 'json');
}
