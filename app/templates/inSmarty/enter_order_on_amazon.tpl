<div class="enter_on_site">
    <div class="enter_on_site__content {if $is_amazon}amazon{/if}">
        Enter this {$distributor->code} order on <a href="{$distributor->d_url_to_login_to_distributor_website}" target="_blank">{$distributor->manufacturer} website!</a>
        {if $distributor->order_entry_special_instructions}
            <br/>
            {$distributor->order_entry_special_instructions|html_entity_decode}
        {/if}
        {if $is_amazon}
            ${$order_group->getAmazonCompetitorsMinPrice()|price_format}
            {if ($order_group->getAmazonCompetitorsMinShipping())}
                + {$order_group->getAmazonCompetitorsMinShipping()|price_format} shipping
            {else}
                & Free shipping
            {/if}
        {/if}
        We pay by {$distributor->getField('d_we_pay_to_distributor_by')->toText()}
    </div>
</div>