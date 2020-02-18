{extends "fraud_check/order_list.tpl"}
{block 'list'}
    {foreach $additional_info as $v}
        <tr>
            <td>
                <a href="{$v->getAdminUrl()}" target="_blank">{$v->getOrderNumber()}</a>
            </td>
            <td>
                {$v->date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$v->cb_status_model}/{$v->dc_status_model}
            </td>
        </tr>
    {/foreach}
{/block}