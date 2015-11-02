{*
+----------------------------------------------------------------------+
| Best Search Filter Mod                                               |
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
{*
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
*}

	{assign var="cidev_count_filter_values" value="0"}
        {foreach from=$filter.filter_values item=filter_values key=filter_values_key}
	        {if $filter_values.fv_active_found eq "Y"}
			{inc value=$cidev_count_filter_values assign="cidev_count_filter_values"}
		{/if}
	{/foreach}

	{if $cidev_count_filter_values gt 12}
	<div class="cidev_menu_scroll">
	{/if}

	<ul>
        {foreach from=$filter.filter_values item=filter_values key=filter_values_key}
        {if $filter_values.fv_active_found eq "Y"}

		{assign var="cidev_show_checked_class" value="0"}
                <input type="hidden" id="cidev_fv_{$cidev_count_active_fv}" name="cidev_fv_{$cidev_count_active_fv}"
                        {if $cidev_selected_filter_values ne ""}
                        {foreach from=$cidev_selected_filter_values item=item_fv key=key_fv_id}
                        {if $key_fv_id eq $filter_values.fv_id}
				value="{$filter_values.fv_id}"
                                {assign var="cidev_show_reset_button" value="1"}
                                {assign var="cidev_show_checked_class" value="1"}
                        {/if}
                        {/foreach}
                        {/if}
                >

		<li class="{if $cidev_show_checked_class eq "1"}cidev_checkbox_checked{else}cidev_checkbox{/if}" id="cidev_id_fv_{$cidev_count_active_fv}">
                	<a href="javascript: void(0);" onclick="javascript: func_cidev_fv_checkbox_is_clicked('{$cidev_count_active_fv}', '{$filter_values.fv_id}');">{$filter_values.fv_name}</a>
		</li>

                {inc value=$cidev_count_active_fv assign="cidev_count_active_fv"}

        {/if}
        {/foreach}
	</ul>

	{if $cidev_count_filter_values gt 12}
	</div>
	{/if}

   </td>
 </tr>
 {if $filter_key ne $cidev_count_filters_in_tree}
  <tr><td height="10">&nbsp;</td></tr>
 {/if}
{/if}
{/foreach}
{/if}


{* {$cidev_manufacturers} --- {$main} *}


<input type="hidden" id="cidev_count_active_fv" name="cidev_count_active_fv" value="{$cidev_count_active_fv}" /> 

{if $config.CIDEV_Best_Search_Filter.cidev_disable_manufacturers ne "Y"}
 {if $cidev_manufacturers ne "" && $main ne "manufacturer_products"}

{*
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
*}

{* ------------------------- *}

 {assign var="cidev_count_active_manuf" value=0}
 {if $cidev_count_filters_in_tree gt 0}
 <tr><td height="10">&nbsp;</td></tr>
 {/if}
 <tr>
   <td valign="top">
	<h3>{$lng.lbl_manufacturers}</h3>

	{if $cidev_count_manufacturers_in_menu gt 12}
	<div class="cidev_menu_scroll">
	{/if}

	<ul>
        {foreach from=$cidev_manufacturers item=cidev_manufacturer key=manufacturer_key}

		{assign var="cidev_show_checked_class" value="0"}
                <input type="hidden" id="cidev_manuf_{$cidev_count_active_manuf}" name="cidev_manuf_{$cidev_count_active_manuf}"
                        {if $cidev_selected_manuf_values ne ""}
                        {foreach from=$cidev_selected_manuf_values item=item_m key=key_m_id}
                        {if $key_m_id eq $cidev_manufacturer.manufacturerid}
                                value="{$cidev_manufacturer.manufacturerid}"
                                {assign var="cidev_show_reset_button" value="1"}
				{assign var="cidev_show_checked_class" value="1"}
                        {/if}
                        {/foreach}
                        {/if}
                >

		 <li class="{if $cidev_show_checked_class eq "1"}cidev_checkbox_checked{else}cidev_checkbox{/if}" id="cidev_id_manuf_{$cidev_count_active_manuf}">
			<a href="javascript: void(0);" onclick="javascript: func_cidev_manuf_checkbox_is_clicked('{$cidev_count_active_manuf}', '{$cidev_manufacturer.manufacturerid}');">{$cidev_manufacturer.manufacturer}</a>
 		</li>

                {inc value=$cidev_count_active_manuf assign="cidev_count_active_manuf"}
        {/foreach}
	</ul>

	{if $cidev_count_manufacturers_in_menu gt 12}
	</div> 
	{/if}

        </table>
   </td>
 </tr>

{* ------------------------- *}

 {/if}
 <input type="hidden" id="cidev_count_active_manuf" name="cidev_count_active_manuf" value="{$cidev_count_active_manuf}" />
{/if}

</table>

{if $cidev_show_reset_button eq "1"}
 <div align="center">
  <br />
  {include file="modules/CIDEV_Best_Search_Filter/customer/buttons/button.tpl" href="javascript: func_cidev_reset_filter();" button_title=$lng.lbl_cidev_reset_filter }
  <br />
 </div>
{/if}

