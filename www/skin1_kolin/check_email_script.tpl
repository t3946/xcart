{* $Id: check_email_script.tpl,v 1.19 2005/11/17 06:55:36 max Exp $ *}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var txt_email_invalid = "{$lng.txt_email_invalid|replace:"\n":" "|replace:"\r":" "|replace:'"':'\"'}";
-->
</script>

{if $main eq "fast_lane_checkout" || $usertype ne "C"}
{* igor_async *}
{include file="main/include_js.tpl" src="check_email_script.js"}
{/if}
