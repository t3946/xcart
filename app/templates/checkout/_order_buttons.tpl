<div class="row invoice-buttons">
    <div class=" col-12 col-md-4">
        <a href="/" class="button yellow-white waves waves-orange waves-effect shop-more">{t 'Shop more' }</a>
    </div>
    <div class=" show-for-medium">
        <div class="row align-right">
            <div class=" text-align--right show-for-large">
                <a href="{url 'convert:print'}?orderid={$order->orderid}&p={$hash}&mode=print" target="_blank" class="button yellow-white waves waves-orange waves-effect print-invoice">
                    {t 'Print invoice' }
                </a>
            </div>
            <div class=" shrink text-align--right">
                <a href="{url 'convert:pdf'}?orderid={$order->orderid}&p={$hash}&mode=print" target="_blank" class="button yellow-white waves waves-orange waves-effect open-pdf-invoice">
                    {t 'Open PDF invoice' }
                </a>
            </div>
        </div>
    </div>
</div>