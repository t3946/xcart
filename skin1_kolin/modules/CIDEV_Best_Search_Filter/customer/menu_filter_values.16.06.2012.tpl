{*
+----------------------------------------------------------------------+
| Advanced Filter Mod                                                  |
+----------------------------------------------------------------------+
| Copyright (c) 2009-2012 CIDEV, xcartmaster@gmail.com                 |
+----------------------------------------------------------------------+
*}

{assign var="cidev_show_reset_button" value="0"}

{assign var="cidev_count_active_fv" value="0"}
<table width="100%">
{if $cidev_filters_tree ne ""}

{math assign="cidev_count_filters_in_tree" equation="x-1" x=$cidev_count_filters_in_tree}

{foreach from=$cidev_filters_tree item=filter key=filter_key}
{if $filter.f_active_found eq "Y"}
 <tr>
   <td valign="top">
        <h3>{$filter.f_name}</h3>
        <table>
        {foreach from=$filter.filter_values item=filter_values key=filter_values_key}
	{if $filter_values.fv_active_found eq "Y"}
         <tr>
           <td>
                <input type="checkbox" id="cidev_fv_{$cidev_count_active_fv}" name="cidev_fv_{$cidev_count_active_fv}" value="{$filter_values.fv_id}" 
                        {if $cidev_selected_filter_values ne ""}
                        {foreach from=$cidev_selected_filter_values item=item_fv key=key_fv_id}
                        {if $key_fv_id eq $filter_values.fv_id} 
				checked="checked"
				{assign var="cidev_show_reset_button" value="1"}
			{/if}
                        {/foreach}
                        {/if}
			{if $config.CIDEV_Best_Search_Filter.cidev_reload_on_action eq "checkbox"}onclick="javascript: cidev_send_filter_values();"{/if}
                >
           </td>
           <td>
                {$filter_values.fv_name}
           </td>
         </tr>
		{inc value=$cidev_count_active_fv assign="cidev_count_active_fv"}
	{/if}
        {/foreach}
        </table>
   </td>
 </tr>
 {if $filter_key ne $cidev_count_filters_in_tree}
  <tr><td height="10">&nbsp;</td></tr>
 {/if}
{/if}
{/foreach}
{/if}
<input type="hidden" id="cidev_count_active_fv" name="cidev_count_active_fv" value="{$cidev_count_active_fv}" /> 

{if $config.CIDEV_Best_Search_Filter.cidev_disable_manufacturers ne "Y"}
 {if $cidev_manufacturers ne "" && $main ne "manufacturer_products"}

 {assign var="cidev_count_active_manuf" value=0}
 <tr><td height="10">&nbsp;</td></tr>
 <tr>
   <td valign="top">
	<h3>{$lng.lbl_manufacturers}</h3>
	<table>
	{foreach from=$cidev_manufacturers item=cidev_manufacturer key=manufacturer_key}
	 <tr>
           <td>
                <input type="checkbox" id="cidev_manuf_{$cidev_count_active_manuf}" name="cidev_manuf_{$cidev_count_active_manuf}" value="{$cidev_manufacturer.manufacturerid}"
                        {if $cidev_selected_manuf_values ne ""}
                        {foreach from=$cidev_selected_manuf_values item=item_m key=key_m_id}
                        {if $key_m_id eq $cidev_manufacturer.manufacturerid} 
				checked="checked"
				{assign var="cidev_show_reset_button" value="1"}
			{/if}
                        {/foreach}
                        {/if}
			{if $config.CIDEV_Best_Search_Filter.cidev_reload_on_action eq "checkbox"}onclick="javascript: cidev_send_filter_values();"{/if}
                >
           </td>
	   <td>
	        {$cidev_manufacturer.manufacturer}
	   </td>
	 </tr>
		{inc value=$cidev_count_active_manuf assign="cidev_count_active_manuf"}
	{/foreach}
	</table>
   </td>
 </tr>

 {/if}
 <input type="hidden" id="cidev_count_active_manuf" name="cidev_count_active_manuf" value="{$cidev_count_active_manuf}" />
{/if}

</table>

{if $cidev_show_reset_button eq "1"}
 <div align="center">
  <br />
  {include file="customer/buttons/button.tpl" href="javascript: func_cidev_reset_filter();" button_title=$lng.lbl_cidev_reset_filter }
  <br />
 </div>
{/if}

