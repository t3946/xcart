{* $Id: refund_invoice_head.tpl,v 1.37.2.1 2011/11/15 17:04:39 kate Exp $ *}

<table cellspacing="0" cellpadding="2" width="100%">
<tr>
    <td width="30">&nbsp;</td>
    <td valign="top">
        <font style="FONT-SIZE: 28px"><b style="text-transform: uppercase;">{$lng.lbl_refund}</b></font><br /><br />
        <b>{$lng.lbl_date}:</b> {$order.date|date_format:$config.Appearance.datetime_format}<br />
        <b>{$lng.lbl_refund} #:</b> {$order.order_prefix}{$order.orderid}-{$manufacturer_code}<br />
        {assign var=cb_status value=$order.shipping_groups[$manufacturerid].cb_status}
        <b>{$lng.lbl_refund_status}:</b> {$statuses.CB[$cb_status]}<br />
        <b>{$lng.lbl_refunded_to}:</b> {$order.shipping_groups[$manufacturerid].acc_payment_method}<br />
        <b>{$lng.lbl_original_order} #:</b> {$order.order_prefix}{$order.orderid}<br />
    </td>
    <td valign="bottom" align="right">
        <b>{$config.Company.operating_company_name}</b><br />
        {$config.Company.location_address},<br />
        {$config.Company.location_city}{if $config.Company.location_country_has_states}, {$config.Company.location_state_name}{/if}<br />
        {$config.Company.location_zipcode}, {$config.Company.location_country_name}<br />
        {if $config.Company.company_phone}{$lng.lbl_phone_1_title}: {$config.Company.company_phone}<br />{/if}
        {if $config.Company.company_phone_2}{$lng.lbl_phone_2_title}: {$config.Company.company_phone_2}<br />{/if}
        {if $config.Company.company_fax}{$lng.lbl_fax}: {$config.Company.company_fax}<br />{/if}
        {if $config.Company.orders_department}{$lng.lbl_email}: {$config.Company.orders_department}<br />{/if}
        {if $order.applied_taxes}<br />
            {foreach from=$order.applied_taxes key=tax_name item=tax}
                {$tax.regnumber}<br />
            {/foreach}
        {/if}
    </td>
</tr>
</table>
