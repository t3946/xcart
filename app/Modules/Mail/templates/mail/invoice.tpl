{extends "mail/invoice_template.tpl"}




{block 'message'}
    <p/>
    {set $message = $order->notification->email_body}
    {$message|replace:"{{c-fullname}}":$order->firstname}
{/block}

{block 'content'}
    {add $site = $.getSite}
    {add $config = $site->getGlobalConfig()}
    {add $site_config  = $site->getConfig()}
    <table cellspacing="0" cellpadding="0" width="600" bgcolor="#ffffff">
    <tr>
    <td>
    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
            <td valign="top"><br/><br/>
                <img src="{$site->getAbsoluteUrl()}/skin1_kolin/images/S3-Stores-Logo-M.png" alt=""/>
            </td>
            <td width="100%">
                <table cellspacing="0" cellpadding="2" width="100%">
                    <tr>
                        <td width="30">&nbsp;</td>
                        <td valign="top">
                            <span style="FONT-SIZE: 28px"><b style="text-transform: uppercase;">Invoice</b></span>
                            <br/><br/>
                            {set $datetime_format = $config.date_format ~~ $config.time_format}
                            <b>Date:</b> {$order->date|date_format:$datetime_format}<br/>
                            <b>Order:</b>
                                {if $type == 'A'}
                                    <a href="https://www.artistsupplysource.com/admin/order.php?orderid={$order->orderid}">{$order->getOrderNumber()}</a>
                                {else}
                                    {$order->getOrderNumber()}
                                {/if}
                            <br/><b>Order status:</b> please see below
                            <br/><b>Payment method:</b><br/>
                                {$order->payment_method->payment_method}<br/>
                            <b>Delivery methods:</b><br/>
                            {foreach $order->groups as $group}
                                {$group->shippingModel->getFrontendName()}<br/>
                            {/foreach}
                        </td>
                        <td valign="bottom" align="right">
                            <b>{$config.operating_company_name}</b><br/>
                            {$config.location_address}
                            ,<br/>{$config.location_city}{if $config.location_country_has_states}, {$config.location_state_name}{/if}
                            <br/>
                            {$config.location_zipcode}, {$config.location_country_name}<br/>
                            {if $config.company_phone}Toll Free: {$config.company_phone}
                                <br/>
                            {/if}
                            {if $config.company_phone_2}Tel: {$config.company_phone_2}
                                <br/>
                            {/if}
                            {if $config.company_fax}Fax: {$config.company_fax}<br/>{/if}
                            {if $config.orders_department}Email: {$config.orders_department}
                                <br/>
                            {/if}

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <hr style="width:100%;margin: 0; border: 0 none; border-bottom: 1px solid #999999;"/>
            </td>
        </tr>
    </table>

    &nbsp;{set $purchase_order = $order->extra_model->purchase_order}

    <br/>
    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
            <td width="45%">
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tr>
                        <td nowrap="nowrap" width="40%"><b>Full Name:</b></td>
                        <td>{$order.firstname}</td>
                    </tr>
                        <tr>
                            <td><b>Phone:</b></td>
                            <td>
                                {$order->phone}
                                {if $order->phone_ext}
                                    <b>ext</b>
                                    {$order->phone_ext}
                                {/if}
                            </td>
                        </tr>
                        <tr>
                            <td><b>Email:</b></td>
                            <td>{$order->email}</td>
                        </tr>

                </table>
            </td>
            <td width="10%">&nbsp;</td>
            <td width="45%" style="vertical-align: top;">
                {if $purchase_order}
                    <table cellspacing="0" cellpadding="0" width="100%" border="0">
                        <tr>
                            <td width="40%"><b>PO Number:</b></td>
                            <td>{$purchase_order['po_number']}</td>
                        </tr>
                        <tr>
                            <td><b>Company Name:</b></td>
                            <td>{$purchase_order->company_name}</td>
                        </tr>

                    </table>
                {/if}
            </td>
        </tr>
    </table>
    {if $purchase_order}
        &nbsp;
        <br/>
        <table cellspacing="0" cellpadding="0" width="100%" border="0">
            <tr>
                <td width="45%" height="25"><b>Purchase manager</b>
                    <hr style="width:100%;margin: 0; border: 0 none; border-bottom: 1px solid #999999;"/>
                </td>
                <td width="10%">&nbsp;</td>
                <td width="45%" height="25"><b>Accounts payable</b>
                    <hr style="width:100%;margin: 0; border: 0 none; border-bottom: 1px solid #999999;"/>
                </td>
            </tr>

            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0" width="100%" border="0">
                        <tr>
                            <td width="40%"><b>Full Name:</b></td>
                            <td>{$purchase_order['name_of_purchaser']}</td>
                        </tr>
                        <tr>
                            <td><b>Phone:</b></td>
                            <td>{$purchase_order['purchase_manager_phone']}
                                {if $purchase_order['purchase_manager_phone_ext']}
                                    <b>ext</b>
                                    {$purchase_order['purchase_manager_phone_ext']}
                                    {/if}
                            </td>
                        </tr>
                        <tr>
                            <td><b>Fax:</b></td>
                            <td>{$purchase_order['po_fax']}</td>
                        </tr>
                        <tr>
                            <td><b>Email:</b></td>
                            <td>{$purchase_order['purchase_manager_email']}</td>
                        </tr>
                    </table>
                </td>
                <td width="10%">&nbsp;</td>
                <td>
                    <table cellspacing="0" cellpadding="0" width="100%" border="0">
                        <tr>
                            <td width="40%"><b>Full Name:</b></td>
                            <td>{$purchase_order['accounts_payable_full_name']}</td>
                        </tr>
                        <tr>
                            <td><b>Phone:</b></td>
                            <td>{$purchase_order['accounts_payable_phone']}
                                {if $purchase_order['accounts_payable_phone_ext']}
                                    <b>ext</b>
                                    {$purchase_order['accounts_payable_phone_ext']}
                                {/if}
                            </td>
                        </tr>
                        <tr>
                            <td><b>Fax:</b></td>
                            <td>{$purchase_order['accounts_payable_fax']}</td>
                        </tr>
                        <tr>
                            <td><b>Email:</b></td>
                            <td>{$purchase_order['accounts_payable_email']}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    {/if}
    <br/>
    <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
            <td width="45%" height="25"><b>Shipping Address</b>
                <hr style="width:100%;margin: 0; border: 0 none; border-bottom: 1px solid #999999;"/>
            </td>
            <td width="10%">&nbsp;</td>
            <td width="45%" height="25"><b>Billing Address</b>
                <hr style="width:100%;margin: 0; border: 0 none; border-bottom: 1px solid #999999;"/>
            </td>
        </tr>

        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                        <tr>
                            <td width="40%"><b>Full Name:</b></td>
                            <td>{$order->s_firstname}</td>
                        </tr>
                        <tr>
                            <td><b>Address:</b></td>
                            <td>{$order->s_address|nl2br}</td>
                        </tr>
                        <tr>
                            <td><b>City:</b></td>
                            <td>{$order->s_city}</td>
                        </tr>
                        <tr>
                            <td><b>State/Province:</b></td>
                            <td>{$order->shipping_state ?: $order->s_state}</td>
                        </tr>
                        <tr>
                            <td><b>Country:</b></td>
                            <td>{$order->shipping_country}</td>
                        </tr>
                        <tr>
                            <td><b>Zip/Postal Code:</b></td>
                            <td>{$order->s_zipcode}</td>
                        </tr>

                </table>
            </td>
            <td>&nbsp;</td>
            <td>
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                        <tr>
                            <td width="40%"><b>Full Name:</b></td>
                            <td>{$order->b_firstname}</td>
                        </tr>
                        <tr>
                            <td><b>Address:</b></td>
                            <td>{$order->b_address|nl2br}</td>
                        </tr>
                        <tr>
                            <td><b>City:</b></td>
                            <td>{$order->b_city}</td>
                        </tr>
                        <tr>
                            <td><b>State/Province:</b></td>
                            <td>{$order->billing_state ?: $order->b_state}</td>
                        </tr>
                        <tr>
                            <td><b>Country:</b></td>
                            <td>{$order->billing_country}</td>
                        </tr>
                        <tr>
                            <td><b>Zip/Postal Code:</b></td>
                            <td>{$order->b_zipcode}</td>
                        </tr>
                </table>
            </td>
        </tr>

        {if $order->non_us_confirmation}
            <tr>
                <td colspan="3">
                    <br/>

                    {if $this_is_printable_version != "Y"}
                        <input type="checkbox" checked="checked" name="non_us_confirmation" value="Y" disabled="disabled"/>
                    {else}
                        [X]
                    {/if} I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada.
                </td>
            </tr>
        {/if}

    </table>
    &nbsp;
    <br/>
    <br/>

    {include "mail/invoice_details.tpl"}

    </td>
    </tr>
    </table>


    {if $this_is_printable_version != "Y"}
        {if $order->customer_notes && $ref_notify != 'Y'}
            <table cellspacing="0" cellpadding="0" width="600" bgcolor="#ffffff">
                <tr>
                    <td>
                        <br/>
                        <table cellspacing="0" cellpadding="0" width="100%" border="0">
                            <tr>
                                <td align="center"><span style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">Customer notes</span>
                                </td>
                            </tr>
                        </table>
                        <table cellspacing="0" cellpadding="10" width="100%" style="border: 1px solid;">
                            <tr>
                                <td style="height:50px;">{$order->customer_notes}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        {/if}

        {if $retrieve != "Y" && $order->groups->count()}
            <table cellspacing="0" cellpadding="0" width="600" bgcolor="#ffffff">
                <tr>
                    <td align="center"><br/><br/><span style="FONT-SIZE:12px"></span>
                    </td>
                </tr>
            </table>
        {/if}
    {else}
        <div align="center">
            {if $order->customer_notes && $ref_notify != 'Y'}
                <div><span style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">Customer notes</span></div>
                <div style="height:50px; border: 1px solid;">{$order->customer_notes|nl2br}</div>
            {/if}

        </div>
    {/if}

{/block}

{block 'signature'}
    {add $site = $.getSite}
    {add $config = $site->getGlobalConfig()}
    {add $site_config  = $site->getConfig()}

    <hr size="1" noshade="noshade" />
    Thank you for choosing S3 Stores!
    <p>
        <font size="2">
            {$site_config.company_name}, a division of {$config.operating_company_name}<br/>
            Phone: {$config.company_phone}<br/>
            Fax: {$config.company_fax}<br/>
            URL: <a href="{$site->getAbsoluteUrl()}" target=_new>{$site->getAbsoluteUrl()}</a>
        </font>
    </p>
{/block}