{* $Id: register_additional_info.tpl,v 1.4 2005/11/17 06:55:39 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}
<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
  $(document).ready(function() {  
        $('#additional_values_2').focusout(function() {
                if ($('#additional_values_2').val() != ""){
                        if (document.getElementById("additional_values_2") && document.getElementById("additional_values_2_error")){
                                document.getElementById("additional_values_2_verified").style.display = '';                      
                                document.getElementById("additional_values_2_error").style.display = 'none';     
                        }
                }
                else {
                        if (document.getElementById("additional_values_2_verified") && document.getElementById("additional_values_2_error")){
                                document.getElementById("additional_values_2_verified").style.display = 'none';                      
                                document.getElementById("additional_values_2_error").style.display = 'none';  
                        }
                }
        });

        $('#additional_values_1').focusout(function() {
                if ($('#additional_values_1').val() != ""){
                        if (document.getElementById("additional_values_1") && document.getElementById("additional_values_1_error")){
                                document.getElementById("additional_values_1_verified").style.display = '';                      
                                document.getElementById("additional_values_1_error").style.display = 'none';     
                        }
                }
                else {
                        if (document.getElementById("additional_values_1_verified") && document.getElementById("additional_values_1_error")){
                                document.getElementById("additional_values_1_verified").style.display = 'none';                      
                                document.getElementById("additional_values_1_error").style.display = 'none';  
                        }
                }
        });
  });
{/literal}
//]]>
</script>
{/if}


{if $section ne '' && $additional_fields ne '' && (($is_areas.A eq 'Y' && $section eq 'A') || $section ne 'A')}
{if $hide_header eq "" && $section eq 'A'}
<tr>
<td height="20" colspan="3"><font class="RegSectionTitle">{$lng.lbl_additional_information}</font><hr size="1" noshade="noshade" /></td>
</tr>
{/if}
{foreach from=$additional_fields item=v}
{if $section eq $v.section && $v.avail eq 'Y'}
<tr>
<td class="cidev_padding_top" valign="top" align="right">{$v.title|default:$v.field} {if $v.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>{/if}

{if $v.title eq "Company" && ($section eq "S" || $section eq "B") && $usertype eq "C"}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_company}</div>
{/if}

</td>
<td valign="top">{if $v.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
{if $v.type eq 'T'}
{if $v.title eq "Company" && ($section eq "S" || $section eq "B") && $usertype eq "C"}
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
{/if}

<input type="text" name="additional_values[{$v.fieldid}]" id="additional_values_{$v.fieldid}" size="32" value="{$v.value|escape|replace:"&amp;#039;":"'"}" {if $v.title eq "Company"} placeholder="{$lng.lbl_fill_in_examples_Company_name}" {/if} onkeyup="cidev_check_field_if_empty('additional_values_{$v.fieldid}')" />

{if $v.title eq "Company" && ($section eq "S" || $section eq "B") && $usertype eq "C"}
</td>
<td id="additional_values_{$v.fieldid}_verified" valign="top" nowrap="nowrap" {if $v.value eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="additional_values_{$v.fieldid}_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
</tr>
</table>
{/if}

{elseif $v.type eq 'C'}
<input type="checkbox" name="additional_values[{$v.fieldid}]" id="additional_values_{$v.fieldid}" value="Y"{if $v.value eq 'Y'} checked="checked"{/if} />
{elseif $v.type eq 'S'}
<select name="additional_values[{$v.fieldid}]" id="additional_values_{$v.fieldid}">
{foreach from=$v.variants item=o}
<option value='{$o|escape}'{if $v.value eq $o} selected="selected"{/if}>{$o|escape}</option>
{/foreach}
</select>
{/if}
{if $reg_error ne "" and $v.value eq "" && $v.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{/foreach}
{/if}
