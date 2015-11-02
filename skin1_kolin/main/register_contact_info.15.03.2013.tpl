{* $Id: register_contact_info.tpl,v 1.10 2005/11/17 06:55:39 max Exp $ *}
{if $is_areas.C eq 'Y'}
{if $hide_header eq ""}
<tr>
<td height="20" colspan="3"><font class="RegSectionTitle">{$lng.lbl_contact_information}</font><hr size="1" noshade="noshade" /></td>
</tr>
{/if}

<tr>
<td colspan="3">{$lng.txt_newbie_registration_bottom_small}</td>
</tr>

{if $is_areas.P eq 'Y'}
{if $default_fields.title.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_title}</td>
<td>{if $default_fields.title.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<select name="title" id="title">
{include file="main/title_selector.tpl" field=$userinfo.titleid}
</select>
</td>
</tr>
{/if}
{if $default_fields.firstname.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_first_name}</td>
<td>{if $default_fields.firstname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="{$userinfo.firstname}" placeholder="{$lng.lbl_fill_in_examples_firstname}" />
{if $reg_error ne "" and $userinfo.firstname eq "" && $default_fields.firstname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.lastname.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_last_name}</td>
<td>{if $default_fields.lastname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="lastname" name="lastname" size="32" maxlength="32" value="{$userinfo.lastname}" />
{if $reg_error ne "" and $userinfo.lastname eq "" && $default_fields.lastname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.company.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_company}</td>
<td>{if $default_fields.company.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="company" name="company" size="32" maxlength="255" value="{$userinfo.company}" placeholder="{$lng.lbl_fill_in_examples_Company_name}" />
{if $reg_error ne "" and $userinfo.company eq "" && $default_fields.company.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.ssn.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_ssn}</td>
<td>{if $default_fields.ssn.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="ssn" name="ssn" size="32" maxlength="32" value="{$userinfo.ssn}" />
{if $reg_error ne "" and $userinfo.ssn eq "" && $default_fields.ssn.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.tax_number.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_tax_number}</td>
<td>{if $default_fields.tax_number.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
{if $userinfo.tax_exempt ne "Y" or $config.Taxes.allow_user_modify_tax_number eq "Y" or $usertype eq "A" or $usertype eq "P"}
<input type="text" id="tax_number" name="tax_number" size="32" maxlength="32" value="{$userinfo.tax_number}" />
{if $reg_error ne "" and $userinfo.tax_number eq "" && $default_fields.tax_number.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
{else}
{$userinfo.tax_number}
{/if}
</td>
</tr>
{/if}
{if $config.Taxes.enable_user_tax_exemption eq 'Y'}
{if (($userinfo.usertype eq "C" or $smarty.get.usertype eq "C") and $userinfo.tax_exempt eq "Y") or ($usertype eq "A" or $usertype eq "P")}
<tr>
<td align="right">{$lng.lbl_tax_exemption}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
{if $usertype eq "A" or $usertype eq "P"} 
<input type="checkbox" id="tax_exempt" name="tax_exempt" value="Y"{if $userinfo.tax_exempt eq "Y"} checked="checked"{/if} />
{elseif $userinfo.tax_exempt eq "Y"}
{$lng.txt_tax_exemption_assigned}
{/if}
</td>
</tr>
{/if}
{/if}
{if $usertype eq "A" or $usertype eq "P"}
<tr>
<td align="right">{$lng.lbl_referred_by}</td>
<td></td>
<td nowrap="nowrap">
{if $userinfo.referer}
<a href="{$userinfo.referer}">{$userinfo.referer}</a>
{else}
{$lng.lbl_unknown}
{/if}
</td>
</tr>
{/if}
{include file="main/register_additional_info.tpl" section="P"}
{/if}

{if $default_fields.phone.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_phone}</td>
<td>{if $default_fields.phone.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="phone" name="phone" size="32" maxlength="32" value="{$userinfo.phone}" placeholder="{$lng.lbl_fill_in_examples_phone}" />
{if $reg_error ne "" and $userinfo.phone eq "" and $default_fields.phone.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.email.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_email}</td>
<td>{if $default_fields.email.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="email" name="email" size="32" maxlength="128" value="{$userinfo.email}" placeholder="{$lng.lbl_fill_in_examples_email}" />
{if $emailerror ne "" or ($reg_error ne "" and $userinfo.email eq "" and $default_fields.email.required eq 'Y')}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.fax.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_fax}</td>
<td>{if $default_fields.fax.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="fax" name="fax" size="32" maxlength="128" value="{$userinfo.fax}" />
{if $reg_error ne "" and $userinfo.fax eq "" and $default_fields.fax.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.url.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_web_site}</td>
<td>{if $default_fields.url.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="url" name="url" size="32" maxlength="128" value="{$userinfo.url}" />
{if $reg_error ne "" and $userinfo.url eq "" and $default_fields.url.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{include file="main/register_additional_info.tpl" section="C"}
{/if}

