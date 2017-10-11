(()=>{
    let toHtml = (parent, find, value) => {
        if (parent) {
            let cont = parent.querySelector(find);
            if (cont) {
                let old_val = cont.innerHTML;
                if (old_val !== value) {
                    cont.innerHTML = value;
                }
            }
        }
    };

    let cache = {};
    $(document).on('component.quantity.change', (e, data) => {
        if (!data.val) {
            return;
        }

        let show = false;
        let product = data.product;

        let subtotal_container = product.querySelector('[cont-subtotal]');

        let id = product.dataset.product;
        let quantity = product.dataset.quantity || 1;
        let price = 0;

        if (!cache[id]) {
            cache[id] = JSON.parse(product.dataset.prices);
        }

        let prices = cache[id];

        for ( let count in prices ) {
            if ( parseInt(count) > quantity ) {
                break;
            }

            price = prices[count];
        }

        let extended  = (quantity * price);

        toHtml(product, '[var-price]', price.toFixed(2));
        toHtml(product, '[var-price-extended]', extended.toFixed(2));

        if (quantity) {
            let list_price = parseFloat(product.dataset.listPrice);

            if (list_price) {
                let safe_percentage = 0;
                let safe_price = 0;
                let per_unit = 0;


                // if (quantity > 1) {
                    show = true;
                    safe_price = ((list_price * quantity) - extended);
                    safe_percentage = Math.floor(safe_price / (extended * .01));
                    per_unit = (safe_price / quantity);

                    toHtml(product, '[var-percentage-safe]', safe_percentage);
                    toHtml(product, '[var-price-safe]', safe_price.toFixed(2));
                    toHtml(product, '[var-price-perunit-safe]', per_unit.toFixed(2));
                // }
            }
        }

        if (subtotal_container) {
            if (show) {
                subtotal_container.classList.remove('hide');
            }
            else {
                subtotal_container.classList.add('hide');
            }
        }
    });
})();