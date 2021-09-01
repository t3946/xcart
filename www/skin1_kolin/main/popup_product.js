// $Id: popup_product.js,v 1.1.2.2 2006/09/19 07:03:49 twice Exp $
function popup_product (field_productid, field_product) {
	return window.open("popup_product.php?field_productid="+field_productid+"&field_product="+field_product, "selectproduct", "width=600,height=550,toolbar=no,status=no,scrollbars=yes,resizable=yes,menubar=no,location=no,direction=no");
}
