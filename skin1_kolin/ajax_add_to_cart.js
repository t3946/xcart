// $Id: ajax_add_to_cart.js,v 1.0 2010/11/01 16:30:02 kate Exp $

   
$().ready(function(){
  $.ajaxSetup({
    error:function(x,e){
      if (x.status == 0) {
//        alert('You are offline!!\n Please Check Your Network.');
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
	if (source == "product"){
//		$('#add2cart_' + id).addClass('ajax_add2cart_wait_container_product');
		var waiting_html = '<span class="btn_atcart_big_wait" /></span>';
		var added_html = '<span class="btn_atcart_big_added"></span>';
		var error_html = '<span class="btn_atcart_big_error"></span>';
	} else {
//		$('#add2cart_' + id).addClass('ajax_add2cart_wait_container_list');
                var waiting_html = '<span class="btn_atcart_small_wait" /></span>';
                var added_html = '<span class="btn_atcart_small_added"></span>';
		var error_html = '<span class="btn_atcart_small_error"></span>';
	}


	$('#add2cart_' + id).html(waiting_html);
	if (source == 'product') {
		var formname = 'orderform';

		var pprice = $('#product_price').html();
		pprice = pprice.replace(/[^0-9\.]/g, '');
		pprice = parseFloat(pprice);

		var pbrand = $('#pbrand').val();
		var pname = $('#pname').val();
		var pcategory = $('#pcategory').val();
		var plist = $('#ga_page_name').val();
		
	} else {
		var formname = 'orderform_' + id + '_' + add_date;

		var pprice = $('#pprice_'+id).val();

		var pbrand = $('#pbrand_'+id).val();
		var pname = $('#pname_'+id).val();
		var pcategory = $('#pcategory_'+id).val();
		var plist = $('#ga_page_name_'+id).val();
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

/* --- */
  var pquantity = info.split("amount=");
  if (pquantity[0] == ""){
	pquantity = pquantity[1];
  } else {
	pquantity = pquantity[1];
  }
  pquantity = pquantity.split("&");
  pquantity = pquantity[0];

  if (pquantity != ""){

   ga('ec:addProduct', {
    'id': id,
    'name': pname,
    'category': pcategory,
    'brand': pbrand,
    'price': pprice,
    'quantity': pquantity
   });
   ga('ec:setAction', 'add', {list: plist});
   ga('send', 'event', 'UX', 'click', 'Add to cart');     // Send data using an event.

  }
/* --- */



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
