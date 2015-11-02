{* $Id: check_froogle_upc_js.tpl,v 1.0 2010/05/28 15:17:13 kate Exp $ *}

<script type="text/javascript">
<!--

var txt_upc_error = '{$lng.err_froogle_wrong_upc}';

var upc_length = '{$UPC_LENGTH}';
var isbn_length = '{$ISBN_LENGTH}';
var ean_isbn_length = '{$EAN_ISBN_LENGTH}';

-->
</script>
{include file="main/include_js.tpl" src="check_froogle_upc.js"}
