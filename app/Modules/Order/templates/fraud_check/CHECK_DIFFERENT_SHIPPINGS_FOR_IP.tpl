{extends "fraud_check/order_list.tpl"}
{block 'list'}
    {foreach $additional_info as $v}
        {set $order_address = $v->getAddressInfo()}
        <tr>
            <td>
                <a href="{$v->getAdminUrl()}" target="_blank">{$v->getOrderNumber()}</a>
            </td>
            <td>
                {$v->date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'} {$order_address[0]['address'][0]},
                {if $order_address[0]['address'][1]}{$order_address[0]['address'][1]},{/if} {$v->s_city},
                {$v->s_state}, {$v->s_country}, {$v->s_zipcode}
            </td>
        </tr>
    {/foreach}
{/block}