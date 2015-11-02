{if $smarty.get.sent ne "Y"}

{*
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
*}
<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script>
//<![CDATA[
{literal}
        tinymce.init({selector:'textarea'});
{/literal}
//]]>
</script>

{assign var="order_details_name" value="Compose message (Order # `$order.order_prefix``$order.orderid`)"}
{include file="page_title.tpl" title=$order_details_name}

{capture name=dialog}
  <form action="compose_message.php" method="post" name="compose_message_form">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mode" value="send_message" />
  <input type="hidden" name="department" value="{$department}" />
  <input type="hidden" name="template_id" value="{$template_id}" />
  <input type="hidden" name="manufacturerid" value="{$manufacturerid}" />
  <input type="hidden" name="mid_templateid" value="{$mid_templateid}" />

  <div class="ProductTitle" align="center">
	Department: {$department_name} 
	{if $department eq "distributor" && $order_manufacturers[$manufacturerid].manufacturer ne ""}
		({$order_manufacturers[$manufacturerid].manufacturer})
	{/if}
  </div>

  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="from" value="{$from}" style="width: 99%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="to" value="{$to}" style="width: 99%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="subject" value="{$subject}" style="width: 99%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />

{*
  {include file="main/textarea_def.tpl" name="body" cols="60" rows="30" class="InputWidth" data=$body width="99%" btn_rows="30"}
*}

<textarea name="body" cols="60" rows="30">{$body|escape:"html"}</textarea>

{*
  <INPUT type="button" value="Send" onclick="disableEditor('body','body'); document.compose_message_form.submit();">
*}
  <INPUT type="button" value="Send" onclick="document.compose_message_form.submit();">
  </form>

{/capture}
{include file="dialog.tpl" title="Compose message" content=$smarty.capture.dialog extra='width="100%"'}

{*
<script type="text/javascript">
//<![CDATA[
{literal}

function turn_Editor(){
//	disableEditor('body','body');
	enableEditor('body','body');
}

window.onload = turn_Editor();
{/literal}
//]]>
</script>
*}

{else}

<script type="text/javascript">
//<![CDATA[
window.onload = setTimeout('window.close()', 2000);
//]]>
</script>

{/if}
