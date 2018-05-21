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

    let formatter = new Intl.NumberFormat("en-US", {
        minimumFractionDigits: 2
    });
    let toLocaleCurrency = (number = 0) => (
        formatter.format(number)
        // number.toLocaleString('en-US', {style: 'currency', currency: 'USD', currencyDisplay: 'code'})
    );

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
        let base_price = null;

        for ( let count in prices ) {
            let i_count = parseInt(count);

            if (i_count) {
                if (!base_price) {
                    base_price = prices[i_count];
                }

                if ( i_count > quantity ) {
                    break;
                }

                price = prices[i_count];
            }
        }

        let extended  = (quantity * price);

        toHtml(product, '[var-price]', toLocaleCurrency(price));
        toHtml(product, '[var-price-extended]', toLocaleCurrency(extended));

        if (quantity) {
            let list_price = parseFloat(product.dataset.listPrice);

            if (list_price && list_price !== price) {
                let safe_percentage = 0;
                let safe_price = 0;
                let per_unit = 0;

                show = true;
                safe_price = ((list_price * quantity) - extended);
                safe_percentage = Math.floor(safe_price / (extended * .01));
                per_unit = (safe_price / quantity);

                toHtml(product, '[var-percentage-safe]', safe_percentage);
                toHtml(product, '[var-price-safe]', toLocaleCurrency(safe_price));
                toHtml(product, '[var-price-perunit-safe]', toLocaleCurrency(per_unit));
            }
        }

        console.log(subtotal_container, show);

        if (subtotal_container) {
            subtotal_container.classList.toggle('hide', !show);
        }
    });
})();