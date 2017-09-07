(()=>{
    let catalog_container = $('.catalog-page');
    if (catalog_container.length) {
        $(window).on('component.quantity.change', (e, data) => {
            let show = false;
            let $product = $(data.target).closest('[data-product]');
            let $subtotal_container = $product.find('.subtotal_container');
            let quantity = $product.data('quantity');

            if (quantity) {
                let list_price = parseFloat($product.data('list-price'));


                if (list_price) {
                    let prices = $product.data('prices');
                    let count = 0;
                    let price = 0;
                    let extended = 0;
                    let safe_percentage = 0;
                    let safe_price = 0;
                    let per_unit = 0;


                    for ( count in prices ) {
                        if ( count >= quantity ) {
                            break;
                        }
                    }

                    price = (prices[count]);
                    extended = (quantity * price).toFixed(2);


                    let $price = $product.find('.price_container .current [itemprop=price]');

                    $price.html(price.toFixed(2));

                    if (quantity > 1) {
                        show = true;
                        safe_price = ((list_price * quantity) - extended).toFixed(2);
                        safe_percentage = Math.floor(safe_price / (extended * .01));
                        per_unit = (safe_price / quantity).toFixed(2);

                        $subtotal_container.find('.subtotal .price').html(extended);
                        $subtotal_container.find('.safe .percentage').html(safe_percentage);
                        $subtotal_container.find('.safe .price').html(per_unit);
                    }
                }
            }


            if (show) {
                $subtotal_container.removeClass('hide');
            }
            else {
                $subtotal_container.addClass('hide');
            }
        });
    }
})();