<div class="enter_on_site">
    <div class="enter_on_site__content {if $is_amazon}amazon{/if}">
        Enter this {$distributor->code} order on <a href="{$distributor->d_url_to_login_to_distributor_website}" target="_blank">{$distributor->manufacturer} website!</a>
        {if $is_amazon}
            ${$order_group->getAmazonCompetitorsMinPrice()|price_format}
            {if ($order_group->getAmazonCompetitorsMinShipping())}
                + {$order_group->getAmazonCompetitorsMinShipping()|price_format} shipping
            {else}
                & Free shipping
            {/if}
        {/if}

    </div>
</div>