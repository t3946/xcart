{* $Id: popup_product_js.tpl,v 1.3.2.1 2004/11/29 14:38:50 max Exp $ *}
{literal}
<SCRIPT type="text/javascript" language="JavaScript 1.2">
function popup_product (field_productid, field_product, query) {
	window.open ("popup_product.php?field_productid="+field_productid+"&field_product="+field_product+"&query="+query, "selectproduct", "width=600,height=550,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no");
}
</SCRIPT>
{/literal}
