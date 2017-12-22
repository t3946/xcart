{extends "base/admin.tpl"}
{block "heading"}
    <h1>{$page_title}</h1>
{/block}
{block "content"}

    {smarty_admin_block name='ICheck'}

        {if $order?}

        <form action="{url 'id_check:start_check'}" >

            <p>Primary Name <input type="text" name="primary.name" value="{$order->s_firstname}"></p>
            <p>Primary Phone <input type="text" name="primary.phone" value="{$phone}"></p>
            <p>Primary Address Street Line 1 <input type="text" name="primary.address.street_line_1" value="{$order->s_address}"></p>
            <p>Primary Address City <input type="text" name="primary.address.city" value="{$order->s_city}"></p>
            <p>Primary Address State Code <input type="text" name="primary.address.state_code" value="{$order->s_state}"></p>
            <p>Primary Address Postal Code <input type="text" name="primary.address.postal_code" value="{$order->s_zipcode}"></p>
            <p>Primary Address Country Code <input type="text" name="primary.address.country_code" value="{$order->s_country}"></p>

            <p>Secondary Name <input type="text" name="secondary.name" value="{$order->b_firstname}"></p>
            <p>Secondary Phone <input type="text" name="secondary.phone" value="{$phone}"></p>
            <p>Secondary Address Street Line <input type="text" name="secondary.address.street_line_1" value="{$order->b_address}"></p>
            <p>Secondary Address City <input type="text" name="secondary.address.city" value="{$order->b_city}"></p>
            <p>Secondary Address State Code <input type="text" name="secondary.address.state_code" value="{$order->b_state}"></p>
            <p>Secondary Address Postal Code <input type="text" name="secondary.address.postal_code" value="{$order->b_zipcode}"></p>
            <p>Secondary Address Country Code <input type="text" name="secondary.address.country_code" value="{$order->b_country}"></p>

            <p>Email Address <input type="text" name="email_address" value="{$order->email}"></p>
            <p>Ip Address <input type="text" name="ip_address" value="{$ip_info}"></p>
            <input type="hidden" name="api_key" value="1687b90051694ce49d92b5214a082912">

            <input type="submit" value="Start Checking">

        </form>
            <br>
        <a href="https://pro.whitepages.com/developer/documentation/identity-check-api/#doc-section-1" target="_blank"> Documentation is here </a>

        {/if}
        {if $response?}
            {foreach $response as $name => $mass}
                <div>
                    <h4>{$name}</h4>
                    {if is_array($mass)}
                        {foreach $mass as $key => $value}
                            {if is_array($value)}
                                <h5>{$key}</h5>
                                {foreach $value as $k => $v}
                                    <p>{$k} => {$v}</p>
                                {/foreach}
                            {else}
                            <p>{$key} => {$value}</p>
                            {/if}
                    {/foreach}
                    {else}
                    <p>{$name} => {$mass}</p>
                    {/if}
                </div>
            {/foreach}
        {/if}

    {/smarty_admin_block}



{/block}
