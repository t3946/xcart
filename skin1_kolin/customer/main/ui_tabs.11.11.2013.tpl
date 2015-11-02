{include file="check_email_script.tpl"}
{include file="check_zipcode_js.tpl"}
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
  $('#{$prefix}container').tabs();
{rdelim});

{literal}
function check_question_email_form() {

	if ($("#email").val()!="" && $("#phone").val()!="" && $("#question").val()!=""){
		send_question_email_form();

	} else {
		alert("Fill all fields please");
		return false;
	}
}

function send_question_email_form(){

	cidev_xmlHttp=cidev_createHttpRequestObject();
	if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

		var cidev_parameters = 'cidev_mode=send&email=' + $("#email").val() + '&phone=' + $("#phone").val() + '&question=' + $("#question").val() + '&productid=' + $('#question_productid').val();

		cidev_xmlHttp.onreadystatechange=function(){
			if(cidev_xmlHttp.readyState==4){
				if(cidev_xmlHttp.status==200){
                	        	cidev_id$("product_question_after").innerHTML=cidev_xmlHttp.responseText;
					$("#product_question_pre").hide();
                        	}else{
                        		cidev_Error('no_server', 'Y');
	                        }
			}
		};

                cidev_xmlHttp.open('POST','product_question.php',true);
                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                cidev_xmlHttp.setRequestHeader('Connection','close');
                cidev_xmlHttp.send(cidev_parameters);
	}
	else {
		setTimeout('send_question_email_form()', 1000);
	}
}


{/literal}

//]]>
</script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
  $(document).ready(function() {  
        $('#email').focusout(function() {

                if ($('#email').val() != ""){
                        checkEmailAddress(document.product_question_email_form.email, 'Y');
                }
        });
  });
{/literal}
//]]>
</script>


<div id="{$prefix}container">

  <ul>
  {foreach from=$tabs item=tab key=ind}
{*    {inc value=$ind assign="ti"} *}
    <li><a href="{if $tab.url}{$tab.url|amp}{else}#{$prefix}{$tab.anchor|default:$ti}{/if}">{$tab.title}</a></li> 
  {/foreach}
  </ul>

  {foreach from=$tabs item=tab key=ind}
    {if $tab.tpl}
{*      {inc value=$ind assign="ti"} *}
      <div id="{$prefix}{$tab.anchor|default:$ti}">
	{if $tab.tpl eq "_product_question_tpl_"}
{* --------------------------------------------------*}
<div id="product_question_pre">
{$lng.lbl_product_question_pre_instructions}
<br />
<br />
<form name="product_question_email_form" action="" method="POST" >
<table cellpadding="1" cellspacing="3" width="100%">

 <tr>
  <td align="right" class="cidev_padding_top">{$lng.lbl_email}:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
	<input type="text" id="email" name="email" size="32" maxlength="128" value="" />
	<input type="hidden" id="question_productid" name="question_productid" size="32" maxlength="128" value="{$productid}" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">{$lng.lbl_phone}:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
	<input type="text" id="phone" name="phone" size="32" maxlength="32" value="" onkeyup="cidev_check_field_phone('phone')" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">Question:</td>
  <td><font class="Star">*</font></td>
  <td>
	<textarea style="width: 98%" name="question" id="question" cols="60" rows="10"></textarea>
  </td>
 </tr>

 <tr>
  <td colspan="3" align="center">
	<input type="button" name="Submit question" value="Submit question" onclick="javasript: check_question_email_form();" />
  </td>
 </tr>

</table>
</form>
</div>

<div id="product_question_after"></div>

{* --------------------------------------------------*}
	{else}
		{$tab.tpl}
	{/if}
      </div>
    {/if}
  {/foreach}

</div>
