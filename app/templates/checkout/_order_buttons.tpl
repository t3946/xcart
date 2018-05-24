<div class="row invoice-buttons">
    <div class="columns small-12 medium-4">
        <a href="/" class="button yellow-white waves waves-orange waves-effect shop-more">{t 'Shop more' dict='order'}</a>
    </div>
    <div class="columns show-for-medium">
        <div class="row align-right">
            <div class="columns text-align--right show-for-large">
                <a href="{url 'checkout:invoice'}" class="button yellow-white waves waves-orange waves-effect print-invoice">
                    {t 'Print invoice' dict='order'}
                </a>
            </div>
            <div class="columns shrink text-align--right">
                <a href="{url 'convert:pdf'}?orderid={$order->orderid}&p={$hash}" class="button yellow-white waves waves-orange waves-effect open-pdf-invoice">
                    {t 'Open PDF invoice' dict='order'}
                </a>
            </div>
        </div>
    </div>
</div>