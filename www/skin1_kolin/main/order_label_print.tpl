{* $Id: order_label_print.tpl,v 1.5 2005/05/04 05:56:07 max Exp $ *}
<table border="0" cellspacing="0" cellpadding="0" border="0">
<tr>
<td colspan="3" style="font-size: 12pt;font-family: Arial; " width="100%">
  From:&nbsp; {$config.Company.company_name}<br />  
  {$config.Company.location_address}<br />  
  {$config.Company.location_city}, {$config.Company.location_state_name} {$config.Company.location_zipcode}<br />  
  {$config.Company.location_country_name}		
</td>
</tr>
<tr>
<td width="130">
</td>
<td valign="top" style="font-size: 14pt;font-family: Arial; ">
 <br />To:&nbsp;
</td>
<td style="font-size: 14pt;font-family: Arial; ">
 <br />{$customer.s_firstname|default:$customer.firstname} {$customer.s_lastname|default:$customer.lastname}<br />
 {if $customer.additional_fields[1].value ne ''} 
 {$customer.additional_fields[1].value}<br />
 {/if}
 {$customer.s_address}<br />  
{if $customer.s_address_2 ne ''}
 {$customer.s_address_2}<br />  
{/if}
 {$customer.s_city}, {$customer.s_state_text} {$customer.s_zipcode}<br />  
 {$customer.s_country_text}
</td>
</tr>
</table>
