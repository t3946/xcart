// $Id: check_froogle_upc.js,v 1.0 2010/05/28 15:18:01 kate Exp $

function check_froogle_upc_field(upc) {
	var upc_error = false;

    if (upc) {
        upc.value = upc.value.replace(/[^0-9a-z]/gi, '');

        if (!upc || upc.value == "") {
            return true;
        }

        if (upc.value.length != isbn_length && upc.value.length != upc_length && upc.value.length != ean_isbn_length && upc.value.length != '8' && upc.value.length != '14') {
            upc_error = true;
            alert(txt_upc_error);
        }
    }

	return !upc_error;
}

