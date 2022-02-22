<div class="row invoice-buttons">
    <div class=" col-12 col-md-4">
        <a href="/" class="button yellow-white waves waves-orange waves-effect shop-more decoration-none">{t 'Shop more' }</a>
    </div>
    <div class=" d-none d-md-block w-auto ms-auto">
        <div class="row align-right">
            <div class=" text-align--right flex-shrink-0 flex-grow-0 w-auto d-none d-lg-block">
                <a href="{url 'convert:print'}?orderid={$order->orderid}&p={$hash}&mode=print" target="_blank" class="button yellow-white waves waves-orange waves-effect print-invoice decoration-none">
                    {t 'Print invoice' }
                </a>
            </div>
            <div class="flex-shrink-0 flex-grow-0 w-auto text-align--right">
                <a href="{url 'convert:pdf'}?orderid={$order->orderid}&p={$hash}&mode=print" target="_blank" class="button yellow-white waves waves-orange waves-effect open-pdf-invoice decoration-none">
                    {t 'Open PDF invoice' }
                </a>
            </div>
        </div>
    </div>
</div>