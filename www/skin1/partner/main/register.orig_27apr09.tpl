{* $Id: register.tpl,v 1.29.2.6 2004/11/09 14:34:53 max Exp $ *}
{if $js_enabled eq 'Y'}
{include file="check_email_script.tpl"}
{include file="check_zipcode_js.tpl"}
{include file="generate_required_fields_js.tpl"}
{include file="check_required_fields_js.tpl"}
{if $config.General.use_js_states eq 'Y'}
{include file="change_states_js.tpl"}
{/if}
{/if}

{if $newbie eq "Y"}
{if $login ne ""}
{assign var="title" value=$lng.lbl_modify_profile}
{else}
{assign var="title" value=$lng.lbl_create_profile}
{/if}
{else}
{if $main eq "user_add"}
{assign var="title" value=$lng.lbl_create_partner_profile}
{else}
{assign var="title" value=$lng.lbl_modify_partner_profile}
{/if}
{/if}

{include file="page_title.tpl" title=$title}

<!-- IN THIS SECTION -->

{if $newbie ne "Y"}
{include file="dialog_tools.tpl"}
{/if}

<!-- IN THIS SECTION -->

<FONT class="Text">

{if $usertype ne "B"}
<BR>
{if $main eq "user_add"}
{$lng.txt_create_partner_profile}
{else}
{$lng.txt_modify_partner_profile}
{/if}
{else}
{$lng.txt_create_profile_msg_partner}
{/if}
<BR><BR>

{$lng.txt_fields_are_mandatory}

</FONT>

<BR><BR>

{capture name=dialog}

{if $newbie ne "Y" and $main ne "user_add" and ($usertype eq "P" and $active_modules.Simple_Mode eq "Y" or $usertype eq "A")}
<DIV align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_return_to_search_results href="users.php?mode=search"}</DIV>
{/if}

{assign var="reg_error" value=$top_message.reg_error}
{assign var="error" value=$top_message.error}
{assign var="emailerror" value=$top_message.emailerror}

{if $registered eq ""}
{if $reg_error}
<FONT class="Star">
{if $reg_error eq "F" }
{$lng.txt_registration_error}
{elseif $reg_error eq "E" }
{$lng.txt_email_already_exists}
{elseif $reg_error eq "U" }
{$lng.txt_user_already_exists}
{/if}
</FONT>
<BR>
{/if}

{if $error ne ""}
<FONT class="Star">
{if $error eq "b_statecode"}
{$lng.err_billing_state}
{elseif $error eq "s_statecode"}
{$lng.err_shipping_state}
{elseif $error eq "email"}
{$lng.txt_email_invalid}
{else}
{$error}
{/if}
</FONT>
<BR>
{/if}

<SCRIPT type="text/javascript" language="JavaScript 1.2">
var is_run = false;
function check_registerform_fields() {ldelim}
	if(is_run)
		return false;
	is_run = true;
	if (check_zip_code(){if $default_fields.email.required eq 'Y'} && checkEmailAddress(document.registerform.email){/if} && checkRequired('')) {ldelim}
		document.registerform.submit();
		return true;
	{rdelim}
	is_run = false;
	return false;
{rdelim}
</SCRIPT>
 
<TABLE border="0" cellspacing="1" cellpadding="2" width="100%">

<FORM action="{$register_script_name}?{$smarty.server.QUERY_STRING}" method="POST" name="registerform" onsubmit="check_registerform_fields(); return false;">

<INPUT type="hidden" name="parent" value="{$parent|default:$userinfo.parent}">

{if $config.General.use_https_login eq "Y"}
<INPUT type="hidden" name="{$XCARTSESSNAME}" value="{$XCARTSESSID}">
{/if}

{include file="main/register_personal_info.tpl" userinfo=$userinfo}

{include file="main/register_billing_address.tpl" userinfo=$userinfo}

{include file="main/register_shipping_address.tpl" userinfo=$userinfo}

{include file="main/register_contact_info.tpl" userinfo=$userinfo}

{include file="main/register_additional_info.tpl" section='A'}

{include file="partner/main/register_plan.tpl" userinfo=$userinfo}

{include file="main/register_account.tpl" userinfo=$userinfo}


{if $active_modules.News_Management and $newslists}
{include file="modules/News_Management/register_newslists.tpl" userinfo=$userinfo}
{/if}


<TR>
<TD colspan="3" align="center">
<BR><BR>
{if $newbie eq "Y"}
{$lng.txt_you_are_agree} <A href="help.php?section=conditions" target="_blank"><FONT class="Text">{$lng.lbl_terms_n_conditions}</FONT></A>.
{/if}
</TD>
</TR>

<TR>
<TD colspan="2">&nbsp;</TD>
<TD>

<FONT class="FormButton">
{if $smarty.get.mode eq "update"}
<INPUT type="hidden" name="mode" value="update">
{/if}

{if $js_enabled and $usertype eq "B"}
{include file="buttons/submit.tpl" type="input" style="button" href="javascript: return check_registerform_fields();"}
{else}
<INPUT type="submit" value=" {$lng.lbl_save} ">
{/if}

</TD>
</TR>

<INPUT type="hidden" name="usertype" value="{if $smarty.get.usertype ne ""}{$smarty.get.usertype|escape:"html"}{else}{$usertype}{/if}">

</FORM>

</TABLE>

<BR><BR>

{if $newbie eq "Y"}
{$lng.txt_newbie_registration_bottom}
<BR><A href="help.php?section=conditions"><FONT class="Text"><B>{$lng.lbl_terms_n_conditions}</B>&nbsp;</FONT>{include file="buttons/go.tpl"}</A>
{else}
{$lng.txt_user_registration_bottom}
{/if}

<BR>

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
{include file="dialog.tpl" title=$lng.lbl_profile_details content=$smarty.capture.dialog extra="width=100%"}

{if $userinfo.status eq "Q" && $usertype ne "B"}

<BR>

{capture name=dialog}

<FORM action="{$register_script_name}?{$smarty.server.QUERY_STRING}" method="POST" name="decisionform">

<INPUT type="hidden" name="mode" value="">

{$lng.txt_partner_profile_is_not_approved}
<BR><BR>

<TEXTAREA name="reason" cols="40" rows="5"></TEXTAREA>

<BR>

<INPUT type="button" value="{$lng.lbl_approved}" onclick="javascript: document.decisionform.mode.value='approved'; document.decisionform.submit();">
&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT type="button" value="{$lng.lbl_declined}" onclick="javascript: document.decisionform.mode.value='declined'; document.decisionform.submit();">

</FORM>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_approve_or_decline_partner_profile content=$smarty.capture.dialog extra="width=100%"}

{/if}

