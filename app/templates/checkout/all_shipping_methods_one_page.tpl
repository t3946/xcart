{set $site = $.getSite}
{set $site_currency = $site->getCurrency()}

{foreach $.app->cart->getItemsGroupedBy() as $gi => $group}
    {include 'checkout/shipping_methods_one_page.tpl'}
{/foreach}
