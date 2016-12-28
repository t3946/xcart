{* $Id: contactus.tpl,v 1.41.2.4 2006/12/25 11:08:01 max Exp $ *}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var requiredFields = [];
{counter start="-1" print=false name="requiredFields"}
{foreach from=$all_fields item=v key=k}
    {if $v.required eq 'Y' && $v.avail eq 'Y' && ($v.ftype eq 'default' || $v.type eq 'T')}
        requiredFields[{counter name="requiredFields"}] = ["{$v.field}","{$v.title|strip|replace:'"':'\"'}",false];
    {/if}
{/foreach}
requiredFields[{counter name="requiredFields"}] = ["subject","{$lng.lbl_subject|strip|replace:'"':'\"'}",false];
requiredFields[{counter name="requiredFields"}] = ["message_body","{$lng.lbl_message|strip|replace:'"':'\"'}",false];
-->
</script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

   function cidev_check_verified_image_for_field(field_id, error_value_for_field){

                if ($('#'+field_id).val() != error_value_for_field){
                        if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
                                document.getElementById(field_id+"_verified").style.display = '';                      
                                document.getElementById(field_id+"_error").style.display = 'none';     
                        }
                }
                else {
                        if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
                                document.getElementById(field_id+"_verified").style.display = 'none';                      
                                document.getElementById(field_id+"_error").style.display = '';  
                        }
                }
   }

   function cidev_check_verified_image_for_not_require_field(field_id){

                if ($('#'+field_id).val() != ""){
                        if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
                                document.getElementById(field_id+"_verified").style.display = '';                      
                                document.getElementById(field_id+"_error").style.display = 'none';     
                        }
                }
                else {
                        if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
                                document.getElementById(field_id+"_verified").style.display = 'none';                      
                                document.getElementById(field_id+"_error").style.display = 'none';  
                        }
                }
   }


  $(document).ready(function() {  

        $('#email').focusout(function() {

                if ($('#email').val() != ""){
                        checkEmailAddress(document.registerform.email, 'Y')
                }
                else {
                        document.getElementById("email_verified").style.display = 'none';                      
                        document.getElementById("email_error").style.display = '';  
                        document.getElementById("email_error_text").style.display = '';  
                }
        });

        $('#company').focusout(function() {
                cidev_check_verified_image_for_not_require_field('company');
        });

        $('#b_address').focusout(function() {
                cidev_check_verified_image_for_not_require_field('b_address');
        });

        $('#b_zipcode').focusout(function() {
                cidev_check_verified_image_for_not_require_field('b_zipcode');
        });

        $('#phone').focusout(function() {
                cidev_check_verified_image_for_not_require_field('phone');
        });

        $('#department').focusout(function() {
		cidev_check_verified_image_for_field('department', 'not_selected');
        });

        $('#department').change(function() {
                cidev_check_verified_image_for_field('department', 'not_selected');
        });

        $('#additional_values_2').focusout(function() {
                cidev_check_verified_image_for_field('additional_values_2', '');
        });

        $('#subject').focusout(function() {
                cidev_check_verified_image_for_field('subject', '');
        });

        $('#message_body').focusout(function() {
                cidev_check_verified_image_for_field('message_body', '');
        });
  });
{/literal}
//]]>
</script>

{include file="check_required_fields_js.tpl"}
{include file="check_email_script.tpl"}
{include file="check_zipcode_js.tpl"}

{if $smarty.get.mode eq "update"}
{*$lng.txt_contact_us_header*}
{/if}
<p />
{capture name=dialog}
{if $smarty.get.mode eq "update"}
{$lng.txt_contact_us_header}<br><br>
{if $fillerror ne ''}
<font class="Star">{$lng.txt_registration_error}</font><br />
{/if}
{if $antibot_err ne ''}
<font class="Star">{$lng.msg_err_antibot}</font><br />
{/if}
<form action="help.php?section=contactus&amp;mode=update&amp;action=contactus" method="post" name="registerform">
<table width="100%" cellspacing="0" cellpadding="2" border="0">

{foreach from=$all_fields item=field}
    {if $field.ftype eq 'default' 
        && $field.avail eq 'Y' 
        &&  ($field.field eq 'b_county' && $config.General.use_counties eq "Y" 
            || $field.field eq 'department' && $departments
            || $field.field ne 'b_county' && $field.field ne 'department')
    }

{if $field.field ne "email" && $field.field  ne "company" && $field.field ne "b_address" && $field.field ne "phone" && $field.field ne "department" && $field.field ne "b_zipcode"}
<script type="text/javascript">
    var verified_image_for_field = '{$field.field}';

{literal}
  $(document).ready(function() {
        $('#' + verified_image_for_field).focusout(function() {
                cidev_check_verified_image_for_field(verified_image_for_field, '');
        });
  });

	function registerFormOnSubmit() {
		document.registerform.submit();
	}

	function registerFormOnValidate() {
        if (checkEmailAddress(document.registerform.email)
            && checkRequired(requiredFields)
            && check_zip_code(document.getElementById('b_country'), document.getElementById('b_zipcode'))
        ) {
            grecaptcha.execute();

        }
	}
{/literal}
</script>
{/if}

        <tr valign="middle">
            <td class="cidev_padding_top" valign="top" align="right" width="40%">
                {if $field.field eq 'username'}
                    {$lng.lbl_username}
                {elseif $field.field eq 'title'}
                    {$lng.lbl_title}
                {elseif $field.field eq 'firstname'}
                    {$lng.lbl_first_name_on_contactus}
			<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_firstname}</div>
                {elseif $field.field eq 'lastname'}
                    {$lng.lbl_last_name}
                {elseif $field.field eq 'company'}
                    {$lng.lbl_company_on_contactus} {if $field.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>{/if}
			<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_company}</div>
                {elseif $field.field eq 'b_address'}
                    {$lng.lbl_address_on_contactus} {if $field.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>{/if}
			<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_address}</div>
                {elseif $field.field eq 'b_address_2'}
                    {$lng.lbl_address_2}
                {elseif $field.field eq 'b_city'}
                    {$lng.lbl_city}
                {elseif $field.field eq 'b_county'}
                    {$lng.lbl_county}
                {elseif $field.field eq 'b_country'}
                    {$lng.lbl_country}
                {elseif $field.field eq 'b_state'}
                    {$lng.lbl_state}
                {elseif $field.field eq 'b_zipcode'}
			Your zip/postal code {if $field.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>{/if}
                {elseif $field.field eq 'phone'}
                    {$lng.lbl_phone_on_contactus} {if $field.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>{/if}
			<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_phone}</div>
                {elseif $field.field eq 'email'}
                    {$lng.lbl_email_on_contactus}
			<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_email}</div>
                {elseif $field.field eq 'fax'}
                    {$lng.lbl_fax}
                {elseif $field.field eq 'url'}
                    {$lng.lbl_web_site}
                {elseif $field.field eq 'department'}
                    {$lng.lbl_department}
			<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_department}</div>
                {/if}

	</td>
 
            <td valign="top">{if $field.required eq 'Y'}<font class="Star">*</font>{/if}</td>
            <td nowrap="nowrap">

	      <table cellpadding="0" cellspacing="0">
		<tr>
		<td valign="top" nowrap="nowrap">

                {if $field.field eq 'username'}
                    <input type="text" id="username" name="username" size="32" maxlength="32" value="{if $userinfo.username ne ''}{$userinfo.username}{else}{$userinfo.login}{/if}" />
                {elseif $field.field eq 'title'}
                    <select id="title" name="title">
                        {include file="main/title_selector.tpl" field=$userinfo.titleid}
                    </select>
                {elseif $field.field eq 'firstname'}
                    <input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="{$userinfo.firstname}" placeholder="{$lng.lbl_contact_placeholder_firstname}" />
                    {if $fillerror ne "" and $userinfo.firstname eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'lastname'}
                    <input type="text" id="lastname" name="lastname" size="32" maxlength="32" value="{$userinfo.lastname}" />
                    {if $fillerror ne "" and $userinfo.lastname eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'company'}
                    <input type="text" id="company" name="company" size="32" value="{$userinfo.company}" placeholder="{$lng.lbl_contact_placeholder_company}" />
                    {if $fillerror ne "" and $userinfo.company eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'b_address'}
                    <input type="text" id="b_address" name="b_address" size="32" maxlength="64" value="{$userinfo.b_address}" placeholder="{$lng.lbl_contact_placeholder_b_address}" />
                    {if $fillerror ne "" and $userinfo.b_address eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'b_address_2'}
                    <input type="text" id="b_address_2" name="b_address_2" size="32" maxlength="64" value="{$userinfo.b_address_2}" />
                    {if $fillerror ne "" and $userinfo.b_address_2 eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'b_city'}
                    <input type="text" id="b_city" name="b_city" size="32" maxlength="64" value="{$userinfo.b_city}" />
                    {if $fillerror ne "" and $userinfo.b_city eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'b_county'}
                    {include file="main/counties.tpl" counties=$counties name="b_county" default=$userinfo.b_county stateid=$userinfo.b_stateid country_name="b_country"}
                {elseif $field.field eq 'b_country'}
                    <select id="b_country" name="b_country" onchange="javascript: check_zip_code();">
                        {section name=country_idx loop=$countries}
                            <option value="{$countries[country_idx].country_code}" {if $userinfo.b_country eq $countries[country_idx].country_code}selected{elseif $countries[country_idx].country_code eq $config.General.default_country and $userinfo.b_country eq ""}selected{/if}>{$countries[country_idx].country}</option>
                        {/section}
                    </select>
                {elseif $field.field eq 'b_state'}
                    {include file="main/states.tpl" states=$states name="b_state" default=$userinfo.b_state default_country=$userinfo.b_country country_name="b_country"}
                {elseif $field.field eq 'b_zipcode'}
                    <input type="text" id="b_zipcode" name="b_zipcode" size="32" maxlength="32" value="{$userinfo.b_zipcode|default:$rma_zipcode}" onchange="javascript: check_zip_code(document.getElementById('b_country'), this);" placeholder="{$lng.lbl_contact_placeholder_b_zipcode}" />
                    {if $fillerror ne "" and $userinfo.b_zipcode eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'phone'}
                    <input type="text" id="phone" name="phone" size="32" maxlength="32" value="{$userinfo.phone}" placeholder="{$lng.lbl_contact_placeholder_phone}" />
                    {if $fillerror ne "" and $userinfo.phone eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'email'}
                    <input type="text" id="email" name="email" size="32" maxlength="128" value="{$userinfo.email|default:$rma_email}" onchange="javascript: checkEmailAddress(this);" placeholder="{$lng.lbl_contact_placeholder_email}" />
                    {if $fillerror ne "" and $userinfo.email eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'fax'}
                    <input type="text" id="fax" name="fax" size="32" maxlength="128" value="{$userinfo.fax}" /></td>
                {elseif $field.field eq 'url'}
                    <input type="text" id="url" name="url" size="32" maxlength="128" value="{if $userinfo.url eq ""}http://{else}{$userinfo.url}{/if}" />
                    {if $fillerror ne "" and $userinfo.url eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'department'}
                    <select id="department" name="department">
	<option value="not_selected" {* selected="selected" *}>&nbsp;</option>
	{foreach from=$departments item="dep"}
		<option value="{$dep.depid}" {if $userinfo.department eq $dep.depid}selected="selected"{/if}>{$dep.name}</option>
	{/foreach}
                    </select>
                    {if $fillerror ne "" and $userinfo.department eq "not_selected" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {/if}

		</td>

		<td id="{$field.field}_verified" valign="top" nowrap="nowrap" style="display: none;">
		<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
		</td>
		<td id="{$field.field}_error" valign="top" nowrap="nowrap" style="display: none;">
		<img src="{$ImagesDir}/checkmark-error.png" alt="" />
		</td>

		{if $field.field eq "email"}
		<td id="email_error_text" valign="top" style="display: none;">
		<div id="email_note" class="cidev_NoteBox">{$lng.txt_email_invalid}</div>
		</td>
		{/if}
	

		</tr>
		</table>
            </td>
        </tr>
    {/if}
        
    {if $field.field eq 'b_state' && $js_enabled eq 'Y' && $config.General.use_js_states eq 'Y' && $field.avail eq 'Y' && $b_country_avail eq 'Y'}
        <tr style="display: none;">
            <td>
        {include file="change_states_js.tpl"}
        {include file="main/register_states.tpl" state_name="b_state" country_name="b_country" county_name="b_county" state_value=$userinfo.b_state county_value=$userinfo.b_county}
            </td>
        </tr>
    {/if}

    {if $field.ftype eq 'additional' && $field.avail eq "Y"}
        <tr valign="middle">
            <td class="cidev_padding_top" valign="top" align="right" width="40%">{$field.title|default:$field.field}
		{if $field.fieldid eq "2"}
		<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_sku_order}</div>
		{/if}
	    </td>
            <td valign="top">{if $field.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
            <td nowrap="nowrap">

		<table>
		<tr>
		<td>

                {if $field.type eq 'T'}
                    <input type="text" id="additional_values_{$field.fieldid}" name="additional_values[{$field.fieldid}]" id="additional_values_{$field.fieldid}" size="32" value="{if $field.fieldid eq "2"}{$field.value|escape|default:$rma_orderid}{else}{$field.value|escape}{/if}" {if $field.fieldid eq "2"}placeholder="{$lng.lbl_contact_placeholder_sku_order}"{/if} />
                {elseif $field.type eq 'C'}
                    <input type="checkbox" id="additional_values_{$field.fieldid}" name="additional_values[{$field.fieldid}]" id="additional_values_{$field.fieldid}" value="Y"{if $field.value eq 'Y'} checked="checked"{/if} />
                {elseif $field.type eq 'S'}
                    <select id="additional_values_{$field.fieldid}" name="additional_values[{$field.fieldid}]" id="additional_values_{$field.fieldid}">
                    {foreach from=$field.variants item=o}
                        <option value='{$o|escape}'{if $v.value eq $o} selected="selected"{/if}>{$o|escape}</option>
                    {/foreach}
                    </select>
                {/if}
                {if $fillerror ne "" and $field.value eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
	        </td>

                <td id="additional_values_{$field.fieldid}_verified" valign="top" nowrap="nowrap" style="display: none;">
                <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
                </td>
                <td id="additional_values_{$field.fieldid}_error" valign="top" nowrap="nowrap" style="display: none;">
                <img src="{$ImagesDir}/checkmark-error.png" alt="" />
                </td>

		</tr>
		</table>
	    </td>

        </tr>
    {/if}

{/foreach}

<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right" width="40%">{$lng.lbl_subject_on_contactus}
<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_subject}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td nowrap="nowrap">

	<table>
	<tr>
	<td width="450">
	<input type="text" id="subject" name="subject" size="32" maxlength="255" value="{$userinfo.subject}" placeholder="{$lng.lbl_contact_placeholder_subject}" style="width: 98%;" />
	{if $fillerror ne "" and $userinfo.subject eq ""}<font class="Star">&lt;&lt;</font>{/if}
	</td>

	<td width="25" id="subject_verified" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
	</td>
	<td width="25" id="subject_error" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-error.png" alt="" />
	</td>

	</tr>
	</table>
</td>

</tr>

<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right" width="40%">{$lng.lbl_message_on_contactus}
<div class="cidev_checkout_descr">{$lng.lbl_CONTACT_FIELD_DESCRIPTION_message_body}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td nowrap="nowrap">

        <table>
        <tr>
        <td width="450">
	<textarea style="width: 98%;" cols="48" id="message_body" rows="12" name="body" placeholder="{$lng.lbl_contact_placeholder_message_body}">{$userinfo.body}</textarea>
	{if $fillerror ne "" and $userinfo.body eq ""}<font class="Star">&lt;&lt;</font>{/if}
	</td>

        <td width="25" id="message_body_verified" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
        </td>
        <td width="25" id="message_body_error" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-error.png" alt="" />
        </td>

        </tr>
        </table>
</tr>

{if $active_modules.Image_Verification and $show_antibot.on_contact_us eq 'Y'}
{include file="modules/Image_Verification/spambot_arrest.tpl" mode="advanced" id=$antibot_sections.on_contact_us}
{/if}
<tr valign="middle">
<td align="center">
    <span class="g-recaptcha right"
          data-sitekey="{$key_recaptcha_public}"
          data-callback="registerFormOnSubmit"
          data-size="invisible"
          data-badge="inline" >
    </span>
</td>
<td>&nbsp;</td>
<td>
{if $js_enabled}
{include file="buttons/submit.tpl" href="javascript: registerFormOnValidate();" js_to_href="Y" b="1"}
{else}
{include file="submit_wo_js.tpl" value=$lng.lbl_submit}
{/if}
<br />
</td>
</tr>
</table>
<input type="hidden" name="usertype" value="{$usertype}" />
</form>
{else}
{$lng.txt_contact_us_sent}
{/if}
    <script src='https://www.google.com/recaptcha/api.js' type="text/javascript" async defer></script>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_contact_us content=$smarty.capture.dialog extra='width="100%"'}
