{* $Id: register.tpl,v 1.51.2.6 2006/12/07 08:28:04 svowl Exp $ *}


<script src="{$SkinDir}/US_City_List/jquery-1.4.js" type="text/javascript"></script>
<script src="{$SkinDir}/US_City_List/jquery.autocomplete.js" type="text/javascript"></script>
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>


{if $av_error eq 1}

{include file="modules/UPS_OnLine_Tools/register.tpl"}

{else}

{if $js_enabled eq 'Y'}
{include file="check_email_script.tpl"}
{include file="check_zipcode_js.tpl"}
{include file="generate_required_fields_js.tpl"} 
{include file="check_required_fields_js.tpl"}
{if $config.General.use_js_states eq 'Y'}
{include file="change_states_js.tpl"}
{/if}
{/if}

{if $action ne "cart"}

{if $newbie eq "Y"}
{if $login ne ""}
{assign var="title" value=$lng.lbl_modify_profile}
{else}
{assign var="title" value=$lng.lbl_create_profile}
{/if}
{else}
{if $main eq "user_add"}
{assign var="title" value=$lng.lbl_create_customer_profile}
{else} 
{assign var="title" value=$lng.lbl_modify_customer_profile}
{/if}
{/if}

{include file="page_title.tpl" title=$title}

<!-- IN THIS SECTION -->

{if $newbie ne "Y"}
{include file="dialog_tools.tpl"}
{/if}

<!-- IN THIS SECTION -->

{if $usertype ne "C"}
<br />
{if $main eq "user_add"}
{$lng.txt_create_customer_profile}
{else}
{$lng.txt_modify_customer_profile}
{/if}
<br /><br />
{/if}

{/if}

{capture name=dialog}

{if $newbie eq "Y"}
{if $registered eq ""}
{if $smarty.get.mode eq "update"}
<font class="Text">
{$lng.txt_modify_profile_msg}
</font>
{if !is_flc}
<br /><br />
{/if}
{/if}
{/if}
{/if}

{if $newbie ne "Y" and $main ne "user_add" and ($usertype eq "P" and $active_modules.Simple_Mode eq "Y" or $usertype eq "A")}
<div align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_return_to_search_results href="users.php?mode=search"}</div>
{/if}

{assign var="reg_error" value=$top_message.reg_error}
{assign var="error" value=$top_message.error}
{assign var="emailerror" value=$top_message.emailerror}

{if $registered eq ""}
{if $reg_error}
<font class="Star">
{if $reg_error eq "F" }
{$lng.txt_registration_error}
{elseif $reg_error eq "E" }
{$lng.txt_email_already_exists}
{elseif $reg_error eq "U" }
{$lng.txt_user_already_exists}
{/if}
</font>
<br />
{/if}

{if $error ne ""}
<font class="Star"></strong>
{if $error eq "b_statecode"}
{$lng.err_billing_state}
{elseif $error eq "s_statecode"}
{$lng.err_shipping_state}
{elseif $error eq "b_county"}
{$lng.err_billing_county}
{elseif $error eq "s_county"}
{$lng.err_shipping_county}
{elseif $error eq "email"}
{$lng.txt_email_invalid}
{elseif $error eq "username"}
{$lng.err_username_invalid}
{else}
{$error}
{/if}
</strong></font>
<br />
{/if}

<script type="text/javascript" language="JavaScript 1.2">
<!--

var show_spam = false;
function check_spam() {$ldelim}
    if ( 0 ||
        {if $default_fields.s_country.avail eq "Y"} document.registerform.s_country.value == "AF" ||{/if} 
        {if $default_fields.b_country.avail eq "Y"} document.registerform.b_country.value == "AF" ||{/if}
{*        {if $default_fields.b_firstname.avail eq "Y" and  $default_fields.b_lastname.avail eq "Y"}document.registerform.b_firstname.value ==  document.registerform.b_lastname.value ||{/if}*}
        {if $default_fields.s_firstname.avail eq "Y" and  $default_fields.s_lastname.avail eq "Y"}document.registerform.s_firstname.value ==  document.registerform.s_lastname.value ||{/if}
        {if $default_fields.firstname.avail eq "Y" and  $default_fields.lastname.avail eq "Y"}document.registerform.firstname.value ==  document.registerform.lastname.value ||{/if} 
        0
        )
        return false;
    return true; 
{$rdelim}

var is_run = false;
function check_registerform_fields() {ldelim}
	if(is_run)
		return false;
	is_run = true;
	if (check_zip_code(){if $default_fields.email.avail eq 'Y'} && checkEmailAddress(document.registerform.email, '{$default_fields.email.required}'){/if} {if $config.General.check_cc_number eq "Y" AND $config.General.disable_cc ne "Y"}&& checkCCNumber(document.registerform.card_number,document.registerform.card_type) {/if}&& checkRequired(requiredFields)) {ldelim}
		document.registerform.submit();
		return true;
	{rdelim}
	is_run = false;
	return false;
{rdelim}
-->
</script>

<form action="{$register_script_name}?{$smarty.server.QUERY_STRING|amp}" method="post" name="registerform" id="autofillform" onsubmit="javascript: check_registerform_fields(); return false;">
{if $config.Security.use_https_login eq "Y"}
<input type="hidden" name="{$XCARTSESSNAME}" value="{$XCARTSESSID}" />
{/if}
<table cellspacing="1" cellpadding="2" width="100%">
<tbody>
{include file="main/register_shipping_address.tpl" userinfo=$userinfo}

{include file="main/register_billing_address.tpl" userinfo=$userinfo}

{include file="main/register_contact_info.tpl" userinfo=$userinfo}

{include file="main/register_additional_info.tpl" section='A'}

{if $config.General.disable_cc ne "Y"}
{include file="main/register_ccinfo.tpl"}
{/if}

{*include file="main/register_account.tpl" userinfo=$userinfo*}
{if !((($active_modules.Simple_Mode ne "" and $usertype eq "P") or $usertype eq "A") and ($userinfo.uname && $userinfo.uname ne $login or !$userinfo.uname and $userinfo.login ne $login))}
	<tr style="display: none;">
		<td>
			<input type="hidden" name="uname" value="{$userinfo.login|default:$userinfo.uname}" />
			<input type="hidden" name="passwd1" value="{$userinfo.passwd1}" />
			<input type="hidden" name="passwd2" value="{$userinfo.passwd2}" />
		</td>
	</tr>
{/if}

{if $active_modules.Special_Offers and $usertype ne "C"}
{include file="modules/Special_Offers/customer/register_bonuses.tpl"}
{/if}

{if $active_modules.News_Management and $newslists}
{include file="modules/News_Management/register_newslists.tpl" userinfo=$userinfo}
{/if}

{if $active_modules.Mailchimp_Subscription and $mc_newslists}

{include file="modules/Mailchimp_Subscription/customer/register_newslists.tpl" userinfo=$userinfo}
{/if}

{if $active_modules.Image_Verification and $show_antibot.on_registration eq 'Y' and $display_antibot}
{assign var="antibot_err" value=$reg_antibot_err}
{include file="modules/Image_Verification/spambot_arrest.tpl" mode="advanced" id=$antibot_sections.on_registration reg_id="img_reg"}
{/if}

<tr>
<td colspan="3">
{if $newbie eq "Y"}
{$lng.txt_terms_and_conditions_newbie_note}
{$lng.txt_newbie_registration_bottom}
{else}
{$lng.txt_user_registration_bottom}
{/if}
{if $is_areas.S eq 'Y' or $is_areas.B eq 'Y'}
{if $active_modules.UPS_OnLine_Tools and $av_enabled eq "Y"}
<table cellpadding="1" cellspacing="1" width="100%">
<tbody>
<tr>
<td colspan="3">
{include file="modules/UPS_OnLine_Tools/ups_av_notice.tpl" postoffice=1}
{include file="modules/UPS_OnLine_Tools/ups_av_notice.tpl"}
</td>
</tr>
</tbody>
</table>
{/if}
{/if}
</td>
</tr>

<tr>
<td colspan="3" align='center'>

{if $smarty.get.mode eq "update"}
<input type="hidden" name="mode" value="update" />
{/if}

<input type="hidden" name="anonymous" value="{$anonymous}" />

<br>

{if $js_enabled and $usertype eq "C"}
{*<a onClick="javascript: check_registerform_fields();" class="VertMenuItems"><font size=3><b>{$lng.lbl_submit}</b></font></a>*}
{include file="buttons/submit.tpl" type="input" style="button" href="javascript: return check_registerform_fields();" b="1"}
{else}
<input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} " />
{/if}

</td>
</tr>

</tbody>
</table>
<input type="hidden" name="usertype" value="{if $smarty.get.usertype ne ""}{$smarty.get.usertype|escape:"html"}{else}{$usertype}{/if}" />
</form>

{include file="billing_autofill.tpl"}
{else}
{if $smarty.post.mode eq "update" or $smarty.get.mode eq "update"}
{$lng.txt_profile_modified}
{elseif $smarty.get.usertype eq "B"  or $usertype eq "B"}
{$lng.txt_partner_created}
{else}
{$lng.txt_profile_created}
{/if}
{/if}
{/capture}
{include file="dialog.tpl" title='' content=$smarty.capture.dialog extra='width="100%"'}
{/if}
