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
<table width="100%" cellspacing="0" cellpadding="2">

{foreach from=$all_fields item=field}
    {if $field.ftype eq 'default' 
        && $field.avail eq 'Y' 
        &&  ($field.field eq 'b_county' && $config.General.use_counties eq "Y" 
            || $field.field eq 'department' && $departments
            || $field.field ne 'b_county' && $field.field ne 'department')
    }
        <tr valign="middle">
            <td class="FormButton">
                {if $field.field eq 'username'}
                    {$lng.lbl_username}
                {elseif $field.field eq 'title'}
                    {$lng.lbl_title}
                {elseif $field.field eq 'firstname'}
                    {$lng.lbl_first_name_on_contactus}
                {elseif $field.field eq 'lastname'}
                    {$lng.lbl_last_name}
                {elseif $field.field eq 'company'}
                    {$lng.lbl_company_on_contactus}
                {elseif $field.field eq 'b_address'}
                    {$lng.lbl_address_on_contactus}
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
                    {$lng.lbl_zip_code}
                {elseif $field.field eq 'phone'}
                    {$lng.lbl_phone_on_contactus}
                {elseif $field.field eq 'email'}
                    {$lng.lbl_email_on_contactus}
                {elseif $field.field eq 'fax'}
                    {$lng.lbl_fax}
                {elseif $field.field eq 'url'}
                    {$lng.lbl_web_site}
                {elseif $field.field eq 'department'}
                    {$lng.lbl_department}
                {/if}

		{if $field.required ne 'Y'}<I><font style="font-weight: normal;">(Optional)</font></I>{/if}
	</td>
 
            <td>{if $field.required eq 'Y'}<font class="Star">*</font>{/if}</td>
            <td nowrap="nowrap">
                {if $field.field eq 'username'}
                    <input type="text" id="username" name="username" size="32" maxlength="32" value="{if $userinfo.username ne ''}{$userinfo.username}{else}{$userinfo.login}{/if}" />
                {elseif $field.field eq 'title'}
                    <select id="title" name="title">
                        {include file="main/title_selector.tpl" field=$userinfo.titleid}
                    </select>
                {elseif $field.field eq 'firstname'}
                    <input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="{$userinfo.firstname}" />
                    {if $fillerror ne "" and $userinfo.firstname eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'lastname'}
                    <input type="text" id="lastname" name="lastname" size="32" maxlength="32" value="{$userinfo.lastname}" />
                    {if $fillerror ne "" and $userinfo.lastname eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'company'}
                    <input type="text" id="company" name="company" size="32" value="{$userinfo.company}" />
                    {if $fillerror ne "" and $userinfo.company eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'b_address'}
                    <input type="text" id="b_address" name="b_address" size="32" maxlength="64" value="{$userinfo.b_address}" />
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
                    <input type="text" id="b_zipcode" name="b_zipcode" size="32" maxlength="32" value="{$userinfo.b_zipcode}" onchange="javascript: check_zip_code(document.getElementById('b_country'), this);" />
                    {if $fillerror ne "" and $userinfo.b_zipcode eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'phone'}
                    <input type="text" id="phone" name="phone" size="32" maxlength="32" value="{$userinfo.phone}" />
                    {if $fillerror ne "" and $userinfo.phone eq "" && $field.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                {elseif $field.field eq 'email'}
                    <input type="text" id="email" name="email" size="32" maxlength="128" value="{$userinfo.email}" onchange="javascript: checkEmailAddress(this);" />
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
            <td class="FormButton">{$field.title|default:$field.field}</td>
            <td>{if $field.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
            <td nowrap="nowrap">
                {if $field.type eq 'T'}
                    <input type="text" id="additional_values_{$field.fieldid}" name="additional_values[{$field.fieldid}]" id="additional_values_{$field.fieldid}" size="32" value="{$field.value|escape}" />
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
        </tr>
    {/if}

{/foreach}

<tr valign="middle">
<td class="FormButton">{$lng.lbl_subject_on_contactus}</td>
<td><font class="Star">*</font></td>
<td nowrap="nowrap">
<input type="text" id="subject" name="subject" size="32" maxlength="128" value="{$userinfo.subject}" />
{if $fillerror ne "" and $userinfo.subject eq ""}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>

<tr valign="middle">
<td class="FormButton">{$lng.lbl_message_on_contactus}</td>
<td><font class="Star">*</font></td>
<td nowrap="nowrap">
<textarea cols="48" id="message_body" rows="12" name="body">{$userinfo.body}</textarea>
{if $fillerror ne "" and $userinfo.body eq ""}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>

{if $active_modules.Image_Verification and $show_antibot.on_contact_us eq 'Y'}
{include file="modules/Image_Verification/spambot_arrest.tpl" mode="advanced" id=$antibot_sections.on_contact_us}
{/if}
<tr valign="middle">
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>
{if $js_enabled}
{include file="buttons/submit.tpl" href="javascript: if (checkEmailAddress(document.registerform.email) && checkRequired(requiredFields) && check_zip_code(document.getElementById('b_country'), document.getElementById('b_zipcode'))) document.registerform.submit()" js_to_href="Y" b="1"}
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
{/capture}
{include file="dialog.tpl" title=$lng.lbl_contact_us content=$smarty.capture.dialog extra='width="100%"'}
