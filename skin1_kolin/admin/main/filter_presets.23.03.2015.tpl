{* $Id: filter_presets.tpl,v 1.0.0.0 2012/06/06 08:39:27 kirill Exp $ *}

{if $filter ne ''}
<form action="configuration.php?option=Filter_Presets" method="post">
<input type="hidden" name="fid" value="{$filter.fid}" />
<table width="100%" class="filter_preset_edit">
    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_preset_title}:</td>
        <td width="85%"><input type="text" name="title" value="{$filter.title}" class="text preset_title" /></td>
    </tr>

    <tr>
        <td width="15%" class="preset_bold">Preset position:</td>
        <td width="85%"><input type="text" name="preset_position" value="{$filter.preset_position}" class="text preset_title" /></td>
    </tr>

    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_bold}:</td>
        <td width="85%"><input type="checkbox" name="bold" value="Y"{if $filter.bold eq "Y"} checked="checked"{/if} /></td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">Marker (S:848484,M:ff4444):</td>
        <td width="85%"><input type="text" name="marker" value="{$filter.marker}" class="text preset_title" /></td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">Sales channel:</td>
        <td width="85%">
            <select name="orders_source" class="select">
                <option value="any">Any channel</option>
                <option value="xcart_orders_only" {if $filter.orders_source eq "xcart_orders_only"}selected="selected"{/if}>S3 Stores websites</option>
                <option value="amazon_orders_only" {if $filter.orders_source eq "amazon_orders_only"}selected="selected"{/if}>Amazon website</option>
            </select>
        </td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_c2b_status}:</td>
        <td width="85%">
            <select name="status[]" multiple="multiple" class="select c2b">
            {foreach from=$statuses.CB item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">Check transit status:</td>
        <td width="85%">
            <select name="status[]" multiple="multiple" class="select">
            {foreach from=$statuses.PO item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_d2c_status}:</td>
        <td width="85%">
            <select name="status[]" multiple="multiple" class="select d2c">
            {foreach from=$statuses.DC item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_b2d_status}:</td>
        <td width="85%">
            <select name="status[]" multiple="multiple" class="select">
            {foreach from=$statuses.BD item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>

{*
    <tr>
        <td width="15%" class="preset_bold">"Currently assigned to" status:</td>
        <td width="85%">
            <select name="status[]" multiple="multiple" class="select" size="5">
            {foreach from=$statuses.CA item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>
*}

    <tr>
        <td width="15%" class="preset_bold">Product question statuses:</td>
        <td width="85%">
            <select name="product_question_statuses_filter[]" multiple="multiple" class="select" size="5">
            {foreach from=$product_question_statuses_filter item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>

    <tr>
        <td width="15%" class="preset_bold">Fraud check status:</td>
        <td width="85%">
            <select name="fraud_statuses_filter[]" multiple="multiple" class="select" size="5">
            {foreach from=$fraud_statuses_filter item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>

    <tr>
        <td width="15%" class="preset_bold">Attention tag:</td>
        <td width="85%">
{*            <select name="attention_tags_values_filter[]" multiple="multiple" class="select" size="5"> *}
            <select name="attention_tags_values_filter[]" class="select">
		<option value=""></option>
            {foreach from=$attention_tags_values_filter item=status key=k}
                <option value="{$k}"{if $status.selected eq "Y"} selected="selected"{/if}>{$status.name}</option>
            {/foreach}
            </select>
        </td>
    </tr>


    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_manufacturers}:</td>
        <td width="85%">
            <select name="distributors[]" multiple="multiple" class="select distributor">
            {foreach from=$distributors item=distributor key=k}
                <option value="{$k}"{if $distributor.selected eq "Y"} selected="selected"{/if}>{$distributor.manufacturer}</option>
            {/foreach}
            </select>
        </td>
    </tr>


    <tr>
        <td width="15%" class="preset_bold">'Ship to' country:</td>
        <td width="85%">
            <select name="ship_to_countries_filter[]" multiple="multiple" class="select distributor">
            {foreach from=$ship_to_countries_filter item=country_f key=k}
                <option value="{$country_f.country_code}"{if $country_f.selected eq "Y"} selected="selected"{/if}>{$country_f.country}</option>
            {/foreach}
            </select>
        </td>
    </tr>


    <tr>
        <td width="15%" class="preset_bold">Processor:</td>
        <td width="85%">
            <select name="processor_empty">
                <option value=""></option>
                <option value="N"{if $filter.processor_empty eq "N"} selected="selected"{/if}>Not empty</option>
                <option value="Y"{if $filter.processor_empty eq "Y"} selected="selected"{/if}>Empty</option>
            </select>
        </td>
    </tr>


    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_time_range_from}:</td>
        <td width="85%">
            <table cellpadding="0" cellspacing="0"><tr>
            <td class="time_from_mode_1" nowrap="nowrap">
                <input class="time_from_mode" type="radio" name="time_from_mode" value="H"{if $filter.time_from_mode eq "H"} checked="checked"{/if}/>
                <input type="text" value="{$filter.time_from}" name="time_from" class="time_range_to" />
                {$lng.lbl_hours_ago_respect}
                <select name="placement_time_from_type">
                    <option value="O"{if $filter.placement_time_from_type eq "O"} selected="selected"{/if}>{$lng.opt_order_placement_time}</option>
		    <option value="M"{if $filter.placement_time_from_type eq "M"} selected="selected"{/if}>Min (Dispatched time / Received by distributor time)</option>
                    <option value="D"{if $filter.placement_time_from_type eq "D"} selected="selected"{/if}>{$lng.opt_d2c_dispatched_time}</option>
		    <option value="R"{if $filter.placement_time_from_type eq "R"} selected="selected"{/if}>Received by distributor time</option>
                </select>
            </td>
            <td class="time_from_mode_2">
                <input type="radio" name="time_from_mode" value="D"{if $filter.time_from_mode eq "D"} checked="checked"{/if}/>
                {html_select_date prefix="from" time=$filter.time_from_date start_year=$config.Company.start_year end_year=$config.Company.end_year}
            </td>
            </tr></table>
        </td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_time_range_to}:</td>
        <td width="85%" nowrap="nowrap">
            <input type="text" value="{$filter.time_to}" name="time_to" class="time_range_to" />
            {$lng.lbl_hours_ago_respect}
            <select name="placement_time_to_type">
                <option value="O"{if $filter.placement_time_to_type eq "O"} selected="selected"{/if}>{$lng.opt_order_placement_time}</option>
                <option value="M"{if $filter.placement_time_to_type eq "M"} selected="selected"{/if}>Min (Dispatched time / Received by distributor time)</option>
                <option value="D"{if $filter.placement_time_to_type eq "D"} selected="selected"{/if}>{$lng.opt_d2c_dispatched_time}</option>
                <option value="R"{if $filter.placement_time_to_type eq "R"} selected="selected"{/if}>Received by distributor time</option>
            </select>
        </td>
    </tr>
    <tr>
        <td width="15%" class="preset_bold">{$lng.lbl_preset_availability}:</td>
        <td width="85%">
            <select name="enabled">
                <option value="Y"{if $filter.enabled eq "Y"} selected="selected"{/if}>{$lng.lbl_enabled}</option>
                <option value="N"{if $filter.enabled ne "Y"} selected="selected"{/if}>{$lng.lbl_disabled}</option>
            </select>
        </td>
    </tr>
    <tr>
        <td width="15%">&nbsp;</td>
        <td width="85%" colspan="2"><br /><input type="submit" value="{$lng.lbl_save}" /></td>
    </tr>
</table>
</form>
{else}
<table width="100%" class="preset_table">
{foreach from=$filters item=row}
<tr>
    {foreach from=$row item=filter}
        <td width="20%"{if $filter.enabled ne 'Y'} class="filter_disabled"{/if}><a href="configuration.php?option=Filter_Presets&fid={$filter.fid}">{if $filter.title ne ''}{$filter.title}{else}{$lng.lbl_filter_empty|escape}{/if} ({$filter.row},{$filter.column})</a></td>
    {/foreach}
<tr>
{/foreach}
</table>
{/if}
