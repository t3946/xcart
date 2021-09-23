{include file="page_title.tpl" title="Google Content API: test"}
<br />

<form action="google_content_api_test.php" method="post" name="t_form" >
<input type="hidden" name="mode" value="" />

<input type="text" name="productid" value='' size="7" />

<INPUT type="button" value="Insert product" onclick="document.t_form.mode.value='insert'; document.t_form.submit();">

<INPUT type="button" value="Delete product" onclick="document.t_form.mode.value='delete'; document.t_form.submit();">

</form>
