(()=>{
    let toHtml = ($parent, find, value) => {
        let cont = $parent.find(find);
        if (cont) {
            let old_val = cont.html();
            if (old_val !== value) {
                cont.html(value);
            }
        }
    };

    // let catalog_container = $('.catalog-page');
    // if (catalog_container.length) {
        $(document).on('component.quantity.change', (e, data) => {
            if (!data.val) {
                return;
            }

            let show = false;
            let $product = $(data.target);

            if (!$product.data('product')) {
                $product = $(data.target).closest('[data-product]');
            }

            let $subtotal_container = $product.find('.subtotal_container');
            // let $price = $product.find('.price_container .current [itemprop=price]');

            let quantity = $product.data('quantity');
            let prices = $product.data('prices');
            let count = 0;
            let price = 0;

            for ( count in prices ) {
                if ( count >= (quantity || 1) ) {
                    break;
                }
            }

            price = (prices[count]);

            let extended  = (quantity * price);

            toHtml($product, '[var-price]', price.toFixed(2));
            toHtml($product, '[var-price-extended]', extended.toFixed(2));

            if (quantity) {
                let list_price = parseFloat($product.data('list-price'));

                if (list_price) {
                    let safe_percentage = 0;
                    let safe_price = 0;
                    let per_unit = 0;



                    // if (quantity > 1) {
                        show = true;
                        safe_price = ((list_price * quantity) - extended).toFixed(2);
                        safe_percentage = Math.floor(safe_price / (extended * .01));
                        per_unit = (safe_price / quantity).toFixed(2);


                        toHtml($product, '[var-price-extended]', extended);
                        toHtml($product, '[var-percentage-safe]', safe_percentage);
                        toHtml($product, '[var-price-perunit-safe]', per_unit);
                    // }
                }
            }

            if (show) {
                $subtotal_container.removeClass('hide');
            }
            else {
                $subtotal_container.addClass('hide');
            }
        });
    // }
})();