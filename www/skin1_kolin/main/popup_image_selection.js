// $Id: popup_image_selection.js,v 1.3 2006/01/04 08:51:31 max Exp $

/*
	Display popup window
*/
function popup_image_selection (type, id, imgid) {
	if (!window.not_image) {
		not_image = 'N';
	}
	if (!imgid) {
		imgid = 'N';
	}
	
	geid_str = '';
	product_field_str = '';
	thumb_field_str = '';
	
	if (type == 'P' || type == 'T' && document.modifythumbform) {
		geid_str = '&geid=' + document.modifythumbform.geid.value;
		product_field_str = '&product_field=' + $('#field_product').val();
		thumb_field_str = '&thumb_field=' + $('#field_thumb').val();
	}
	return window.open("image_selection.php?type="+type+"&id="+id+"&imgid="+imgid+"&notimage="+not_image+geid_str+product_field_str+thumb_field_str,"selectimage","width=500,height=350,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no");
}

/*
	Reset new selected image
*/
function popup_image_selection_reset (type, id, imgid) {
	if (document.getElementById(imgid)) {
		var ts = new Date();
		document.getElementById(imgid).src = xcart_web_dir+"/image.php?type="+type+"&id="+id+"&ts="+ts.getTime() + "&not_image=" + not_image;
		if (document.getElementById(imgid+'_text')) {
			document.getElementById(imgid+'_text').style.display = 'none';
			for (var cnt = 1; true; cnt++) {
				if (!document.getElementById(imgid+'_text'+cnt))
					break;
				window.opener.document.getElementById(imgid+'_text'+cnt).style.display = 'none';
			}
		}

		if (document.getElementById('skip_image_'+type))
			document.getElementById('skip_image_'+type).value = 'Y';
		else if (document.getElementById('skip_image_'+type+"_"+id))
			document.getElementById('skip_image_'+type+"_"+id).value = 'Y';

		if (document.getElementById(imgid+'_reset'))
			document.getElementById(imgid+'_reset').style.display = 'none';
	}
}

